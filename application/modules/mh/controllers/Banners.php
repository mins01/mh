<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banners extends MX_Controller {

	public function __construct($bbs_conf=array())
	{
		$this->load->model('mh/banners_model','banners_m');
		$this->config->set_item('layout_disable',true);
		//$this->action();
	}
	public function index(){
		// $b_id = $this->uri->segment(2);
		// $b_idx = $this->uri->segment(3);
		$this->action($b_id,$b_idx);
	}
  public function json(){
    $rows = $this->banners_m->select_for_using(array());
		$json = array();
		$json['info']=array(
			'date'=>date('Y-m-d H:i:s'),
		);
		$json['banners']=$rows;
		$this->print_json($json);
		exit;
  }
	public function js(){
    $rows = $this->banners_m->select_for_using(array());
		$json = array();
		$json['info']=array(
			'date'=>date('Y-m-d H:i:s'),
		);
		$json['banners']=$rows;
		$this->print_js($json);
		exit;
  }
	private function print_json($json){
		header('Content-Type: application/json');
		$t = 60*10;
		header("Expires: ".gmdate("D, d M Y H:i:s", time()+$t)." GMT");
		header("Cache-Control: public, max-age = {$t}");
		$this->config->set_item('layout_disable',true);
		$options = 0;
		if(if(defined('JSON_UNESCAPED_UNICODE'))){
			if(defined('JSON_UNESCAPED_UNICODE')) $options += JSON_UNESCAPED_UNICODE;
			if(defined('JSON_PRETTY_PRINT')) $options += JSON_PRETTY_PRINT;
			echo json_encode($json,$options);
		}else{
			echo json_encode($json);
		}

		return;
	}
	private function print_js($json){
		header('Content-Type: application/javascript');
		$t = 60*10;
		header("Expires: ".gmdate("D, d M Y H:i:s", time()+$t)." GMT");
		header("Cache-Control: public, max-age = {$t}");
		$this->config->set_item('layout_disable',true);
		$options = 0;

		echo 'var banners_data = ';
		if(if(defined('JSON_UNESCAPED_UNICODE'))){
			if(defined('JSON_UNESCAPED_UNICODE')) $options += JSON_UNESCAPED_UNICODE;
			if(defined('JSON_PRETTY_PRINT')) $options += JSON_PRETTY_PRINT;
			echo json_encode($json,$options);
		}else{
			echo json_encode($json);
		}

		return;
	}

}
