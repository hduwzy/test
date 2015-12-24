<?php

namespace rpc;
use cli\proc\Process;
use cli\events\EventInterface;
use flight\Flight;
use cli\mem\Msgqueue;

class Rpcmaster {
	// 系统php路径
	private $_php_exe;
	// 脚本入口路径
	private $_run_path;
	// 已启动进程数
	private $_shell_count;
	// 命令等候队列
	private $_cmd_queue;

	private $_runing_list;

	private $_spare_time;

	public function __construct()
	{
		$this->_shell_count = 0;
		$this->_cmd_queue = array();
		$this->_runing_list = array();
		$this->_spare_time = array();
		$this->_run_path = dirname(dirname(__FILE__)) . "/run.php";
		Process::onSysEvent(SIGCHLD, EventInterface::EV_SIGNAL, array($this, 'sig_child'));
	}

	/**
	 * 设置php可执行文件路径
	 * @param [string] $path [路径]
	 */
	public function setPhpExe($path)
	{
		$this->_php_exe = $path;
		return $this;
	}

	/**
	 * 生成shell命令
	 * @param  [string] $command [命令名称]
	 * @param  [array] $params  [参数]
	 * @return [string]          [shell命令]
	 */
	public function getShellCmd($command, $params)
	{
		$shell_cmd = "nohup ";
		$shell_cmd .= $this->_php_exe;
		$shell_cmd .= " {$this->_run_path} {$command} ";
		$shell_cmd .= implode(' ', $params);
		$shell_cmd .= " >/dev/null 2>&1 &";
		return $shell_cmd;
	}

	public function getProcessCmd($command, $params)
	{
		if (($cmd = Flight::conf()->get('cli.rpccmd.' . $command))) {
			if (!class_exists($cmd)) {
				return false;
			}
			$cmd = new $cmd();
			return array(
				'closure' => array($cmd, 'handle'),
				'params' => $params
			);
		}
		return false;
	}
	
	// 命令插入队列尾部
	public function pushCmd($cmd)
	{
		array_push($this->_cmd_queue, $cmd);
		return $this;
	}

	//命令队列弹出队头的命令
	public function shiftCmd()
	{
		return array_shift($this->_cmd_queue);
	}

	// 获取等候命令的长度
	public function queueLength()
	{
		return count($this->_cmd_queue);
	}

	// 解析命令,json格式
	private function _parseCmd($socket)
	{
		
		if (!$socket) {
			// log
			exit('Fail to get socket');
		}
		$cmd_str = '';
		stream_set_blocking($socket, 0);
		while(!feof($socket)) {
			$cmd_str .= fread($socket, 1024);
			if (strpos($cmd_str, "\r\n") !== false) {
				$cmd_str = str_replace("\r\n", '', $cmd_str);
				break;
			}
		}
		var_dump($cmd_str);
		return json_decode($cmd_str);
	}

	/**
	 * socket事件回调函数
	 * @param  [resource] $socket [服务器套接字]
	 * @param  [int] $ev_tag [事件标志]
	 * @param  [array] $args   [回调参数]
	 * @return [none]
	 */
	public function handle($socket = null, $ev_tag = null, $args = null)
	{
		// 回去远程命令
		date_default_timezone_set('Asia/Shanghai');
		if (null === $socket) {
			$cmds = new \stdClass();
			$cmds->command = '_finished_';
		} else {
			$rec_socket = stream_socket_accept($socket);
			$cmds = $this->_parseCmd($rec_socket);
		}
		if (empty($cmds)) {
			$cmds = array();
		}
		$cmds = is_object($cmds) ? array($cmds) : $cmds;
		$log_file = Flight::conf()->get('cli.sys.rpc_logpath') . date('Y-m-d') . '.log';
		// 逐个处理命令
		foreach ($cmds as $cmd) {
			if (!isset($cmd->params)) {
				$cmd->params = array();
			}
			
			$cur_cmd = $cmd;
			if ($cur_cmd->command == '_status_') {// 进程结束通知
				$status = array();
				
				$status['start_time'] = date('Y-m-d H:i:s', Process::$status['start_time']);
				$status['runing_list'] = $this->_runing_list;
				// $wait_cmd = array();
				// array_walk($this->_cmd_queue, function($v, $k)use(&$wait_cmd){
				// 	$wait_cmd[] = $v['command'];
				// });
				$status['waiting_list'] = $this->_cmd_queue;
				fwrite($rec_socket, json_encode($status));
				continue;
			} elseif($cur_cmd->command == '_log_')  {
				$tail_n = isset($cur_cmd->params[0]) ? (int)$cur_cmd->params[0] : 10;
				if (file_exists($log_file)) {
					$log_data = `tail -n {$tail_n} {$log_file}`;
				}
				fwrite($rec_socket, json_encode($log_data));
				continue;
			} elseif ($cur_cmd->command == '_finished_') {// 进程结束通知
				$this->_shell_count --; // 正在运行的进程计数自减
				if (($queued_cmd = $this->shiftCmd())) {
					// 一个进程结束后，判断队列是否有正在等待的命令，
					// 有则唤起
					$cur_cmd = $queued_cmd;
				} else {
					// 队列中没有等待命令，忽略
					continue;
				}
			} else {
				if ($this->_shell_count >= Flight::conf()->get('cli.sys.maxprocess')) {
					// 正在运行的进程是否已经达到上限
					// 是则插入等待队列，继续等待
					$this->pushCmd($cur_cmd);
					continue;
				} else {
					$queued_cmd = $this->shiftCmd();
					
					if (!empty($queued_cmd)) {
						// 队列中有等待的命令，先处理
						$this->pushCmd($cur_cmd);
						$cur_cmd = $queued_cmd;
					} else {
						// 队列中没等待命令
					}
				}
			}
			
			$process_cmd = $this->getProcessCmd($cur_cmd->command, $cur_cmd->params);

			if (false === $process_cmd) {
				continue;
			}
			
			$id = Process::fork($cur_cmd->command, $process_cmd['closure'], $process_cmd['params']);
			$log = date('H:i:s') . " start($id):" . json_encode($cur_cmd);
			$ret = `echo "{$log}" >> {$log_file}`;
			$this->_runing_list[$id] = $cur_cmd;
			$this->_spare_time[$id] = microtime(1);
			$this->_shell_count ++;
		}
	}

	public function sig_child($fd, $events, $args)
	{
		$status = -1;
		date_default_timezone_set('Asia/Shanghai');
		while(($pid = \pcntl_waitpid(-1, $status, WNOHANG)) > 0) {
			foreach (Process::$child as $key => $value) {
				if ($value == $pid) {
					unset(Process::$child[$key]);
					foreach (Process::$alias as $key => $value) {
						if ($pid == $value) {
							unset(Process::$alias[$key]);
						}
					}
				}
			}
			Msgqueue::delQueue($pid);
			$log_file = Flight::conf()->get('cli.sys.rpc_logpath') . date('Y-m-d') . '.log';
			$spare_time = (microtime(1) - $this->_spare_time[$pid]);
			$spare_time = sprintf("%.2f", $spare_time);
			if (pcntl_wifexited($status)) {
				$exit_code = pcntl_wexitstatus($status);
				$log = date("H:i:s") . " exit({$exit_code}-{$pid}):" . json_encode($this->_runing_list[$pid]) . " $spare_time";
			} else {
				$exit_code = -1;
				$log = date("H:i:s") . " exit({$exit_code}-{$pid}):" . json_encode($this->_runing_list[$pid]) . " $spare_time";
			}
			$ret = `echo "{$log}" >> {$log_file}`;
			unset($this->_runing_list[$pid]);
			unset($this->_spare_time[$pid]);
			$this->handle();
		}
	}
}