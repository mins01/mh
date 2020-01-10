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

		// //-- 웹 캐시 설정
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


		if(!$url){
			show_error("required url");
		}

		$this->load->module('mh_util/mh_cache');
		$key = md5($url);
		// var_dump( $this->mh_cache);
		// exit;
		$opgs = $this->mh_cache->get($key);
		// var_dump($opgs);
		if(!$opgs){
			$this->load->library('Mproxy');
			$opts = array();
			$opts[CURLOPT_SSL_VERIFYPEER]=false;
			$opts[CURLOPT_SSL_VERIFYHOST]=false;

			//($url,$cookieRaw=null,$headers=array(), $opts = array())
			// $url='//test.com/index.php?123=312';
			// URL에 생략된 것 처리
			// $url = preg_replace('/#.*$/','',$url); //#HASH 부분 삭제 낮은 cURL에서 400 에러 난다.
			$t = parse_url($url);
			// var_dump($t);
			unset($t['fragment']); //#HASH 부분 삭제. 낮은 cURL에서 400 에러 난다.
			// exit;
			if(!isset($t['host'][0])){
				$t['host'] = isset($_SERVER['HTTP_HOST'][0])?$_SERVER['HTTP_HOST']:'';
			}
			if(!isset($t['scheme'][0])){
				if(strpos($url,'//')===0){
					$t['scheme'] = isset($_SERVER['HTTPS'][0])?'https':'http';
				}else{
					$t['scheme'] = 'http';
				}

			}
			$call_url = unparse_url($t);
			//
			//
			// var_dump($t);
			// var_dump($call_url);
			// exit;
			set_time_limit(120);// 동작 타임아웃
			$this->mproxy->conn_timeout = 60;
			$this->mproxy->exec_timeout = 60;
			$res = $this->mproxy->get($call_url,null,array(),$opts);

			// echo $url;
			// print_r($res);exit;
			if($res['errorno']!=0 ){
				show_error($res['errormsg'],$res['httpcode'],$res['errormsg']);
			}else if(!isset($res['body'][0]) || stripos('html',$res['body'])!==false){
				show_error('not html contents');
				// exit;
			}
			$content = $res['body'];
			$charset = 'utf-8';
			$matches = array();
			preg_match('/charset=([^\s\n>"\']*)/',$res['header'],$matches);
			if(isset($matches[1])){
				$charset = $matches[1];
			}else{
				$matches = array();
				preg_match('/charset=([^\s\n>]*)/',$content,$matches);
				if(isset($matches[1])){
					$charset = $matches[1];
				}
			}
			$charset = str_replace(array("'",'"','>'),'',$charset);
			$charset = strtolower($charset);
			// echo $charset;
			$this->load->library('mh_util');

			if($charset == 'ks_c_5601-1987'){
				$charset = 'uhc';
			}
			if($charset != 'utf-8'){
				// echo $charset;
				$content = @iconv($charset,'UTF-8//IGNORE',$content);
				// echo $content;
			}
			// echo $content ;
			$opgs = $this->mh_util->parseOgp($content,$url);
			// var_dump($opgs);
			if(empty($opgs)){
				$opgs['title']='not opg meta!';
				// show_error("not opg meta");
				// exit;
			}

			// $this->output->cache($sec/60);
			$this->mh_cache->save($key,$opgs);
		}else{

		}
		$this->load->view('/misc/htmlOgp',array('opgs'=>$opgs,'url'=>$url));
	}

}
