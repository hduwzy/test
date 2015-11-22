<?php
return array(
//根据表的名称获取列的信息
//================================================================================================================================//
'columns_by_tbname' => array(
	'sql' 		=> 'select {fields} from {table} where a.table_name = :tbname',
	'tables'	=> array('`information_schema`.`COLUMNS`' => 'a',),
	'fields' 	=> array(
		'`information_schema`.`COLUMNS`' 	=> array('table_name', 'column_name', ),
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
	'params'	=> array(
		'tbname' => array('required' => 'required', 'comment' => '表名称'),
	),
	'comments' 	=> array(
		'comment'	=> '根据表的名称获取列的信息',
	),
),
//================================================================================================================================//



































);