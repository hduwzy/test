<?php
return array(
//根据产品ID删除单个产品
//==============================================================================//
'product_by_id' => array(
	'sql' 		=> "delete from {table} where goods_id=:goods_id",
	'tables' 	=> array('rs_goods' => 'a'),
	'params'	=> array(
		'goods_id' => array('required' => 'required', 'comment' => '产品ID')
	),
	'comments' 	=> array(
		'comment'	=> '根据产品ID删除单个产品',
	),
),
//==============================================================================//












































);