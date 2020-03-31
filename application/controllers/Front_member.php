<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_member extends MX_Controller {

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
		$this->load->module('mh/layout');
		$this->load->module('mh/common');
		$this->load->module('mh/member');
	}
	public function login(){
		$this->member->login();
	}
	public function json_login(){
		$this->member->json_login_process();
	}
	public function user_info(){
		if(!$this->common->logedin){
			$this->common->redirect('회원가입으로 변경합니다.',SITE_URI_PREFIX.'member/join');
			return;
		}
		$this->member->modify();
	}
	public function user_pass(){
		$this->member->password();
	}
	public function logout(){
		$this->member->logout();
	}
	public function join(){
		if($this->common->logedin){
			$this->common->redirect('회원정보 수정으로 변경합니다.',SITE_URI_PREFIX.'member/user_info');
			return;
		}
		$this->member->join();
	}

	public function search_id(){
		$this->member->search_id();
	}
	public function search_pw(){
		$this->member->search_pw();
	}
	public function reset_pw(){
		$this->member->reset_pw();
	}


}
