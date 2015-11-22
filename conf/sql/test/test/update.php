<?php
return array(
//根据产品ID更新产品
//==============================================================================//
'tbtest_by_name' => array(
	'sql' 		=> "update {table} set {values} where a.name=:name",
	'tables'	=> array('tb_test' => 'a'),
	'params'	=> array(
		'name' 	=> array('required' => 'required', 'comment' => '产品ID')
	),
	'comments' 	=> array(
		'comment'	=> '根据产品ID更新产品',
	),
),
//==============================================================================//









































);