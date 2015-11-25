<?php
return array(
//根据产品添加时间获取产品
//================================================================================================================================//
'attr' => array(
	'sql' 		=> 'select {fields} from {table} where a.cat_id=:cat_id and b.language_flag=:lang_flag',
	'tables'	=> array('rs_attribute' => 'a','rs_attribute_multi' => 'b'),
	'join'		=> array(
		'relation'	=> '#a join #b',
		'on'		=> 'a.attr_id = b.attr_id'
	),
	'fields' 	=> array(
		'rs_attribute' 			=> array( 'cat_id', 'attr_id', 'left_show'),
		'rs_attribute_multi' 	=> array('language_flag', 'attr_name'),
		'_other_'				=> array(),
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