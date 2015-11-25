<?php
namespace sysext\db\mysql;

use sysext\db\mysql\Queryparse;

class Pdoex {
	private $pdos;
	private $parsed_stm;
	private $stm;
	private $parser;
	public $app;
	private $connect_name;

	public function __construct()
	{
		$this->pdos 		= array();
		$this->parsed_stm 	= array();
		$this->stm 			= array();
		$this->parser 		= new Queryparse();
	}

	public function setConnectName($name)
	{
		$this->connect_name = $name;
	}

	public function getConnectName()
	{
		return isset($this->connect_name) ? $this->connect_name : $this->app->get('app.name');
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

	public function update($key, $values, $params = array())
	{

	}

	public function insert($key, $values)
	{

	}

	public function delete($key, $params = array())
	{

	}


	public function prepareSql($key, $params = array())
	{
		if (!isset($this->parsed_stm[$key])) {
			$sql_info = $this->app->conf()->get($key);
			if (!$sql_info) {
				return false;
			}
			$optype = $this->getOptypeFromKey($key);
			$parse_func = "parse" . ucfirst($optype);
			$sql = $this->parser->$parse_func($sql_info, $params);
			if (empty($sql)) {
				return false;
			}
			$this->parsed_stm[$key] = $sql . $this->extraSql();
		}

		return $this->parsed_stm[$key];
	}

	public function prepareStm($key, $params = array(), $read_only = false)
	{
		$sql = $this->prepareSql($key, $params);
		$pdo = $this->connect($this->getConnectName(), $read_only);
		$stm = $pdo->prepare($sql);
		// TODO...
		// 缓存解析后的statement
	}

	public function prefixParams($v, &$k)
	{
		$k = ':' . $k;
	}

	public function order($field = '')
	{
		$this->orderBy = $field;
		return $this;
	}

	public function limit($la = -1, $lb = -1)
	{
		$this->offset = ($la > 0 ) ? (($lb > 0) ? ($la) : (0)) : null;
		$this->limit  = ($la > 0 ) ? (($lb > 0) ? ($lb) : ($la)) : null;
		return $this;
	}

	public function group($field)
	{
		$this->groupBy = $field;
		return $this;
	}

	public function extraSql()
	{
		$extra_arr = array();
		if (isset($this->groupBy)) {
			$extra_arr[] = " group by " . $this->groupBy;
		}

		if (isset($this->orderBy)) {
			$extra_arr[] = " order by " . $this->orderBy;
		}

		if (isset($this->limit) && $this->limit > 0) {
			if (isset($this->offset) && $this->offset > 0) {
				$extra_arr[] = " limit {$this->offset},{$this->limit}";
			} else {
				$extra_arr[] = " limit {$this->limit}";
			}
		}
		unset($this->groupBy);
		unset($this->offset);
		unset($this->limit);
		unset($this->orderBy);
		return implode(' ', $extra_arr);
	}

	public function connect($app_name, $read_only = false)
	{
		$grant_string = $readonly ? 'read' : 'write';
		$connect_info_key = "db.{$app_name}.{$grant_string}";

		if (isset($this->pdos[$connect_info_key])) {
			return $this->pdos[$connect_info_key];
		}

		$connect_info = $this->app->conf()->get($connect_info_key);
		try {
			$pdo = new PDO(
				$connect_info['dsn'],
				$connect_info['username'],
				$connect_info['password']
			);
		} catch (PDOException $e) {
			echo 'Connection failed: ' . $e->getMessage();
		}

		$this->pods[$connect_info_key] = $pdo;
		return $pdo;
	}

	public function getOptypeFromKey($key)
	{
		$temp = explode('.', $key);
		return $temp[count($temp) - 1];
	}

}