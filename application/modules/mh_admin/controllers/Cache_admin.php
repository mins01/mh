<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cache_admin extends MX_Controller {

	public function __construct()
	{
		$this->load->library('mh_cache');
	}

	public function _remap($method, $params = array())
	{
		$this->index($params);
	}

	public function set_base_url($base_url){
		$this->base_url = $base_url;
	}
	// /bbs로 접근할 경우, 맨 처음은 b_id가 된다.
	public function index($param){
		$mode = isset($param[0][0])?$param[0]:'list';
		//$mode = $this->uri->segment(3,'list');//option

		$this->set_base_url(ADMIN_URI_PREFIX.'cache_admin');
		$this->action($mode);
	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$mode = isset($param[0][0])?$param[0]:'main';
		$this->set_base_url($base_url);
		$this->action($mode);
	}

	public function action($mode){
    $process = $this->input->post_get('process');
    if(isset($process[0])){
      $this->{'process_'.$process}();
    }else{
      $this->{'mode_'.$mode}();
    }
	}

  public function mode_main(){
    $skin = 'mh_admin/cache_admin/main';
		$cache_info = $this->mh_cache->cache_info(true);
		$cnt_cached = 0;
		$cnt_expired = 0;
		foreach ($cache_info as $r) {
			if($r['expired']){
				$cnt_expired++;
			}else{
				$cnt_cached++;
			}
		}
    $this->load->view($skin,array(
      "USE_CACHE"=>USE_CACHE,
      "USE_MH_CACHE"=>USE_MH_CACHE,
			'cnt_cached'=>$cnt_cached,
			'cnt_expired'=>$cnt_expired,
			'cache_info'=>$cache_info,
      // 'cache_info'=>$this->mh_cache->cache_detail_info(),
		));
  }
  public function process_clean(){
    $this->mh_cache->clean();
    $referer = isset($_SERVER['HTTP_REFERER'][0])?$_SERVER['HTTP_REFERER']:ADMIN_URI_PREFIX;
    header('Location: '.$referer);
    exit;
  }
	public function process_readjust(){
    $this->mh_cache->readjust();
    $referer = isset($_SERVER['HTTP_REFERER'][0])?$_SERVER['HTTP_REFERER']:ADMIN_URI_PREFIX;
    header('Location: '.$referer);
    exit;
  }


}
