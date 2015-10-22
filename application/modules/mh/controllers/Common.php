<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common extends MX_Controller {

	var $logedin = false;

	public function __construct($bbs_conf=array())
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->load->helper('cookie');
		
		$this->load->model('menu_model','menu_m');
		$this->menu_m->load_db();
		$this->config->set_item('menu_rows', $this->menu_m->get_menu_rows());
		$this->config->set_item('menu_tree', $this->menu_m->get_menu_tree());

		$t = $this->get_login('m_id');

		$this->logedin = isset($t[0]);
		$this->config->set_item('layout_logedin',$this->logedin);
	}
	
	public function redirect($msg,$ret_url){
		$this->load->view('mh/redirect.php',array('msg'=>$msg,'ret_url'=>$ret_url));
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
		switch(LOGIN_TYPE){
			case 'cookie':
				$r = $this->get_login_at_cookie($key);
				break;
		}
		$r = unserialize($r);
		if(isset($key)){
			return isset($r[$key])?$r[$key]:NULL;
		}
		return $r;
	}
	public function get_login_at_cookie($key=NULL){
		return $this->input->cookie(LOGIN_NAME);
		
	}
	
}






