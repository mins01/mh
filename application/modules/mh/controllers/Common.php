<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends MX_Controller {

	public $logedin = false;
	private $m_row = array();

	public function __construct($bbs_conf=array())
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->load->helper('cookie');
		
		$this->load->model('menu_model','menu_m');
		$this->menu_m->load_db();
		$this->config->set_item('menu_rows', $this->menu_m->get_menu_rows());
		$this->config->set_item('menu_tree', $this->menu_m->get_menu_tree());

		$this->init_login();
		$t = $this->get_login('m_id');

		$this->logedin = isset($t[0]);
		$this->config->set_item('layout_logedin',$this->logedin);
	}
	
	public function redirect($msg,$ret_url){
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',$msg);
		$this->load->view('mh/redirect.php',array('msg'=>$msg,'ret_url'=>$ret_url));
	}
	public function required_login(){
		if(!$this->logedin){
			$ret_url = SITE_URI_PREFIX.'login';
			$this->redirect('로그인이 필요합니다.',$ret_url);
			return false;
		}
		return true;
	}
	
	private function init_login(){
		switch(LOGIN_TYPE){
			case 'cookie':
				$v = $this->input->cookie(LOGIN_NAME);
				break;
		}

		if(isset($v)){
			$this->m_row = unserialize($v);
		}
	}
	public function set_login($m_row){
		unset($m_row['m_pass']);
		switch(LOGIN_TYPE){
			case 'cookie':
				$this->set_login_at_cookie(serialize($m_row));
				break;
		}
	}
	public function set_logout(){
		switch(LOGIN_TYPE){
			case 'cookie':
				$this->set_login_at_cookie('',-100);
				break;
		}
	}
	
	//-- 로그인 쿠키 설정
	public function set_login_at_cookie($str,$expire=null){
		if(!isset($expire)){
			$expire = LOGIN_EXPIRE;
		}
		$data = array(
				'name'   => LOGIN_NAME,
				'value'  => $str,
				'expire' => $expire,
				'domain' => LOGIN_DOAMIN,
				'path'   => LOGIN_PATH,
				'prefix' => LOGIN_PREFIX,
				'secure' => LOGIN_SECURE
		);
		$this->input->set_cookie($data);
	}
	public function get_login($key=NULL){
		if(isset($key)){
			return isset($this->m_row[$key])?$this->m_row[$key]:null;
		}
		return $this->m_row;
	}

	
}






