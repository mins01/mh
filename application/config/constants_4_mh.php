<?php
defined('BASEPATH') OR exit('No direct script access allowed');

if(isset($_SERVER['REMOTE_ADDR'][0]) && $_SERVER['REMOTE_ADDR']=='121.189.37.55'){ //과도 접근자.
	header('Location: http://www.police.go.kr');
	exit('');
}

//== mh 설정
$http_host = isset($_SERVER['HTTP_HOST'])?$_SERVER['HTTP_HOST']:'';


define('USE_CACHE',true); //전역 캐시 사용여부
define('USE_MH_CACHE',USE_CACHE && true); //Mh_cache만 캐시 사용여부


define('REFLESH_TIME','20200807_0'); //query string 에 붙여서 파일 웹 캐싱 무시용. 수동으로 변경한다.

define('SITE_NAME','공대여자홈');
define('SITE_ADMIN_MAIL','mins01.lycos.co.kr@gmail.com');
define('SITE_URI_PREFIX', '/mh/'); // URI 앞부분 경로
define('SITE_URI_ASSET_PREFIX', '/mh/asset/'); // aaset 폴더 접속용 URI 앞부분 경로
define('SERVER_PATH_ASSET', APPPATH.'../asset/'); // 서버 내의 aaset 폴더
define('ADMIN_PREFIX', '_admin'); // 관리자 URI 기본 경로
define('ADMIN_URI_PREFIX', SITE_URI_PREFIX.ADMIN_PREFIX.'/'); // 관리자 URI 기본 경로
define('DB_PREFIX', 'mh_'); // DB 접두사
define('HASH_KEY','mh'); //해시용 추가 문자열. 한번 설정 후 바꾸면 안됩니다!

define('IS_DEV', preg_match('/^[^\/]*dev[^\/]*\./',$http_host));
define('IS_ADMIN', preg_match('|^'.SITE_URI_PREFIX.ADMIN_URI_PREFIX.'|',(isset($_SERVER['REQUEST_URI'])?$_SERVER['REQUEST_URI']:'')));


if(IS_DEV){
	define('USE_HTTPS',false); // HTTPS 사용가능여부
	define('HTTPS_PORT',''); // HTTPS 포트
	define('LOGIN_NAME','SESD_MH');
}else{
	define('USE_HTTPS',false); // HTTPS 사용가능여부
	define('HTTPS_PORT',''); // HTTPS 포트
	define('LOGIN_NAME','SESS_MH');
}

define('MEMBER_LAYOUT','default'); //Member layout 설정. 기본:default, (empty ...)
define('MEMBER_ONLY_HTTPS', USE_HTTPS && true ); // /mh/member 동작시 https로 강제한다.

//font 의 기본 layout 을 바꾸고 싶다면 conf_front.php를 수정하라.

// define('LOGIN_TYPE','cookie');
define('LOGIN_TYPE','session');
// 세션일 땐 만료일, 도메인 등이 동작 안한다.
define('LOGIN_EXPIRE',60*60*24*365);
define('LOGIN_VERIFY_EXPIRE',60*60*24*7); //세션 암호화 체크 expire
define('LOGIN_REFRESH_EXPIRE',LOGIN_VERIFY_EXPIRE/100); //세션 암호화 갱신 expire
define('LOGIN_DOAMIN',$http_host);
//define('LOGIN_PATH',substr(SITE_URI_PREFIX,0,-1));
define('LOGIN_PATH','/; samesite=strict'); //php 7.3 미만 버번용
define('LOGIN_PREFIX','');
define('LOGIN_SECURE',false);

define('ADMIN_LOGIN_NAME',md5('SESS_MH_ADMIN'.$http_host));
// define('ADMIN_LOGIN_TYPE','cookie');
define('ADMIN_LOGIN_TYPE',LOGIN_TYPE);
// 세션일 땐 만료일, 도메인 등이 동작 안한다.
define('ADMIN_LOGIN_EXPIRE',60*60*24*365);
define('ADMIN_LOGIN_DOAMIN',$http_host);
define('ADMIN_LOGIN_PATH',substr(ADMIN_URI_PREFIX,0,-1).'; samesite=strict');
define('ADMIN_LOGIN_PREFIX','');
define('ADMIN_LOGIN_SECURE',false);


define('ENCRYPTION_KEY_PREFIX','MH_');
define('_FORM_DIR',APPPATH.'../_form');
define('_FILES_DIR',APPPATH.'../_files');
define('_LOGS_DIR',APPPATH.'../_logs');
define('_TMP_DIR',APPPATH.'../_tmp');
define('_TEMP_DIR',APPPATH.'../_temp');

date_default_timezone_set('Asia/Seoul');

define('MH_LOG_STORE',3); //로그 저장소, 0:로그저장안함, 1: CI로그파일,2:DB, 3:1+2

define('DATE_YMD',date('Y-m-d'));

// define('ALLOWED_IP_REGEXP','/^127.0.0.\d{1,3}$/'); //접근 가능 IP preg_match 규칙에 맞아야함. 빈값이면 전부 허용
define('ALLOWED_IP_REGEXP','');
// define('ADMIN_ALLOWED_IP_REGEXP','/^(127.0.0.9|127.0.0.7)$/'); //접근 가능 IP  preg_match 규칙에 맞아야함. 빈값이면 전부 허용. 관리자페이지 용
define('ADMIN_ALLOWED_IP_REGEXP','');

require_once(dirname(__FILE__).'/legacy.php');
