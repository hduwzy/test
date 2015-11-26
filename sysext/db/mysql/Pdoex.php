<?php
namespace sysext\db\mysql;

use sysext\db\mysql\Queryparse;
use sysext\db\mysql\PDOMemcache;
use \PDO;
class Pdoex {
	private $pdos;
	private $parsed_stm;
	private $stm;
	private $parser;
	public $app;
	private $connect_name;
	private $cache;
	private $last_stm;
	private $last_pdo;

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
		return isset($this->connect_name) ? 
			($this->connect_name) : $this->app->get('app.name');
	}

	public function errorMsg()
	{
		$info = $this->last_stm->queryString . "\n";
		$info .= "errorcode:" . $this->last_stm->errorCode() . "\n";
		$info .= print_r($this->last_stm->errorInfo(), true) . "\n";
		return $info;
	}

	public function setCache(CacheInterface $cache)
	{
		$this->cache = $cache;
	}

	public function getCache()
	{
		if (null === $this->cache) {
			$this->cache = new PDOMemcache();
		}
		return $this->cache;
	}

	public function fetchAll($key, $params = array(), $from_cache = false)
	{
		$stm = $this->prepareStm($key);

		$bool = $stm->execute($this->prefixParams($params));
		if ($stm->errorCode() != '00000') {
			return false;
		}
		$stm->setFetchMode(PDO::FETCH_ASSOC);
		if (($temp = $stm->fetchAll(PDO::FETCH_ASSOC)) === false) {
			return array();
		}
		return $temp;
	}

	public function fetchAllObjects($key, $params = array(), $from_cache = false)
	{
		$stm = $this->prepareStm($key);
		$bool = $stm->execute($this->prefixParams($params));
		if ($stm->errorCode() != '00000') {
			return false;
		}
		$stm->setFetchMode(PDO::FETCH_ASSOC);
		$ret = array();
		while(($temp = $stm->fetchObject())) {
			$ret[] = $temp;
		}
		return $ret;
	}

	public function fetchOne($key, $params = array(), $from_cache = false)
	{
		$stm = $this->prepareStm($key);

		$bool = $stm->execute($this->prefixParams($params));
		if ($stm->errorCode() != '00000') {
			return false;
		}
		$stm->setFetchMode(PDO::FETCH_ASSOC);
		if (($temp = $stm->fetch()) === false) {
			return array();
		} 
		return $temp;
	}

	public function fetchGroup($key, $params = array(), $from_cache = false)
	{
		// TODO
	}

	public function lastInsertId()
	{
		if ($this->last_pdo) {
			return $this->last_pdo->lastInsertId();
		}
		return false;
	}

	public function update($key, $values, $params = array())
	{
		$stm = $this->prepareStm($key, $values);
		$bool = $stm->execute($params);
		if ($stm->errorCode() != '00000') {
			return false;
		}
		return $stm->rowCount();
	}

	public function insert($key, $values)
	{
		$stm = $this->prepareStm($key, $values);
		$bool = $stm->execute();
		if ($stm->errorCode() != '00000') {
			return false;
		}
		return $stm->rowCount();
	}

	public function delete($key, $params = array())
	{
		$stm = $this->prepareStm($key);
		$bool = $stm->execute($params);
		if ($stm->errorCode() != '00000') {
			return false;
		}
		return $stm->rowCount();
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
			if (empty($params)) {
				$this->parsed_stm[$key] = $sql . $this->extraSql();	
			} else {
				return $sql . $this->extraSql();
			}
		}

		return $this->parsed_stm[$key];
	}

	public function prepareStm($key, $params = array(), $read_only = false)
	{
		$sql = $this->prepareSql($key, $params);
		$do_cache = empty($params);
		$sql_md5 = md5($sql);
		if (isset($this->stm[$sql_md5]) && $do_cache) {
			return $this->stm[$sql_md5];
		}
		$pdo = $this->connect($this->getConnectName(), $read_only);
		$stm = $pdo->prepare($sql);
		if ($do_cache) {
			$this->stm[$sql_md5] = $stm;
		}
		$this->last_stm = $stm;
		$this->last_pdo = $pdo;
		return $stm;
	}

	public function prefixParams($params)
	{
		$new_param = array();
		foreach ($params as $key => $value) {
			$key = ':' . $key;
		}
		return $params;
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
		$grant_string = $read_only ? 'read' : 'write';
		$connect_info_key = "db.{$app_name}.{$grant_string}.*";

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
		return $temp[count($temp) - 2];
	}
}