<?php
return array(
//
//================================================================================================================================//
'param' => array(
	'sql' 		=> 'select {fields} from {table} where a.sk_id = :sk_id and a.param_name = :pname',
	'tables'	=> array('sql_params' => 'a',),
	'fields' 	=> array(
		'sql_params' 		=> array('*'),
		'_other_'			=> array(),
	),
	'params'	=> array(
		'sk_id' => array('required' => 'required', 'comment' => 'sqkl key'),
		'pname' => array('required' => 'required', 'comment' => '参数名称'),
	),
	'comments' 	=> array(
		'comment'	=> '根据产品添加时间获取产品',
	),
),
//================================================================================================================================//




































);