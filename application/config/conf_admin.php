<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== layout에서 사용할 설정!

$config['layout']= array(
	'prefix_title'=> '',
	'suffix_title'=> '::관리자사이트',
);

//-- 레이아웃용 view 파일 설정
$config['layout_view_head'] = 'default_head';
$config['layout_view_tail'] = 'default_tail';
