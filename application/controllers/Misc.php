<?
class Misc extends MX_Controller {
	
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
		$this->config->set_item('layout_disable',true);
	}
	
	public function htmlOgp(){
		//file_get_contents("https://www.youtube.com/watch?v=EIGGsZZWzZA")
		$url = $this->input->get('url');
		if(!$url){
			show_error("required url");
		}
		$content = file_get_contents($url);
		if(!isset($content[0]) || stripos('html',$content)!==false){
			show_error("not html contents");
		}
		$this->load->library('mh_util');
		$opgs = $this->mh_util->parseOgp($content);
		$this->load->view('/misc/htmlOgp',array('opgs'=>$opgs,'url'=>$url));
	}

}