<?php
defined('BASEPATH') OR exit('No direct script access allowed');
// ver.2021040909
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
	'file_dir'=>realpath(APPPATH.'/../../../mh_files/bbs/'),

	//관리자에서 사용할 값들
	'list_types'=>array(
		'0'=>'답변순서 적용',
		'1'=>'작성 순서(역순)',
		'2'=>'종료날짜 순서(역순)',
	),
	'levels'=>array(
		'0'=>'비회원',

		'1'=>'일반회원',
		'10'=>'일반회원10',
		'20'=>'일반회원20',
		'90'=>'서브관리자',
		'99'=>'관리자',
		'100'=>'사용금지',
	),
	'show_holiday'=>true, //ics 를 사용한 달력스킨 휴일 표기설정용
	'upload_dir_permission'=>0777,  //업로드 경로 permission read+write+exec
	'upload_file_permission'=>0666,  //업로드 파일 permission read+write
);
