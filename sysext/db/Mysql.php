<?php

namespace sysext\db;

use Aura\Sql\ExtendedPdo;

class Mysql{
	public $pdo;

	public $app;

	public function __call($func, $params)
	{
		if (!$this->pdo) {
			$app = $this->app;
			$db_conf = $app->conf()->get('admin.db.*');
			$this->pdo = new ExtendedPdo(
				$db_conf['dsn'],
				$db_conf['username'],
				$db_conf['password']
			);
		}

		$key = $params[0];
		$param = isset($params[1]) ? $params[1] : array();
		$temp = explode('.', $key);
		$op = "parse" . ucfirst($temp[count($temp) - 2]) . "Sql";
		$stm = call_user_func_array(array($this, $op), array($key, $param));

		if (!$stm) {
			return false;
		}
		$param = isset($params[2]) ? $params[2] : $param;
		$stm .= $this->extraSql();
		return call_user_func_array(array($this->pdo, $func), array($stm, $param));
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

	public function parseSelectSql($key, $params = array())
	{
		$sql_info 	= $this->app->conf()->get($key);
		if (!$sql_info) {
			return false;
		}
		$sql 		= $sql_info['sql'];
		$tables 	= $sql_info['tables'];
		$join		= isset($sql_info['join']) ? $sql_info['join'] : null;
		$fields		= $sql_info['fields'];
		$otherfield = array();
		if (isset($fields['_other_'])) {
			$otherfield = $fields['_other_'];
			unset($fields['_other_']);
		}
		$fields_str = '';
		$temp = array();
		foreach ($fields as $key => $fields_arr) {
			$tb_alia = $tables[$key];
			foreach ($fields_arr as &$fname) {
				$fname = $tb_alia . '.' . $fname;
			}
			if (!empty($fields_arr)) {
				$temp[] = implode(',', $fields_arr);
			}
		}
		$temp += $otherfield;
		$fields_str = implode(',', $temp);
		

		$table_str 	= '';
		$tables 		= array_flip($tables);
		
		if (!$join) {
			$table_str = current($tables) . " as " . key($tables);
		}

		while($join) {
			$relation = $join['relation'];
			foreach ($tables as $alia => $tbname) {
				$repl_alia = '#' . $alia;
				$tbname = "{$tbname} as {$alia}";
				$relation = str_replace($repl_alia, $tbname, $relation);
			}
			$table_str .= $relation . ' on ' . $join['on'];
			if (isset($join['join'])) {
				$table_str = "($table_str)";
				$join = $join['join'];
			} else {
				break;
			}
		}

		$sql = str_replace(array('{fields}', '{table}'), array($fields_str, $table_str), $sql);

		return $sql;
	}

	public function parseUpdateSql($key, $values = array())
	{
		$sql_info = $this->app->conf()->get($key);
		if (!$sql_info) {
			return false;
		}
		$sql = $sql_info['sql'];
		$tables = $sql_info['tables'];

		$tbname = key($tables) . " as " . current($tables);
		$sql = str_replace('{table}', $tbname, $sql);

		$values_str = '';
		$temp = array();
		foreach ($values as $key => $val) {
			if (is_string($val)) {
				$val = "\"$val\"";
			}
			$temp[] = "$key=$val";
		}
		$values_str .= implode(',', $temp);

		$sql = str_replace('{values}', $values_str, $sql);

		return $sql;
	}

	public function parseInsertSql($key, $values = array())
	{
		$sql_info = $this->app->conf()->get($key);
		if (!$sql_info) {
			return false;
		}
		$sql = $sql_info['sql'];
		$tables = $sql_info['tables'];

		$tbname = key($tables);
		$sql = str_replace('{table}', $tbname, $sql);

		$values_str = '';
		$temp = array();
		$cur = current($values);

		if (is_array($cur)) {
			$keys = array_keys($cur);
			$v_temp = array();
			foreach ($values as $key => $value) {
				array_walk($value, array($this, 'sqlValue'));
				$v_temp[] = "(" . implode(',', $value) . ")";
			}
			$values_str = "(" . implode(',', $keys) . ") values " . implode(',', $v_temp);
		} else {
			$keys = array_keys($values);
			$values = array_values($values);
			array_walk($values, array($this, 'sqlValue'));
			$values_str = "(" . implode(',', $keys) . ") values (" .  implode(',', $values) . ")";
		}

		$sql = str_replace('{values}', $values_str, $sql);
		return $sql;
	}

	public function parseDeleteSql($key, $params = array())
	{
		$sql_info = $this->app->conf()->get($key);
		if (!$sql_info) {
			return false;
		}
		$sql = $sql_info['sql'];
		$tables = $sql_info['tables'];
		$tbname = key($tables);

		return str_replace('{table}', $tbname, $sql);
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

	public function sqlValue(&$v, $k)
	{
		if (is_string($v)) {
			$v = "\"{$v}\"";
		}
	}
}