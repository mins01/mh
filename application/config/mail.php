<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//서버 환경에 맞춰서 수정.
// 화이트 도메인 설정이 필요함.
	$config['mail']= array(
		'protocol'=> 'smtp',//smtp,mail,sendmail
		'smtp_host'=> 'ssl://smtp.gmail.com',
		'smtp_port'=> '465' , //465, 587
		'smtp_user'=> 'xxxx',
		'smtp_pass'=> 'xxxx',
		'smtp_from_name'=>'xxxx',
		
		'smtp_timeout'=> 5,
		//'smtp_crypto'=>'tls', //tls,ssl
		'charset'=>'utf-8',
		'mailtype'=>'html',
	);


require_once(dirname(__FILE__).'/../../../conf/mail.php'); //이부분은 삭제해서 사용.