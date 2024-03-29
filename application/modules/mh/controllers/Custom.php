<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* 이 모듈속의 동작은 자동화하지 않는다. 스스로 커스터마이즈 해야함!
*/
// module v2 방식
class Custom extends MX_Controller{
	public $module_type = 2; //index_as_front 를 사용안한다.
	public function __construct()
	{

	}
	public function index($conf=array(),$param=array()){ // 기본 메소드
		if(isset($conf['menu']['mn_arg1']) && method_exists($this,$conf['menu']['mn_arg1'])){
			$this->{$conf['menu']['mn_arg1']}($conf,$param);
		}else{
			show_error('지원되지 않는 메뉴 아규먼트1 입니다.');
		}
	}

	private function aa($conf=array(),$param=array()){ // private 메소드 테스트용 (외부에서 호출 불가)
		if(isset($conf['menu']['mn_arg1']) && method_exists($this,$conf['menu']['mn_arg1'])){
			$this->{$conf['menu']['mn_arg1']}($conf,$param);
		}else{
			show_error('지원되지 않는 메뉴 아규먼트1 입니다.');
		}
	}

	private function last_bbs($conf,$param){
		// // //-- 웹 캐시 설정
		// $this->load->library('mheader');
		// $sec = 30; //캐시시간
		// $etag = date('Hi').ceil(date('s')/$sec).'last_bbs';
		//
		// //$msgs = array();
		// if( MHeader::etag($etag)){ //etag는 사용하지 말자.
		// //$msgs[] = 'etag 동작';//실제 출력되지 않는다.(304 발생이 되기 때문에)
		// 	exit('etag 동작');
		// }else if(MHeader::lastModified($sec)){
		// //$msgs[] = 'lastModified 동작'; //실제 출력되지 않는다.(304 발생이 되기 때문에)
		// 	exit('lastModified 동작');
		// }
		// MHeader::expires($sec);


		$this->load->library('mh_cache');
		$key = __FUNCTION__;
		// var_dump($this->mh_cache);
		// $this->mh_cache->use_cache = false;
		$this->load->model('mh/custom_model','custom_m');
		// $b_rowss = array();
		// $b_rowss['tech'] = $this->custom_m->get_list_rows('tech',7,50);
		// $b_rowss['mono'] = $this->custom_m->get_list_rows('mono',7,50);
		// $b_rowss['free'] = $this->custom_m->get_list_rows('free',7,50);
		// $b_rowss['calendar'] =  = $this->custom_m->get_calendar_rows('calendar',date('Y-m-d',time()-60*60*24*7),date('Y-m-d',time()+60*60*24*7));
		// $b_rowss['freegame'] =  = $this->custom_m->get_calendar_rows('freegame',date('Y-m-d',time()-60*60*24*7),date('Y-m-d',time()+60*60*24*7));
		// print_r($rows);

		$output = $this->mh_cache->get($key);
		if(!$output){

			$b_rowss = array();
			$bbs_tbl_b_ids = array(
				array('tech','tech',0,'기술','tech'),
				array('mine','calendar',1,'일정','calendar'),
				array('mine','freegame',1,'무료게임','freegame'),
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
			// $b_rowss = $this->custom_m->last_bbs_rowss($bbs_tbl_b_ids,50,7);
			foreach ($bbs_tbl_b_ids as $v) {
				if($v[2]==0){
					$b_rowss[$v[1]] = $this->custom_m->get_list_rows($v[1],7,50);
				}else if($v[2]==1){
					$b_rowss[$v[1]] = $this->custom_m->get_calendar_rows($v[1],date('Y-m-d',time()-60*60*24*7),date('Y-m-d',time()+60*60*24*7));
				}
			}
			// print_r($b_rowss);
			// exit;

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


			$bt_rowss = array();
			$bt_rowss[] = array('tech','tech',0,'기술','tech',$this->custom_m->last_bbs_tags('tech','tech',100,10));


			$output = array(
				'bbs_tbl_b_ids'=>$bbs_tbl_b_ids,
				'b_rowss'=>$b_rowss,
				'bc_tbl_b_ids'=>$bc_tbl_b_ids,
				'bc_rowss'=>$bc_rowss,
				'bt_rowss'=>$bt_rowss,
			);
			$this->mh_cache->save($key,$output,60*5);
		}
		$this->config->set_item('layout_head_contents',
		'<link href="'.html_escape(SITE_URI_ASSET_PREFIX.'css/bbs/skin/bbs_skin_default.css').'?='.REFLESH_TIME.'" rel="stylesheet"  class="mb_wysiwyg_head_css">'
		);
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','');
		$this->load->view('mh/custom/last_bbs_columns',$output);

	}



}
