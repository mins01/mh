<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__).'/Bbs.php');

class Sdgn extends MX_Controller {

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
	}
	
	public function _remap($method, $params = array())
	{
		
		$this->index($params);
	}
	
	// /bbs로 접근할 경우, 맨 처음은 b_id가 된다.
	public function index($param){
		exit('DontUseMethod');
		$b_id = isset($param[0][0])?$param[0]:'';
		$mode = isset($param[1][0])?$param[1]:'list';
		$b_idx = isset($param[2][0])?$param[2]:'';
		if(!isset($b_id[0])){
			show_error('게시판 아이디가 없습니다.');
		}
		$mode = $this->uri->segment(3,'list');//option
		$b_idx = $this->uri->segment(4);//option
		//$this->set_base_url(base_url('bbs/'.$b_id));
		//$this->action($b_id,$mode,$b_idx);
	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$b_id = $conf['menu']['mn_arg1'];
		$mode = isset($param[0][0])?$param[0]:'list';
		$b_idx = isset($param[1][0])?$param[1]:'list';
		if(!isset($b_id[0])){
			show_error('게시판 아이디가 없습니다.');
		}
		//$this->set_base_url($base_url);
		//$this->action($b_id,$mode,$b_idx);
		
		if(!method_exists($this,$conf['menu']['mn_arg1'])){
			show_error($conf['menu']['mn_arg1'].' 메소드가 없습니다.');
			exit;
		}
		$this->{$conf['menu']['mn_arg1']}($conf,$param);
	}
	//==== 메인 화면용
	public function main($conf,$param){
		
		
		$cache_key = __METHOD__;
		$disable_cache = IS_DEV;
		
		if ($disable_cache || ($view_data = $this->mh_cache->get($cache_key))===false)
		{
			
			//최대 평가 코멘트 수
			//$bc_rows = $this->sdgn_etc_m->select_comment_for_main();
			//유닛 무기 수정 TOP 10
			$sw_rows = $this->sdgn_etc_m->select_for_last_update_weapon();
			//인기 기체
			$su_rows = $this->sdgn_etc_m->select_units_for_main();
			// 최근 코멘트
			$last_bc_rows = $this->sdgn_etc_m->select_last_comment_for_main();
			// 일정
			$tm = time();
			$plan_dt_st = date('Y-m-d',$tm-(60*60*24*7));
			$plan_dt_ed = date('Y-m-d',$tm+(60*60*24*7));
			$plan_b_rows = $this->sdgn_etc_m->select_for_plan($plan_dt_st,$plan_dt_ed); 

			$units_cards = array();
			foreach($su_rows as $su_row){
				$units_cards[] = $this->load->view('mh/sdgn/units_card',array('su_row'=>$su_row,'use_a'=>true),true);
			}
			
				
			$view_data = array(
				'conf' =>$conf,
				'param' =>$param,
				// 'bc_rows'=>$bc_rows,
				'sw_rows'=>$sw_rows,
				'su_rows'=>$su_rows,
				'units_cards'=>$units_cards,
				'last_bc_rows'=>$last_bc_rows,
				'plan_b_rows'=>$plan_b_rows,
				'plan_dt_st'=>$plan_dt_st,
				'plan_dt_ed'=>$plan_dt_ed,
			);
			if(!$disable_cache) $this->mh_cache->save($cache_key, $view_data,60*10);
		}
		
		$this->load->view('mh/sdgn/main',$view_data);
	}
	
	
	//==== 유닛 목록용
	public function units($conf,$param){
		$this->load->model('sdgn_unit_model','sdgn_unit_m');
		$this->load->model('mh/bbs_master_model','bm_m');
		$b_id = 'sdgn_units';
		$this->bm_row = $this->bm_m->get_bm_row($b_id);
		if($this->bm_row['bm_open']!='1'){
			show_error('사용 불가능한 게시판 입니다.');
		}
		$this->skin_path = 'mh/bbs/skin/'.$this->bm_row['bm_skin'];
		

		$this->config->set_item('layout_title','SDGN UNITS');
		
		if($this->input->get('unit_idx')){
			return $this->units_detail($conf,$param);
		}else{
			return $this->units_lists($conf,$param);
		}
		
	}
	public function units_lists($conf,$param){
		// $get = $this->input->get();
		$sh = array(
			'unit_name'=>$this->input->get('unit_name'),
			'unit_ranks'=>$this->input->get('unit_ranks'),
			'unit_properties_nums'=>$this->input->get('unit_properties_nums'),
			'unit_is_weapon_change'=>$this->input->get('unit_is_weapon_change'),
			'unit_is_transform'=>$this->input->get('unit_is_transform'),
			
		);
		if(!$sh['unit_ranks']) $sh['unit_ranks'] = array();
		if(!$sh['unit_properties_nums']) $sh['unit_properties_nums'] = array();
		
		$cache_key = __METHOD__.md5(serialize($sh));
		$disable_cache = IS_DEV;
		
		if ($disable_cache || ($view_data = $this->mh_cache->get($cache_key))===false)
		{
			$su_rows = $this->sdgn_unit_m->select_for_lists($sh);
			$units_cards = array();
			foreach($su_rows as $su_row){
				$units_cards[] = $this->load->view('mh/sdgn/units_card',array('su_row'=>$su_row,'use_a'=>true),true);
			}
			$view_data = array(
			'conf' =>$conf,
			'param' =>$param,
			'units_cards'=>$units_cards,
			'su_cnt_all' =>$this->sdgn_unit_m->count(),
			'su_cnt'	=> $this->sdgn_unit_m->count_for_lists($sh),
			'sh'=>$sh,
			
			);
			if(!$disable_cache) $this->mh_cache->save($cache_key, $view_data,60*10);
		}
		$this->load->view('mh/sdgn/units',$view_data);
	}
	public function get_head_contents($mode){
		return $this->load->view( $this->skin_path.'/head_contents',array('mode'=>$mode,'bm_row'=>$this->bm_row),true);
	}
	public function units_detail($conf,$param){
		$this->load->model('sdgn_weapon_model','sdgn_weapon_m');
		
		
		$unit_idx = $this->input->get('unit_idx');
		
		$mode = 'read';
		$this->config->set_item('layout_head_contents',$this->config->item('layout_head_contents').$this->get_head_contents($mode));
		
		$disable_cache = IS_DEV;
		$cache_key = 'Sdgn::units_detail'.'_'.$unit_idx;
		if ($disable_cache || ($view_data = $this->mh_cache->get($cache_key))===false)
		{
			$su_row=$this->sdgn_unit_m->select_by_unit_idx($unit_idx);
			if(!isset($su_row)){
				show_error('WHAT?');
			}
			$sw_rows = $this->sdgn_weapon_m->select_weapons_by_unit_idx($su_row['unit_idx']);
			foreach($sw_rows as & $sw_row){
				$sw_row['card'] = $this->load->view('mh/sdgn/weapon_card',array('sw_row'=>$sw_row),1);
			}
			unset($sw_row);			
			$sw_rows = $this->sdgn_weapon_m->select_assoc_weapons_by_rows($sw_rows);

			$units_card = $this->load->view('mh/sdgn/units_card',array('su_row'=>$su_row,'use_a'=>false),true);
			
			
			$comment_url = base_url('bbs_comment/'.$this->bm_row['b_id'].'/'.$su_row['unit_idx']);
			$view_data = array(
				'su_row'=>$su_row,
				'sw_rows'=>$sw_rows,
				'units_card'=>$units_card,
				'html_comment'=>($this->bm_row['bm_use_comment']=='1')?$this->load->view($this->skin_path.'/comment',array('comment_url'=>$comment_url,'bm_row'=>$this->bm_row),true):'',
			);
			
			if(!$disable_cache) $this->mh_cache->save($cache_key, $view_data,60*10);
		}
		$view_data['logedin'] = $this->common->logedin;
		$view_data['is_admin'] = $this->common->is_admin;
		
		$this->load->view('mh/sdgn/units_detail',$view_data);
		
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
	
	public function last_comments($conf,$param){
		$cache_key = __METHOD__;
		$disable_cache = IS_DEV;
		
		if ($disable_cache || ($view_data = $this->mh_cache->get($cache_key))===false)
		{
			
			$bc_rows = $this->sdgn_etc_m->select_for_last_comments(20);
			$units_cards = array();
			$view_data = array(
			'conf' =>$conf,
			'param' =>$param,
			'bc_rows'=>$bc_rows,
			);
			if(!$disable_cache) $this->mh_cache->save($cache_key, $view_data,60*10);
		}
		$this->load->view('mh/sdgn/last_comments',$view_data);
	}
}






