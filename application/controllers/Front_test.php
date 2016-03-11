<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Front_test extends MX_Controller {

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
	public function json_login(){
		$this->load->view('/test/json_login');
	}
	public function dec_enc_str(){
		$enc_str = $this->input->post_get('enc_str');
		$dec_arr = null;
		if(isset($enc_str)){
			$dec_arr = $this->common->dec_str($enc_str);
		}
		$data = array(
			'enc_str'=>$enc_str,
			'dec_arr'=>$dec_arr,
		);
		$this->load->view('/test/dec_enc_str',$data);
	}


}






