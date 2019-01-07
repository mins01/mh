<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== layout에서 사용할 설정!

$config['layout']= array(
	'prefix_title'=> '',
	'suffix_title'=> '::'.SITE_NAME,
);

$config['db']= array(
'prefix'=>'',//현재 사용안함.
);


//--- layout용 head
$config['layout_keywords'] = '공대여자,웹,프로그래밍,DB,PHP,MySQL,ORACLE';
//--- layout용 head.php 속 meta-og 용
$config['layout_og_title'] = '공대여자 홈';
$config['layout_og_description'] = '공대여자 홈';
$config['layout_og_image'] = 'http://www.mins01.com/img/logo.gif';
$config['layout_og_image_width'] = '190';
$config['layout_og_image_height'] = '70';
$config['layout_og_site_name'] = '공대여자 홈';
$config['layout_og_type'] = 'website';