<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__).'/Bbs.php');

class Sdgn_unit extends MX_Controller {

	public $b_id = 'sdgn_unit';
	public $bm_row = null;
	
	public function __construct()
	{
		//parent::__construct();
	

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
	
	//====
	public function units($conf,$param){
		$this->load->model('sdgn_unit_model','sdgn_unit_m');
		$this->load->model('mh/bbs_master_model','bm_m');
		$b_id = 'sdgn_units';
		$this->bm_row = $this->bm_m->get_bm_row($b_id);
		if($this->bm_row['bm_open']!='1'){
			show_error('사용 불가능한 게시판 입니다.');
		}
		$this->skin_path = 'mh/bbs/skin/'.$this->bm_row['bm_skin'];
		
		$this->config->set_item('layout_head_contents',
			'<link href="/mh/css/sdgn/units.css" rel="stylesheet" type="text/css" />'
		);
		$this->config->set_item('layout_title','SDGN UNITS');
		
		if($this->input->get('unit_idx')){
			return $this->units_detail($conf,$param);
		}else{
			return $this->units_lists($conf,$param);
		}
		
	}
	public function units_lists($conf,$param){
		//print_r($su_rows);
		$su_rows = $this->sdgn_unit_m->select_for_lists();
		$units_cards = array();
		foreach($su_rows as $su_row){
			$units_cards[] = $this->load->view('mh/sdgn/units_card',array('su_row'=>$su_row,'use_a'=>true),true);
		}
		$this->load->view('mh/sdgn/units',array(
		'conf' =>$conf,
		'param' =>$param,
		'units_cards'=>$units_cards,
		'su_cnt' =>$this->sdgn_unit_m->count(),
		
		));
	}
	public function get_head_contents($mode){
		return $this->load->view( $this->skin_path.'/head_contents',array('mode'=>$mode,'bm_row'=>$this->bm_row),true);
	}
	public function units_detail($conf,$param){
		$unit_idx = $this->input->get('unit_idx');
		$su_row=$this->sdgn_unit_m->select_by_unit_idx($unit_idx);
		if(!isset($su_row)){
			show_error('WHAT?');
		}
		$mode = 'read';
		$this->config->set_item('layout_head_contents',$this->config->item('layout_head_contents').$this->get_head_contents($mode));
		
		
		$units_card = $this->load->view('mh/sdgn/units_card',array('su_row'=>$su_row,'use_a'=>false),true);
		
		
		$comment_url = base_url('bbs_comment/'.$this->bm_row['b_id'].'/'.$su_row['unit_idx']);
		$this->load->view('mh/sdgn/units_detail',
			array(
				'su_row'=>$su_row,
				'units_card'=>$units_card,
				'html_comment'=>($this->bm_row['bm_use_comment']=='1')?$this->load->view($this->skin_path.'/comment',array('comment_url'=>$comment_url,'bm_row'=>$this->bm_row),true):'',
			)
		);
	}
}





