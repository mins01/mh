<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

require_once(dirname(__FILE__).'/Bbs.php');

class Sdgn_unit extends MX_Controller {

	public $b_id = 'sdgn_unit';
	
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
		$this->config->set_item('layout_head_contents',
			'<link href="/mh/css/sdgn/units.css" rel="stylesheet" type="text/css" />'
		);
		$this->config->set_item('layout_title','SDGN UNITS');
		
		$this->load->model('sdgn_unit_model','sdgn_unit_m');
		$su_rows = 
		//print_r($su_rows);
		
		$this->load->view('mh/sdgn/units',array(
		'conf' =>$conf,
		'param' =>$param,
		'su_rows' =>$this->sdgn_unit_m->select(),
		'su_cnt' =>$this->sdgn_unit_m->count(),
		
		
		));
	}
}






