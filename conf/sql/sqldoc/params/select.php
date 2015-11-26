<?php
return array(
//根据产品添加时间获取产品
//================================================================================================================================//
'products_by_addtime' => array(
	'sql' 		=> 'select {fields} from {table} where a.addtime >= :ltime and a.addtime <= :rtime',
	'tables'	=> array('rs_goods' => 'a', 'rs_goods_multi' => 'b', 'rs_category' => 'c'),
	'join'		=> array(
		'relation' 	=> '#a join #b',
		'on' 		=> 'a.goods_id = b.goods_id',
		'join'		=> array(
			'relation' 	=> 'join #c',
			'on'		=> 'a.cat_id = c.cat_id',
		)
	),
	'fields' 	=> array(
		'rs_goods' 			=> array(),
		'rs_goods_multi'	=> array(),
		'rs_category'		=> array(),
		'_other_'			=> array(),
	),
	'params'	=> array(
		'ltime' => array('required' => 'required', 'comment' => '时间左区间'),
		'rtime' => array('required' => 'option', 'comment' => '时间右区间'),
	),
	'comments' 	=> array(
		'comment'	=> '根据产品添加时间获取产品',
	),
),
//================================================================================================================================//
//根据产品分类获取产品
//================================================================================================================================//
'products_by_cat' => array(
	'sql' 		=> 'select {fields} from {table} where b.cat_id=:cat_id',
	'tables'	=> array('rs_goods' => 'b'),
	'fields' 	=> array(
		'rs_goods' 			=> array(),
		'_other_'			=> array(),
	),
	'params'	=> array(
		'cat_id' => array('required' => 'required', 'comment' => '分类id'),
	),
	'comments' 	=> array(
		'comment'	=> '根据产品分类获取产品',
	),
),
//================================================================================================================================//




































);