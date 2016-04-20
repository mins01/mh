<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if($_SERVER['REMOTE_ADDR']=='121.189.37.55'){ //과도 접근자.
	header('Location: http://www.police.go.kr');
	exit('');
}


//== mh 설정
$http_host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';
define('SITE_NAME','공대여자홈');
define('SITE_ADMIN_MAIL','mins01.lycos.co.kr@gmail.com');
define('SITE_URI_PREFIX', '/mh/'); // URI 앞부분 경로
define('ADMIN_PREFIX', '_admin'); // 관리자 URI 기본 경로
define('ADMIN_URI_PREFIX', SITE_URI_PREFIX.ADMIN_PREFIX.'/'); // 관리자 URI 기본 경로
define('DB_PREFIX', 'mh_'); // DB 접두사
define('HASH_KEY','mh'); //해시용 추가 문자열. 한번 설정 후 바꾸면 안됩니다!

define('IS_DEV', preg_match('/^[^\/]*dev[^\/]*\./',$http_host));
define('IS_ADMIN', preg_match('|^'.SITE_URI_PREFIX.ADMIN_URI_PREFIX.'|',$_SERVER['REQUEST_URI']));

if(IS_DEV){
	define('LOGIN_NAME','SESD_MH');
}else{
	define('LOGIN_NAME','SESS_MH');
}

define('LOGIN_TYPE','cookie');
define('LOGIN_EXPIRE',60*60*24*365);
define('LOGIN_DOAMIN',$http_host);
//define('LOGIN_PATH',substr(SITE_URI_PREFIX,0,-1));
define('LOGIN_PATH','/');
define('LOGIN_PREFIX','');
define('LOGIN_SECURE',false);

define('ADMIN_LOGIN_NAME',md5('SESS_MH_ADMIN'.$http_host));
define('ADMIN_LOGIN_TYPE','cookie');
define('ADMIN_LOGIN_EXPIRE',60*60*24*365);
define('ADMIN_LOGIN_DOAMIN',$http_host);
define('ADMIN_LOGIN_PATH',substr(ADMIN_URI_PREFIX,0,-1));
define('ADMIN_LOGIN_PREFIX','');
define('ADMIN_LOGIN_SECURE',false);


define('ENCRYPTION_KEY_PREFIX','MH_');
define('_FORM_DIR',dirname(__FILE__).'/../../_form');

date_default_timezone_set('Asia/Seoul'); 

define('MH_LOG_STORE',3); //로그 저장소, 0:로그저장안함, 1: CI로그파일,2:DB, 3:1+2

define('DATE_YMD',date('Y-m-d'));

require_once(dirname(__FILE__).'/legacy.php');




