<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== BBS에서 사용할 설정!

$config['bbs'] = array(
	'b_notices'=>array(
		'0'=>'일반글',
		'1'=>'LV.1',
		'2'=>'LV.2',
		'3'=>'LV.3',
		'4'=>'LV.4',
		'5'=>'LV.5',
		'6'=>'LV.6',
		'7'=>'LV.7',
		'8'=>'LV.8',
		'9'=>'LV.9',
	),
	'b_htmls'=>array(
		't'=>'TEXT',
		'p'=>'PRE',
		'h'=>'HTML',
	),
	'b_htmls_for_admin'=>array(
		't'=>'TEXT',
		'p'=>'PRE',
		'h'=>'HTML',
		'r'=>'RealHTML',
	),
	//파일 저장 위치
	'file_dir'=>realpath(APPPATH.'/../_files/bbs/'),
);