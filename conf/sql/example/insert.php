<?php
return array(
//根据产品添加时间获取产品
//================================================================================================================================//
'products' => array(
	'sql' 		=> 'insert into {table} {values}',
	'tables'	=> array('rs_goods' => 'a'),
	'fields'	=> array(
		'goods_id'	=> array('type'=>'integer', 'null'=>'no', 'key'=>'pri', 'default'=>'null', 'extra'=>'auto_increment'),
		'cat_id'	=> array('type'=>'integer', 'null'=>'no', 'key'=>'mul', 'default'=>'0', 'extra'=>''),
		'site_id'	=> array('type'=>'integer', 'null'=>'no', 'key'=>'', 'default'=>'null', 'extra'=>''),
		'site_id'	=> array('type'=>'integer', 'null'=>'no', 'key'=>'', 'default'=>'null', 'extra'=>''),
	),
	'comments' 	=> array(
		'comment'	=> '根据产品添加时间获取产品',
	),
),
//================================================================================================================================//































);