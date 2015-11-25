<?php
namespace sysext\db\mysql;

class Queryparse {

	public function parseSelect($sql_info)
	{
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

	public function parseUpdate($sql_info, $values = array())
	{
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

	public function parseInsert($sql_info, $values = array())
	{
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

	public function parseDelete($sql_info)
	{
		if (!$sql_info) {
			return false;
		}
		$sql = $sql_info['sql'];
		$tables = $sql_info['tables'];
		$tbname = key($tables);

		return str_replace('{table}', $tbname, $sql);
	}
}

