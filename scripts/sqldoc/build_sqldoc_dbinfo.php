<?php

require_once ("boot.php");

// undefine('ROOT');

use flight\Flight;
// path 下的第一级目录名为Appname，二级目录为module name，最后的文件名为OP type

$path 		= $argv[1];
$key_pref 	= $argv[2];

if (!is_dir($path)) {
	die($path . " is not a valid directory!");
}



$result = get_sqldoc_keys($path, $key_pref);

foreach ($result as $k => $value) {
	$proj_info = parse_key($value);
	$op_type = $proj_info['op_type'];
	unset($proj_info['op_type']);
	
	$proj_id = insert_proj($proj_info);
	$sql_infos = Flight::conf()->get($value . '.*');
	
	foreach($sql_infos as $key => $sql_info){
		$key_name = $value . '.' . $key;
		$sk_id = insert_key($sql_info, $key_name, $proj_id, $op_type);
	}
}


function insert_proj($proj_info)
{
	$is_exsists = Flight::db()->fetchOne('sql.sqldoc.projects.select.proj_by_app_module', $proj_info);

	if (empty($is_exsists)) {
		Flight::db()->insert('sql.sqldoc.projects.insert.projinfo', $proj_info);
		return Flight::db()->lastInsertId();
	}
	return $is_exsists['spj_id'];
}

function insert_key($sql_info, $key_name, $proj_id, $op_type)
{
	$insert_data = array(
		'spj_id'		=> $proj_id,
		'key_name' 		=> $key_name,
		'rel_tb_num' 	=> count($sql_info['tables']),
		//'sql_string' 	=> Flight::db()->prepareSql($rawkey),
		'comments' 		=> $sql_info['comments']['comment'],
		'op_type' 		=> $op_type
	);
	Flight::db()->insert('sql.sqldoc.sqlkey.insert.sqlkey', $insert_data);
	return Flight::db()->lastInsertId();
}

function insert_param()
{

}

function insert_rel_tables()
{

}


function get_sqldoc_keys_recursive($path, &$keys)
{
	if (is_dir($path)) {
		$handle = opendir($path);
		while(($fname = readdir($handle))) {
			if ($fname == '.' || $fname == '..') {
				continue;
			}
			if (is_dir($path . '/' . $fname)) {
				get_sqldoc_keys_recursive($path . '/' . $fname, $keys);
			} else {
				$keys[] = $path . '/' . $fname;
			}
		}
	}
}

function get_sqldoc_keys($path, $key_pref)
{
	$keys = array();
	get_sqldoc_keys_recursive($path, $keys);
	
	foreach ($keys as &$val) {
		$val = $key_pref . '' . substr($val, strlen($path), strrpos($val, '.php') - strlen($path));
		$val = str_replace('/', '.', $val);

	}
	return $keys;
}

function parse_key($key)
{
	$parts = explode('.', $key);
	$result = array();
	$_ = array_shift($parts);
	$result['app_name'] = array_shift($parts);
	$result['op_type'] = array_pop($parts);
	$result['module_name'] = array_shift($parts);
	return $result;
}


