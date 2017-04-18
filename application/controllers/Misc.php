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
		$this->load->library('Mproxy');
		$res = $this->mproxy->get($url);

		// print_r($res['body']);
		if(!isset($res['body'][0]) || stripos('html',$res['body'])!==false){
			show_error("not html contents");
		}
		$this->load->library('mh_util');
		$opgs = $this->mh_util->parseOgp($res['body']);
		// var_dump($opgs);


		//-- 웹 캐시 설정
		$this->load->library('mheader');
		$sec = 60*60*24; //하루. 더 길게해도 문제 없다.(파일 수정 기능이 없기 때문에)
		$etag = date('Hi').ceil(date('s')/$sec).substr(md5($url),0,6);

		//$msgs = array();
		if( MHeader::etag($etag)){ //etag는 사용하지 말자.
		//$msgs[] = 'etag 동작';//실제 출력되지 않는다.(304 발생이 되기 때문에)
			exit('etag 동작');
		}else if(MHeader::lastModified($sec)){
		//$msgs[] = 'lastModified 동작'; //실제 출력되지 않는다.(304 발생이 되기 때문에)
			exit('lastModified 동작');
		}
		MHeader::expires($sec);

		// $this->output->cache($sec/60);
		$this->load->view('/misc/htmlOgp',array('opgs'=>$opgs,'url'=>$url));
	}

}
