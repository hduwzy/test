<?php

namespace rpc;

class Rpcserver {
	private $_host;
	private $_port;
	private $_schema;
	private $_socket;
	private $_errno;
	private $_errstr;
	private $_lockfile;
	public function __construct()
	{

	}

	public function init()
	{

	}

	public function schema($schema)
	{
		$this->_schema = $schema;
		return $this;
	}

	public function port($port)
	{
		$this->_port = $port;
		return $this;
	}

	public function host($host)
	{
		$this->_host = $host;
		return $this;
	}

	public function getHost()
	{
		return implode('', array($this->_schema . '://', $this->_host . ':', $this->_port));
	}

	public function create()
	{
		if ($this->_socket) {
			fclose($this->_socket);
			$this->_socket = null;
		}
		$this->_socket = stream_socket_server($this->getHost(), $this->_errno, $this->_errstr);
		if (false === $this->_socket) {
			exit('Fail to create socket!');
		}
		return $this;
	}

	public function listen($callback)
	{
		
	}

	public function getSocket()
	{
		return $this->_socket;
	}

	public function singleLock($filename)
	{
		if (!file_exists($filename)) {
			touch($filename);
			$this->_lockfile = $filename;
			return true;
		}
		return false;
	}

	public function singleUnlock()
	{
		if (file_exists($this->_lockfile)) {
			unlink($this->_lockfile);
		}
	}

	public function close()
	{
		if (is_resource($this->_socket)) {
			fclose($this->_socket);
		}
	}

	public function __destruct()
	{
		$this->close();
		$this->singleUnlock();
	}
}