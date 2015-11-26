<?php
return array(
//
//================================================================================================================================//
'proj_by_app_module' => array(
	'sql' 		=> 'select {fields} from {table} where a.app_name = :app_name and a.module_name = :module_name',
	'tables'	=> array('sql_porjects' => 'a', ),
	'fields' 	=> array(
		'sql_porjects' 			=> array('*'),
		'_other_'			=> array(),
	),
	'params'	=> array(
		'app_name' 		=> array('required' => 'required', 'comment' => 'app名称'),
		'module_name' 	=> array('required' => 'option', 'comment' => '模块名称'),
	),
	'comments' 	=> array(
		'comment'	=> '根据app名称，模块名称，查找项目信息（判断项目是否已经存在）',
	),
),



































);