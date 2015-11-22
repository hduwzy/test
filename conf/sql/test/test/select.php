<?php
return array(
//根据产品添加时间获取产品
//================================================================================================================================//
'tbtest' => array(
	'sql' 		=> 'select {fields} from {table}',
	'tables'	=> array('tbtest' => 'a',),
	// 'join'		=> array(
	// 	'relation' 	=> '#a join #b',
	// 	'on' 		=> 'a.goods_id = b.goods_id',
	// 	'join'		=> array(
	// 		'relation' 	=> 'join #c',
	// 		'on'		=> 'a.cat_id = c.cat_id',
	// 	)
	// ),
	'fields' 	=> array(
		'tb_test' 			=> array('*'),
		'_other_'			=> array(),
	),
	// 'params'	=> array(
	// 	'ltime' => array('required' => 'required', 'comment' => '时间左区间'),
	// 	'rtime' => array('required' => 'option', 'comment' => '时间右区间'),
	// ),
	'comments' 	=> array(
		'comment'	=> '根据产品添加时间获取产品',
	),
),
//================================================================================================================================//
//根据产品添加时间获取产品
//================================================================================================================================//
'tbtest_by_name' => array(
	'sql' 		=> 'select {fields} from {table} where a.name=:name',
	'tables'	=> array('tb_test' => 'a',),
	// 'join'		=> array(
	// 	'relation' 	=> '#a join #b',
	// 	'on' 		=> 'a.goods_id = b.goods_id',
	// 	'join'		=> array(
	// 		'relation' 	=> 'join #c',
	// 		'on'		=> 'a.cat_id = c.cat_id',
	// 	)
	// ),
	'fields' 	=> array(
		'tb_test' 			=> array('*'),
		'_other_'			=> array(),
	),
	// 'params'	=> array(
	// 	'ltime' => array('required' => 'required', 'comment' => '时间左区间'),
	// 	'rtime' => array('required' => 'option', 'comment' => '时间右区间'),
	// ),
	'comments' 	=> array(
		'comment'	=> '根据产品添加时间获取产品',
	),
),
//================================================================================================================================//
//根据产品添加时间获取产品
//================================================================================================================================//
'tbtest_by_age' => array(
	'sql' 		=> 'select {fields} from {table} where a.age=:age',
	'tables'	=> array('tb_test' => 'a',),
	// 'join'		=> array(
	// 	'relation' 	=> '#a join #b',
	// 	'on' 		=> 'a.goods_id = b.goods_id',
	// 	'join'		=> array(
	// 		'relation' 	=> 'join #c',
	// 		'on'		=> 'a.cat_id = c.cat_id',
	// 	)
	// ),
	'fields' 	=> array(
		'tb_test' 			=> array('*'),
		'_other_'			=> array(),
	),
	// 'params'	=> array(
	// 	'ltime' => array('required' => 'required', 'comment' => '时间左区间'),
	// 	'rtime' => array('required' => 'option', 'comment' => '时间右区间'),
	// ),
	'comments' 	=> array(
		'comment'	=> '根据产品添加时间获取产品',
	),
),
//================================================================================================================================//



































);