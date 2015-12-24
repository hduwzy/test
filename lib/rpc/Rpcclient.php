<?php

namespace rpc;

class Rpcclient {
	private $_host;
	private $_port;
	private $_schema;
	private $_client;
	private $_timeout;
	private $_cmds;

	public function __construct()
	{
		$this->schema('tcp')->timeout(3);
		$this->_cmds = array();
	}


	public function getHost()
	{
		return implode('', array($this->_schema . '://', $this->_host . ':', $this->_port));
	}

	public function create()
	{
		if (is_resource($this->_client)) {
			fclose($this->_client);
			$this->_client = null;
		}
		$this->_client = stream_socket_client($this->getHost(), $this->_errno, $this->_errstr, $this->_timeout);
		if (!$this->_client) {
			exit($this->_errno . ":" . $this->_errstr . "\n" );
		}
		return $this;
	}

	public function schema($schema)
	{
		$this->_schema = $schema;
		return $this;
	}

	public function host($host)
	{
		$this->_host = $host;
		return $this;
	}

	public function port($port)
	{
		$this->_port = $port;
		return $this;
	}

	public function timeout($timeout)
	{
		$this->_timeout = $timeout;
		return $this;
	}

	public function write($content)
	{	
		$ret = fwrite($this->_client, $content . "\r\n");
		return $this;
	}

	public function read($length = 1024)
	{
		$content = fread($this->_client, $length);
		return $content;
	}

	public function close()
	{
		if (is_resource($this->_client)) {
			fclose($this->_client);
		}
	}

	public function call($cmd, $params = array())
	{
		$this->_cmds[] = array(
			'command' => $cmd,
			'params' => $params,
		);
		return $this;
	}

	public function commit()
	{
		$this->create();
		$content = json_encode($this->_cmds);
		$this->write($content);
		$this->close();
		return $this;
	}

	public function remoteStatus()
	{
		$cmd = json_encode(array(
			'command' => '_status_',
			'params' => array()
		));

		$this->create();
		$this->write($cmd);
		fflush($this->_client);
		$ret = '';
		while(($response = fread($this->_client, 1024)) != '') {
			$ret .= $response;
		}
		fclose($this->_client);
		return json_decode($ret);
	}

	public function remoteLog($n)
	{
		$cmd = json_encode(array(
			'command' => '_log_',
			'params' => array($n)
		));

		$this->create();
		$this->write($cmd);
		fflush($this->_client);
		$ret = '';
		while(($response = fread($this->_client, 1024)) != '') {
			$ret .= $response;
		}
		fclose($this->_client);
		return json_decode($ret);
	}

	public function __destruct()
	{
		$this->close();
	}
}