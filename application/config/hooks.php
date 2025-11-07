<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	http://codeigniter.com/user_guide/general/hooks.html
|
*/
if(is_cli()){
	
}else{
	// $hook['post_controller_constructor'] = array(
	// 		'class'    => 'InitIpHook',
	// 		'function' => 'setInitialIp',
	// 		'filename' => 'InitIpHook.php',
	// 		'filepath' => 'hooks',
	// 		'params'   => array()
	// );

	$hook['pre_controller'] = array(
		'class'    => 'Mh_hook',
		'function' => 'pre',
		'filename' => 'Mh_hook.php',
		'filepath' => 'hooks',
		'params'   => array()
	);
	
	$hook['post_controller'] = array(
		'class'    => 'Mh_hook',
		'function' => 'post',
		'filename' => 'Mh_hook.php',
		'filepath' => 'hooks',
		'params'   => array()
	);	
}
