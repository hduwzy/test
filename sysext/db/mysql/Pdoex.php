<?php
namespace sysext\db\mysql;

use sysext\db\mysql\Queryparse;

class Pdoex {
	private $pdos;
	private $parsed_stm;
	private $parser;
	public $app;

	public function __construct()
	{
		$this->pdos = array();
		$this->parsed_stm = array();
		$this->parser = new Queryparse();
	}

	public function fetchRow($key, $params = array())
	{

	}

	public function fetchAll($key, $params = array())
	{

	}

	public function fetchAssoc($key, $params = array())
	{

	}

	public function fetchGroup($key, $params = array())
	{

	}


	public function update($key, $cond, $where)
	{

	}

	public function insert($key, $values)
	{

	}

	public function delete($key, $where)
	{

	}


	public function prepare($key, $params = array())
	{
		if (isset($this->parsed_stm[$key])) {
			return $this->parsed_stm[$key];
		}

		$sql_info = $this->app->conf()->get($key);
		if (!$sql_info) {
			return false;
		}

		$optype = $this->getOptypeFromKey($key);
		$parse_func = "parse" . ucfirst($optype);
		$sql = $this->parser->$parse_func($sql_info, $params);

		$app_name = 

		$this->parsed_stm[$key] = $sql;
		return $stm;
	}

	public function connect($app_name, $read_only = false)
	{
		$grant_string = $readonly ? 'read' : 'write';
		$connect_info_key = "db.{$app_name}.{$grant_string}";
		$connect_info = $this->app->conf()->get($connect_info_key);
		
	}

	public function getOptypeFromKey($key)
	{
		$temp = explode('.', $key);
		array_pop($temp);
		return array_pop($temp);
	}

}