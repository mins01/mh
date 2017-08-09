<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'welcome';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

//$route['default_controller'] = 'front/main/index';
$route['default_controller'] = 'front/index';
//$route[ADMIN_URI_PREFIX] = 'mh/admin/index';
//$route[ADMIN_URI_PREFIX.'/(.*)'] = 'mh/admin/index';
//$route['(.*)/(:any)'] = 'front/$2';
$route['member'] = 'front_member/index'; //바꾸지 마시오!
$route['member/(.*)'] = 'front_member/$1'; //바꾸지 마시오!

$route['_test/(.*)'] = 'front_test/$1'; //개발 테스트용. 필요 없으면 주석처리.

$route['_admin'] = 'admin/index';
$route['_admin/bbs_admin'] = 'mh_admin/bbs_admin/index';
$route['_admin/bbs_admin/(.*)'] = 'mh_admin/bbs_admin/index/$1';
$route['_admin/(.*)'] = 'admin/$1';
$route['bbs'] = 'mh/bbs/index';
$route['bbs/(.*)'] = 'mh/bbs/index/$1';
$route['bbs_comment/(.*)'] = 'mh/bbs_comment/index';
$route['misc/(.*)'] = 'misc/$1'; //기타



$route['sdgn'] = 'front_sdgn/index';
$route['sdgn/(.*)'] = 'front_sdgn/$1';

$route['crlud_test'] = 'mh_util/crlud_test';
$route['crlud'] = 'mh_util/crlud';



$route['(.*)'] = 'front/$1';






//EX
// $route['default_controller'] = 'www/main/m_index';

// $route['dtl/(:any)'] = 'www/product/m_detail';
// $route['join'] = 'www/member/m_join'; //회원가입
// $route[''] = 'www/main/m_index';
// $route['pin/event'] = 'www/pin/m_event'; //이벤트
// $route['pin/notice'] = 'www/pin/m_notice'; //공지사항
// $route['pin/faq'] = 'www/pin/m_faq'; //faq
// $route['ria/(:any)'] = 'www/ria/$1'; //json

