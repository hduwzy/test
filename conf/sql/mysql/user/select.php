<?php
return array(
//根据产品添加时间获取产品
//================================================================================================================================//
'users' => array(
	'sql' 		=> 'select {fields} from {table}',
	'tables'	=> array('user' => 'a',),
	'fields' 	=> array(
		'user' 			=> array('*'),
		'_other_'		=> array(),
	),
	// 'join'		=> array(
	// 	'relation' 	=> '#a join #b',
	// 	'on' 		=> 'a.goods_id = b.goods_id',
	// 	'join'		=> array(
	// 		'relation' 	=> 'join #c',
	// 		'on'		=> 'a.cat_id = c.cat_id',
	// 	)
	// ),
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