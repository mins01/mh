<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__).'/Bbs.php');

class Sdgn_json extends MX_Controller {

	public $b_id = 'sdgn_unit';
	public $bm_row = null;
	
	public function __construct()
	{
		//parent::__construct();
		$this->load->module('mh_util/mh_cache');
		$this->load->model('sdgn_etc_model','sdgn_etc_m');
		$this->config->set_item('layout_head_contents',
			'<link href="/mh/css/sdgn/units.css" rel="stylesheet" type="text/css" />'
		);
		$this->config->set_item('layout_disable',true);
	}
	
	public function _remap($method, $params = array())
	{
		$this->index($params);
	}
	
	// /bbs로 접근할 경우, 맨 처음은 b_id가 된다.
	public function index($param){
		exit('DontUseMethod');
		$method = isset($param[0][0])?$param[0]:'';
		
		if(!isset($method[0])){
			show_error('메소드가가 없습니다.');
		}
		
	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$b_id = $conf['menu']['mn_arg1'];
		$method = isset($param[0][0])?$param[0]:'units';
		
		if(!isset($method[0])){
			show_error('메소드가가 없습니다.');
		}
		//$this->set_base_url($base_url);
		//$this->action($b_id,$mode,$b_idx);
		
		if(!method_exists($this,$method)){
			show_error($method.' 메소드가 없습니다.');
			exit;
		}
		$this->{$method}($conf,$param);
	}
	
	public function json_encode($obj){
		return json_encode($obj);
	}
	
	//==== 유닛 목록용
	public function units($conf,$param){
	
		$this->load->model('sdgn_unit_model','sdgn_unit_m');
		$this->load->model('mh/bbs_master_model','bm_m');
		$b_id = 'sdgn_units';
		// $this->bm_row = $this->bm_m->get_bm_row($b_id);
		// if($this->bm_row['bm_open']!='1'){
			// show_error('사용 불가능한 게시판 입니다.');
		// }
		//$this->skin_path = 'mh/bbs/skin/'.$this->bm_row['bm_skin'];
		

		$this->config->set_item('layout_title','SDGN UNITS');
		
		if($this->input->get('unit_idx')){
			return $this->units_detail($conf,$param);
		}else{
			return $this->units_lists($conf,$param);
		}
		
	}
	public function units_lists($conf,$param){
		//$this->output->cache(1);
		$cache_key = __METHOD__;
		$disable_cache = IS_DEV;
		
		if ($disable_cache || ($view_data = $this->mh_cache->get($cache_key))===false)
		{
			$su_rows = $this->sdgn_unit_m->select_for_lists();
			$view_data = array(
				'su_rows'=>$su_rows,
			);
			if(!$disable_cache) $this->mh_cache->save($cache_key, $view_data,60*10);
		}
		echo $this->json_encode($view_data);
		return;
	}

	public function units_detail($conf,$param){
		$unit_idx = $this->input->get('unit_idx');
		
		$disable_cache = IS_DEV;
		$cache_key = __METHOD__.'_'.$unit_idx;
		if ($disable_cache || ($view_data = $this->mh_cache->get($cache_key))===false)
		{
			$su_row=$this->sdgn_unit_m->select_by_unit_idx($unit_idx);
			if(!isset($su_row)){
				show_error('WHAT?');
			}
			
			$units_card = $this->load->view('mh/sdgn/units_card',array('su_row'=>$su_row,'use_a'=>false),true);
			
			
			$comment_url = base_url('bbs_comment/'.$this->bm_row['b_id'].'/'.$su_row['unit_idx']);
			$view_data = array(
				'su_row'=>$su_row,
			);
			
			if(!$disable_cache) $this->mh_cache->save($cache_key, $view_data,60*10);
		}
		echo $this->json_encode($view_data);
		return;
		
	}
	
	public function vs_sdgo($conf,$param){
		$cache_key = __METHOD__;
		$disable_cache = IS_DEV;
		if ($disable_cache || ($view_data = $this->mh_cache->get($cache_key))===false)
		{
			$view_data = array(
			'sdgn_count_units'=>$this->sdgn_etc_m->count_units(),
			'sdgo_count_units'=>767,
			'sdgn_count_skills'=>$this->sdgn_etc_m->count_skills(),
			'sdgo_count_skills'=>232,
			'sdgn_count_comments'=>$this->sdgn_etc_m->count_comments(),
			'sdgo_count_comments'=>12102,
			'sdgn_count_comment_users'=>$this->sdgn_etc_m->count_comment_users(),
			'sdgo_count_comment_users'=>1068,
			
			
			);
			if(!$disable_cache) $this->mh_cache->save($cache_key, $view_data,60*10);
		}
		$this->load->view('mh/sdgn/vs_sdgo',$view_data);
	}
}






