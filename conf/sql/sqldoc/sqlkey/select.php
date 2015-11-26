<?php
return array(
//
//================================================================================================================================//
'sqlkey' => array(
	'sql' 		=> 'select {fields} from {table} where a.key_name like :sk',
	'tables'	=> array('sql_key' => 'a',),
	'fields' 	=> array(
		'sql_key' 			=> array('*'),
		'_other_'			=> array(),
	),
	'params'	=> array(
		'sk' => array('required' => 'required', 'comment' => '搜索key', 'eg_val' => '%sqldoc%insert%'),
	),
	'comments' 	=> array(
		'comment'	=> '根据key_name模糊匹配sql key',
	),
),
//================================================================================================================================//
//
//================================================================================================================================//
'sqlkey_by_keyname' => array(
	'sql' 		=> 'select {fields} from {table} where a.key_name = :keyname',
	'tables'	=> array('sql_key' => 'a',),
	'fields' 	=> array(
		'sql_key' 			=> array('*'),
		'_other_'			=> array(),
	),
	'params'	=> array(
		'keyname' => array('required' => 'required', 'comment' => 'keyname', 'eg_val' => 'sql.sqldoc.sqlkey.sqlkey_by_keyname'),
	),
	'comments' 	=> array(
		'comment'	=> '根据key_name准确查找sql key',
	),
),
//================================================================================================================================//



































);