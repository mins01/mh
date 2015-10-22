<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Admin extends MX_Controller {

	public function __construct()
	{
		parent::__construct();
		// $this->load->module('www/common');
		// $this->load->model('product_model','product_m');
		// $this->load->model('tag_model','tag_m');
		// $this->load->model('bbs_model','bbs_m');

		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		//$this->load->driver('cache');
		$this->config->load('conf_front'); // 프론트 사이트 설정
		$this->config->load('conf_admin'); // 관리자 사이트 설정 (설정 덮어 씌움)
	}

	public function _remap($method, $params = array())
	{
		// if (method_exists($this, $method))
		// {
				// return call_user_func_array(array($this, $method), $params);
		// }
		// show_404();
		// show_error('x');
		$this->index();
		
	}
	public function get_menu(){
		return array(
		'module'=>'mh/page',
		'view'=>'/page/page1',
		);
	}
/**
 * 메인 페이지
 * @return null
 */
	public function index(){
		$data = array();

		$conf = $this->get_menu();
		$this->load->module($conf['module'],$conf);
		//var_dump($r);
		//var_dump($this->page);
		//$this->page->action();
	}


}






