<?php
return array(
//根据产品ID删除单个产品
//==============================================================================//
'tbtest_all' => array(
	'sql' 		=> "delete from {table}",
	'tables' 	=> array('tb_test' => 'a'),
	// 'params'	=> array(
	// 	'goods_id' => array('required' => 'required', 'comment' => '产品ID')
	// ),
	'comments' 	=> array(
		'comment'	=> '根据产品ID删除单个产品',
	),
),
//==============================================================================//
//根据产品ID删除单个产品
//==============================================================================//
'tbtest_by_age' => array(
	'sql' 		=> "delete from {table} where age=:age",
	'tables' 	=> array('tb_test' => 'a'),
	// 'params'	=> array(
	// 	'goods_id' => array('required' => 'required', 'comment' => '产品ID')
	// ),
	'comments' 	=> array(
		'comment'	=> '根据产品ID删除单个产品',
	),
),
//==============================================================================//












































);