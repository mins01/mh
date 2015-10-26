<?php
defined('BASEPATH') OR exit('No direct script access allowed');

$config['mail']= array(
	'protocol'=> 'smtp',
	'smtp_host'=> '--',
	'smtp_user'=> '--',
	'smtp_pass'=> '--',
	'smtp_port'=> 465 , // 587
	'smtp_timeout'=> 10,
);

require_once(dirname(__FILE__).'/../../../conf/mail.php');