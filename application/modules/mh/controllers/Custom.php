<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 이 모듈속의 동작은 자동화하지 않는다. 스스로 커스터마이즈 해야함!
*/
class Custom extends MX_Controller {
	
	public function __construct($conf=array())
	{

	}

		// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$view = $conf['menu']['mn_arg1'];
		$this->action($conf,$param);
	}
	
	public function action($conf,$param){
		
		if(isset($conf['menu']['mn_arg1']) && method_exists($this,$conf['menu']['mn_arg1'])){
			$this->{$conf['menu']['mn_arg1']}($conf,$param);
		}else{
			show_error('지원되지 않는 메뉴 아규먼트1 입니다.');
		}
	}
	
	public function last_bbs($conf,$param){
		$this->load->model('mh/custom_model','custom_m');
		
		$bbs_tbl_b_ids = array(
			array('tech','tech',0,'기술','tech'),
			array('mine','calendar',1,'일정','calendar'),
			array('mine','mono',0,'잡담','mono'),
			array('test','free',0,'자유','free'),
			// array('mine','guest',0,'방명록','guest'),
			//array('mine','gallery','갤러리'),
			//array('test','test','테스트'),
			//array('mine','diff','틀린그림찾기'),
		);
		// $b_rowss = array_merge(
			// $this->custom_m->last_bbs_rowss($bbs_tbl_b_ids,5,30),
			//$this->custom_m->last_bbs_rowss($calendar_tbl_b_ids,5,30)
			// );
		$b_rowss = $this->custom_m->last_bbs_rowss($bbs_tbl_b_ids,50,30);
		
		$this->config->set_item('layout_head_contents',
		'<link href="'.html_escape(base_url('css/bbs/skin/bbs_skin_default.css')).'" rel="stylesheet"  class="mb_wysiwyg_head_css">'
		);
		
		//
		$bc_tbl_b_ids = array(
			//array('tech','tech',0,'기술','tech'),
			//array('mine','calendar',1,'일정','calendar'),
			array('mine','mono',0,'잡담','mono'),
			array('test','free',0,'자유','free'),
			// array('mine','guest',0,'방명록','guest'),
			//array('mine','gallery','갤러리'),
			//array('test','test','테스트'),
			//array('mine','diff','틀린그림찾기'),
		);
		$bc_rowss = $this->custom_m->last_bbs_comment_rowss($bc_tbl_b_ids,50,30);
		
		
		
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','');
		$this->load->view('mh/custom/last_bbs',array(
			'bbs_tbl_b_ids'=>$bbs_tbl_b_ids,
			'b_rowss'=>$b_rowss,
			'bc_tbl_b_ids'=>$bc_tbl_b_ids,
			'bc_rowss'=>$bc_rowss,
			));
	}
	


}






