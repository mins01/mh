<?
$config['db_table_manager'] = array();

$config['db_table_manager']['game_hexa'] = array(
	'wheres'=>array(),
	'order_by'=>'gr_date desc',
	'pks'=>array( //수정 삭제 등에 사용할 키값(유니크해야함!!!)
		'gr_nick','gr_date',
	),
);