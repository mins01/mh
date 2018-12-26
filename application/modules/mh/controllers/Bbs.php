<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bbs extends MX_Controller {
	private $bbs_conf = array();
	private $bm_row = array();
	private $m_row = array();
	private $skin_path = '';
	private $base_url = '';
	private $logedin = null;
	private $tail_qs = '';// 명령용 URL뒤에 붙는 쿼리 스트링 (간략화 처리한다.)
	public function __construct()
	{
				$this->load->helper('form');

		$this->load->model('mh/bbs_master_model','bm_m');
		$this->load->model('mh/bbs_model','bbs_m');
		$this->load->model('mh/bbs_file_model','bf_m');
		$this->load->model('mh/bbs_tag_model','bt_m');

		$this->load->module('mh/layout');
		$this->load->module('mh/common');

		$this->m_row = $this->common->get_login();
		$this->logedin = & $this->common->logedin;
		$this->config->load('bbs');
		$this->bbs_conf = $this->config->item('bbs');

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
		$b_id = isset($param[0][0])?$param[0]:'';
		$mode = isset($param[1][0])?$param[1]:'list';
		$b_idx = isset($param[2][0])?$param[2]:'';
		if(!isset($b_id[0])){
			show_error('게시판 아이디가 없습니다.',400,'Bad Request');
		}
		$mode = $this->uri->segment(3,'list');//option
		$b_idx = $this->uri->segment(4);//option
		$this->set_base_url(base_url('bbs/'.$b_id));

		$this->config->set_item('layout_og_title', $this->config->item('layout_og_title')." : {$b_id}");

		$this->action($b_id,$mode,$b_idx);
	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$b_id = $conf['menu']['mn_arg1'];
		$mode = isset($param[0][0])?$param[0]:'list';
		$b_idx = isset($param[1][0])?$param[1]:'list';
		if(!isset($b_id[0])){
			show_error('게시판 아이디가 없습니다.',400,'Bad Request');
		}
		$this->set_base_url($base_url);
		$this->action($b_id,$mode,$b_idx);
	}

	//URL 뒤에 붙을 쿼리 스트링 부분 처리. ?도 처리함
	public function tail_querystring_from_get($get){
		$t = $this->querystring_from_get($get);
		return (isset($t[0])?'?':'').$t;
	}
	//URL 쿼리 스트링 간략화용 간략화용
	public function querystring_from_get($get){
		$get2 = $get;
		if(isset($get2['page']['0']) && $get2['page']=='1'){unset($get2['page']);} //URL 간략화용
		if(!isset($get2['ct']['0'])){unset($get2['ct']);unset($get['ct']);} 
		if(!isset($get2['q']['0'])){unset($get2['tq']);unset($get2['q']);unset($get['tq']);unset($get['q']);} //URL 간략화용
		if(strlen(implode('',array_values($get2)))===0){
			return '';
		}else{
			return http_build_query($get);
		}
	}
	public function action($b_id,$mode,$b_idx){
		//-- 게시판 마스터 정보 가져오기
		if(!isset($b_id)){
			show_error('게시판 정보가 잘못되었습니다.',400,'Bad Request');
		}
		$this->bm_row = $this->bm_m->get_bm_row($b_id);
		if($this->bm_row['bm_open']!='1'){
			show_error('사용 불가능한 게시판 입니다.',404,'File not found');
		}
		//print_r($conf['bm_row']);
		$this->bbs_m->set_bm_row($this->bm_row); //여기서 모델에 사용할 게시판 아이디가 고정됨
		$this->bf_m->set_bm_row($this->bm_row);
		$this->bt_m->set_bm_row($this->bm_row);
		$this->skin_path = 'mh/bbs/skin/'.$this->bm_row['bm_skin'];

		$this->bbs_conf['page'] = (int)$this->input->get('page',1);
		if(!is_int($this->bbs_conf['page']) || $this->bbs_conf['page'] <= 0){
			$this->bbs_conf['page'] = 1;
		}
		if(!isset($mode)){
			$mode = 'list';
		}


		if(!method_exists($this,'mode_'.$mode)){
			show_error('잘못된 모드입니다.',400,'Bad Request');
		}
		$get = $this->input->get();
		
		$this->tail_qs = $this->tail_querystring_from_get($get); //여기서 한번만 한다!
		
		$this->bbs_conf['base_url'] = $this->base_url;
		
		$this->bbs_conf['list_url'] = $this->base_url . "/list".$this->tail_qs;
		$this->bbs_conf['write_url'] = $this->base_url . "/write".$this->tail_qs;
		
		$get2 = array();
		$get2['lm'] = 'rss';
		$this->bbs_conf['rss_url'] = $this->base_url . "/list?".http_build_query($get2);

		$this->bbs_conf['mode'] = $mode;


		$this->{'mode_'.$mode}($b_idx);


	}

	private function pagination($get,$total_rows){
		$max_page = ceil($total_rows/$this->bm_row['bm_page_limit']);
		$uri = $this->base_url . "/list";
		return generate_paging($get,$max_page,$uri);
	}

	private function get_permission_lists($m_idx=''){

		$is_mine = !empty($m_idx) && $m_idx == $this->common->get_login('m_idx');
		$m_level = (int)$this->common->get_login('m_level');
		$is_guest_b_row = !isset($m_idx[0]);
		$is_admin = $this->bm_row['bm_lv_admin']<=$m_level;

		if(!isset($m_level)) $m_level = 0;
		return array(
			'list'=>$this->bm_row['bm_lv_list']<=$m_level,
			'read'=>$this->bm_row['bm_lv_read']<=$m_level,
			'write'=>$this->bm_row['bm_lv_write']<=$m_level,
			'edit'=>$this->bm_row['bm_lv_edit']<=$m_level &&($is_guest_b_row || $is_mine ||$is_admin),
			'set_represent'=>$this->bm_row['bm_lv_edit']<=$m_level &&($is_guest_b_row || $is_mine),
			'answer'=>$this->bm_row['bm_lv_answer']<=$m_level,
			'down'=>$this->bm_row['bm_lv_down']<=$m_level,
			'delete'=>$this->bm_row['bm_lv_delete']<=$m_level &&($is_guest_b_row || $is_mine||$is_admin),
			'admin'=>$is_admin,
			'mine'=>$is_mine,
		);
	}
	private function extends_b_row(& $b_row,$get){
		unset($get['b_idx']);	
		$b_row['read_url'] = $this->base_url . '/read/'.$b_row['b_idx'].$this->tail_qs;
		$b_row['answer_url'] = $this->base_url . '/answer/'.$b_row['b_idx'].$this->tail_qs;
		$b_row['edit_url'] = $this->base_url . '/edit/'.$b_row['b_idx'].$this->tail_qs;
		$b_row['delete_url'] = $this->base_url . '/delete/'.$b_row['b_idx']	.$this->tail_qs;
		$b_row['write_url'] = $this->base_url . '/write'.$this->tail_qs; //사용안됨
		

		if(!empty($b_row['bf_idx'])){
			$b_row['thumbnail_url'] = $this->base_url . '/thumbnail/'.urlencode($b_row['b_idx']).'?bf_idx='.urlencode($b_row['bf_idx']).'&inline=1'; //브라우저에서 보인다면 보여준다.
		}

		if(isset($b_row['b_insert_date'][0]) && time()-strtotime($b_row['b_insert_date'])<$this->bm_row['bm_new']){
			$b_row['is_new'] = true;
		}else{
			$b_row['is_new'] = false;
		}

		

		
	}
	private function extends_b_rows(&$b_rows,$get){
		foreach($b_rows as & $r){
			$this->extends_b_row($r,$get);
		}
	}
	private function extends_bf_row(& $bf_row,$b_row){

		$bf_row['download_url'] = $this->base_url . '/download/'.urlencode($b_row['b_idx']).'?bf_idx='.urlencode($bf_row['bf_idx']); //강제로 다운로드 시킨다.
		$bf_row['view_url'] = $bf_row['download_url'].'&inline=1'; //브라우저에서 보인다면 보여준다.
		if($bf_row['is_external']){
			$bf_row['thumbnail_url'] = $bf_row['bf_save'];
			switch($bf_row['bf_type']){
				case 'external/image':$bf_row['bf_name']='외부 이미지';break;
				default:$bf_row['bf_name']='외부 링크';break;
			}
		}else{
			$bf_row['thumbnail_url'] = $this->base_url . '/thumbnail/'.urlencode($b_row['b_idx']).'?bf_idx='.urlencode($bf_row['bf_idx']).'&inline=1'; //브라우저에서 보인다면 보여준다.
		}
	}
	private function extends_bf_rows(&$bf_rows,$b_row){
		foreach($bf_rows as & $r){
			$this->extends_bf_row($r,$b_row);
		}
	}

	private function get_bf_rows_by_b_row(&$b_row){
		$bf_rows = $this->bf_m->select_for_list($b_row['b_idx']);
		$this->extends_bf_rows($bf_rows,$b_row);
		return $bf_rows;
	}

	private function get_bf_row_by_b_row($b_row){
		$bf_rows = $this->bf_m->select_for_list($b_row['b_idx']);
		$this->extends_bf_rows($bf_rows,$b_row);
		return $bf_rows;
	}

	public function get_time_st_ed_by_date($v_date){
		if(!$v_date || strtotime($v_date)===false){
			$v_date = date('Y-m-d');
		}
		$v_time = strtotime($v_date);
		$v_Y = date('Y',$v_time);
		$v_m = date('m',$v_time);
		$v_time_01 = mktime(0,0,0,$v_m,1,$v_Y);
		$v_time_01_w = date('w',$v_time_01);

		$v_time_st = $v_time_01-86400*$v_time_01_w;
		$v_date_st = date('Y-m-d',$v_time_st);

		$v_time_02 = mktime(0,0,-1,$v_m+1,1,$v_Y);
		$v_time_02_w = date('w',$v_time_02);

		$v_time_ed = $v_time_02+86400*(6-$v_time_02_w);
		$v_date_ed = date('Y-m-d',$v_time_ed);
		return array($v_date_st,$v_date_ed,$v_time_st,$v_time_ed);
	}
	public function mode_list($b_idx=null,$with_read=false){
		$get = $this->input->get();
		$lm = $this->input->get('lm');

		if($lm=='rss' && $with_read){
			$lm = null;
		}

		if(!isset($lm)){
			$lm = $this->bm_row['bm_list_def'];
		}



		if($lm=='calendar'){
			return $this->mode_list_for_calendar($b_idx,$with_read);
		}else if($lm=='list'){
			return $this->mode_list_for_default($b_idx,$with_read);
		}else if($lm=='rss'){
			return $this->mode_list_for_rss();
		}else{
			return $this->mode_list_for_default($b_idx,$with_read);
		}
	}
	public function mode_list_for_calendar($b_idx=null,$with_read=false){
		$permission = $this->get_permission_lists();
		if(!$permission['list']){
			if($with_read){
				return;
			}else{
				show_error('권한이 없습니다.',403,'Permission denied');
			}
		}

		$get = $this->input->get();
		$dt = $this->input->get('dt');
		if(!isset($get['tq'])){ $get['tq'] = null; }
		if(!isset($get['q'])){ $get['q'] = null; }
		if(!isset($get['ct'])){ $get['ct'] = null; }
		if(!isset($get['dt'])){ $get['dt'] = date('Y-m-01'); }
		$dt = $get['dt'];
		//$get['page']=$this->bbs_conf['page'];
		list($date_st,$date_ed,$time_st,$time_ed) = $this->get_time_st_ed_by_date($get['dt']);
		$v_get = array_merge(
				$get,array('date_st'=>$date_st,'date_ed'=>$date_ed)
				);
		$b_rows = $this->bbs_m->select_for_calendar($v_get);
		// echo $this->db->last_query();
		$b_rowss = $this->bbs_m->exnteds_b_rows_for_calendar($b_rows,$date_st,$date_ed);

		$get2 = $this->input->get();
		$this->extends_b_rows($b_rows,$get2);
		$b_n_rows = $this->bbs_m->select_for_notice_list($get);
		$this->extends_b_rows($b_n_rows,$get2);
		$count = $this->bbs_m->count_for_calendar($v_get);

		$v_t = strtotime($dt);
		$v_Y = date('Y',$v_t);
		$v_m = date('m',$v_t);
		$v_get2 = array_merge(
					$get,array(
					'date_st'=>date('Y-m-01',mktime(0,0,0,$v_m-6,1,$v_Y)),
					'date_ed'=>date('Y-m-d',mktime(-1,0,0,$v_m+7,1,$v_Y)),
					)
				);

		$count_rowss = $this->bbs_m->count_per_month_for_calendar($v_get2);
		//$start_num = $this->bbs_m->get_start_num($count,$get);

		$tmp = $this->input->get();
		$tmp['dt'] ='dt';
		$def_url = $this->base_url . "/list?".str_replace('dt=dt','dt={{dt}}',http_build_query($tmp));
		$pagination_dt = $this->load->view($this->skin_path.'/pagination_dt',array(
		'dt' =>$get['dt'],
		'get'=>$get,
		'def_url'=>$def_url,
		'count_rowss'=>$count_rowss,
		),true);
		if(!$with_read){
			$this->config->set_item('layout_head_contents',$this->get_head_contents('list'));
			$this->config->set_item('layout_hide',false);
			$this->config->set_item('layout_title','calendar : '.$this->bm_row['bm_title']);
			$this->config->set_item('layout_og_title', $this->config->item('layout_og_title')." : 달력 : {$v_Y}년 {$v_m}월");
			$this->config->set_item('layout_og_description', "달력 : {$v_Y}년 {$v_m}월");
		}
		$this->load->view($this->skin_path.'/calendar',array(
		'b_rows' => $b_rows,
		'b_rowss'=>$b_rowss,
		'b_n_rows'=>$b_n_rows,
		'bm_row' => $this->bm_row,
		'count' => $count,
		//'max_page' => ceil($count/$this->bm_row['bm_page_limit']),
		//'start_num' => $start_num,
		'get'=>$get,
		'pagination_dt' => $pagination_dt,
		'bbs_conf'=>$this->bbs_conf,
		'b_idx'=>$b_idx,
		'permission'=>$permission,
		'date_st'=>$date_st,
		'date_ed'=>$date_ed,
		'time_st'=>$time_st,
		'time_ed'=>$time_ed,
		'base_url'=>$this->base_url,

		));

	}
	public function _mode_list($b_idx=null,$with_read=false,$opt = array()){

		$permission = $this->get_permission_lists();
		if(!$permission['list']){
			if($with_read){
				return;
			}else{
				show_error('권한이 없습니다.',403,'Permission denied');
			}
		}

		$get = $this->input->get();
		if(!isset($get['page']) || !is_numeric($get['page']) || $get['page']<1){ $get['page'] = 1; }
		if(!isset($get['tq'])){ $get['tq'] = ''; }
		if(!isset($get['q'])){ $get['q'] = ''; }
		if(!isset($get['ct'])){ $get['ct'] = ''; }
		$get['page']=$this->bbs_conf['page'];
		$order_by = isset($opt['order_by'])?$opt['order_by']:'';
		$b_rows = $this->bbs_m->select_for_list($get,$order_by);
		//echo $this->db->last_query();
		
		$get2 = $this->input->get();
		$this->extends_b_rows($b_rows,$get2);
		$b_n_rows = $this->bbs_m->select_for_notice_list($get);
		$this->extends_b_rows($b_n_rows,$get);
		$count = $this->bbs_m->count($get);
		$start_num = $this->bbs_m->get_start_num($count,$get);

		$tmp = $this->input->get();
		$tmp['page'] ='page';
		$def_url = $this->base_url . "/list?".str_replace('page=page','page={{page}}',http_build_query($tmp));
		$pagination = $this->load->view($this->skin_path.'/pagination',array(
		'max_page' => ceil($count/$this->bm_row['bm_page_limit']),
		'page'=>$this->bbs_conf['page'],
		'def_url'=>$def_url
		),true);
		if(!$with_read){
			$this->config->set_item('layout_head_contents',$this->get_head_contents('list'));
			$this->config->set_item('layout_hide',false);
			$this->config->set_item('layout_title','list : '.$this->bm_row['bm_title']);
			$this->config->set_item('layout_og_title', $this->config->item('layout_og_title')." : 목록 {$get['page']} page");
			$this->config->set_item('layout_og_description', "목록 {$get['page']} page");
		}


		$this->load->view($this->skin_path.'/list',array(
		'b_rows' => $b_rows,
		'b_n_rows'=>$b_n_rows,
		'bm_row' => $this->bm_row,
		'count' => $count,
		'max_page' => ceil($count/$this->bm_row['bm_page_limit']),
		'start_num' => $start_num,
		'get'=>$get,
		'pagination' => $pagination,
		'bbs_conf'=>$this->bbs_conf,
		'b_idx'=>$b_idx,
		'permission'=>$permission,
		));

	}
	public function mode_list_for_calendar_list($b_idx=null,$with_read=false){
		$opt = array('order_by'=>'b.b_etc_1 DESC , b.b_etc_2 DESC ');
		$this->_mode_list($b_idx,$with_read,$opt);
	}
	public function mode_list_for_default($b_idx=null,$with_read=false){
		$this->_mode_list($b_idx,$with_read);
	}
	public function mode_list_for_rss(){
		$permission = $this->get_permission_lists();
		if(!$permission['list']){
			if($with_read){
				return;
			}else{
				show_error('권한이 없습니다.',403,'Permission denied');
			}
		}

		$get = $this->input->get();
		if(!isset($get['page']) || !is_numeric($get['page']) || $get['page']<1){ $get['page'] = 1; }
		if(!isset($get['tq'])){ $get['tq'] = ''; }
		if(!isset($get['q'])){ $get['q'] = ''; }
		if(!isset($get['ct'])){ $get['ct'] = ''; }
		$get['page']=$this->bbs_conf['page'];
		$opts = array(
			'with_short_b_text'=>true,
			'order_by' => isset($opt['order_by'])?$opt['order_by']:'',
			'wheres' =>array(
				'b_secret' => '0',
			)
		);
		$b_rows = $this->bbs_m->select_for_list($get,$opts);
		$get2 = $this->input->get();
		unset($get2['lm']);
		$this->extends_b_rows($b_rows,$get2);
		$this->config->set_item('layout_disable',true);


		//-- RSS 구조 생성.
		$rss_arr = array(
			'@attributes' => array(
			    'xmlns:dc' => 'http://purl.org/dc/elements/1.1/',
			    'version' => '2.0',
			)
		);
		// print_r($this->bm_row);
		$rss_arr['channel'] = array(
			'title' => 'RSS : '.$this->bm_row['bm_title'],
			'description' =>  $this->config->item('layout_og_title')." : 목록 {$get['page']} page",
			'language' => 'ko',
			'link' => $this->base_url . '/list',
			'item' => array(),
		);
		foreach ($b_rows as $b_row) {
			$item = array(
				'title'=>$b_row['b_title'],
				'link'=>$b_row['read_url'],
				'description'=>array('@cdata'=>$b_row['short_b_text']),
				'dc:creator'=>$b_row['b_name'],
				'dc:date'=>substr($b_row['b_insert_date'],0,10),
			);
			$rss_arr['channel']['item'][]=$item;
		}
		$this->load->library('Array2XML');
		Array2XML::init('1.0', 'UTF-8');
		$xml = Array2XML::createXML('rss', $rss_arr);
		// header('Content-Type: application/xml');
		header('Content-Type: application/rss+xml; charset=utf-8');
		echo $xml->saveXML();
		return;
	}
	//비밀번호 필수 체크 : false: fail, true: OK
	private function required_password($b_row,$b_pass,$title='비밀번호 확인',$sub_title=''){
		if($this->common->get_login('is_admin')){
			return true;
		}
		if(isset($b_row['m_idx'][0]) && $b_row['m_idx'] != $this->common->get_login('m_idx') || !isset($b_row['m_idx'][0])){
			//echo $this->bbs_m->hash($b_pass).'::'. $b_row['b_pass'];
			$data = array(
			'error_msg'=>'',
			'title'=>$title,
			'sub_title'=>$sub_title,
			);
			if(!$b_pass){
				$data['error_msg'] = '';
				$this->load->view($this->skin_path.'/required_password',$data);
				return false;
			}else if( $this->bbs_m->hash($b_pass) != $b_row['b_pass']){
				$data['error_msg'] = '비밀번호를 확인해주세요.';
				$this->load->view($this->skin_path.'/required_password',$data);
				return false;
			}
		}
		return true;
	}
	public function get_head_contents($mode){
		return $this->load->view( $this->skin_path.'/head_contents',
			array(
				'mode'=>$mode,
				'bm_row'=>$this->bm_row,
				'bbs_conf'=>$this->bbs_conf,
			)
			,true);
	}
	public function mode_read($b_idx){
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다',400,'Bad Request');
		}
		$get = $this->input->get();
		if(!isset($get['page']) || !is_numeric($get['page']) || $get['page']<1){ $get['page'] = 1; }
		if(!isset($get['tq'])){ $get['tq'] = ''; }
		if(!isset($get['q'])){ $get['q'] = ''; }
		if(!isset($get['ct'])){ $get['ct'] = ''; }
		$get['page']=$this->bbs_conf['page'];
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('데이터가 없습니다',404,'File not found');
		}
		$this->extends_b_row($b_row,$get);
		$permission = $this->get_permission_lists($b_row['m_idx']);
		if(!$permission['read']){
			show_error('권한이 없습니다.',403,'Permission denied');
		}
		if($b_row['b_secret']=='1' && !$permission['mine']){
			$b_pass = $this->input->post('b_pass');
			if(!$this->required_password($b_row,$b_pass,'비밀번호 확인')){
				return;
			}
		}
		if($this->bm_row['bm_use_file']=='1'){
			$view_form_file = $this->load->view($this->skin_path.'/form_file',array(
				'mode'=>'read',
				'get'=>$get,
				'bm_row' => $this->bm_row,
				'bbs_conf'=>$this->bbs_conf,
				'permission'=>$permission,
				'bf_rows'=>$this->get_bf_rows_by_b_row($b_row),
			),true);
		}else{
			$view_form_file = '';
		}

		$this->bbs_m->hitup($b_row['b_idx'],$_SERVER['REMOTE_ADDR'],$this->common->get_login('m_idx'));

		$this->config->set_item('layout_head_contents',$this->get_head_contents('read'));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','read : '.$b_row['b_title'].' : '.$this->bm_row['bm_title']);
		$this->config->set_item('layout_og_title', $this->config->item('layout_og_title')." : {$b_row['b_title']}");
		$this->config->set_item('layout_og_description', "읽기 : {$b_row['b_title']}");

		//썸네일이 있을 경우 og 이미지를 추가한다.
		if(isset($b_row['thumbnail_url'][0])){
			$this->config->set_item('layout_og_image',$b_row['thumbnail_url']);
			$this->config->set_item('layout_og_image_width','150');
			$this->config->set_item('layout_og_image_height','150');

		}

		// $this->bt_m->pickup_tags('하루');
		// $r = $this->bt_m->pickup_tags('하루 #이틀 #삼-일 #사_일 #[오]일');
		// print_r($r);
		// exit;
		// $this->bt_m->pickup_tags($b_row['b_text']);

		$comment_url = base_url('bbs_comment/'.$this->bm_row['b_id'].'/'.$b_idx);
		$this->load->view($this->skin_path.'/read',array(
			'mode'=>'read',
			'b_row' => $b_row,
			'bm_row' => $this->bm_row,
			'get'=>$get,
			'bbs_conf'=>$this->bbs_conf,
			'html_comment'=>($this->bm_row['bm_use_comment']=='1')?$this->load->view($this->skin_path.'/comment',array('comment_url'=>$comment_url),true):'',
			'permission'=>$permission,
			'view_form_file'=>$view_form_file,
			'bt_rows'=>$this->select_tags($b_idx),
		));
		// echo $this->db->last_query();

		if($this->bm_row['bm_read_with_list']=='1'){
			$this->mode_list($b_idx,true);
		}
	}
	private function _mode_download($b_idx,$is_thumbnail=false){
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다',400,'Bad Request');
		}
		$get = $this->input->get();
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('데이터가 없습니다',404,'File not found');
		}
		$this->extends_b_row($b_row,$get);
		$permission = $this->get_permission_lists($b_row['m_idx']);
		if(!$permission['read']){
			show_error('권한이 없습니다.',403,'Permission denied');
		}
		if(!$permission['down']){
			show_error('내려받기 권한이 없습니다.',403,'Permission denied');
		}
		if($b_row['b_secret']=='1' && !$permission['mine']){
			$b_pass = $this->input->post('b_pass');
			if(!$this->required_password($b_row,$b_pass,'비밀번호 확인')){
				return;
			}
		}

		$inline = !!$this->input->post_get('inline');
		$resume = !!$this->input->post_get('resume');

		$bf_idx = $this->input->post_get('bf_idx');
		$bf_row = $this->bf_m->select_by_bf_idx($bf_idx);
		//print_r($bf_row);
		if(!isset($bf_row['bf_idx'])){
			show_error('파일 데이터가 없습니다.',404,'File not found');
		}

		while(ob_get_level()>0 && ob_end_clean()){//출력 버퍼 삭제하고 종료.(모든 버퍼를 삭제한다.
		}

		if($bf_row['is_external']){
			header('Location: '.$bf_row['bf_save'],true,302);
			exit;
		}
		if($is_thumbnail ){
			if($bf_row['is_image'] && $this->bf_m->thumbnail_by_bf_row($bf_row,$inline,$resume)){
				exit();// 여기서 강제로 종료!
			}else{
				show_error($this->bf_m->msg);
			}
			//else if($this->bf_m->download_by_bf_row($bf_row,$inline,$resume)){
			//	exit();
			//}
		}
		if($this->bf_m->download_by_bf_row($bf_row,$inline,$resume)){
			if(!$inline){//다운로드인 경우 다운로드 수 증가.
			$this->bf_m->hitup($b_row['b_idx'],$_SERVER['REMOTE_ADDR'],$this->common->get_login('m_idx'));
			}
			exit();// 여기서 강제로 종료!
		}else{
			show_error($this->bf_m->msg);
		}

	}
	public function mode_download($b_idx){
		$this->_mode_download($b_idx,false);
	}
	public function mode_thumbnail($b_idx){
		$this->_mode_download($b_idx,true);
	}
	public function mode_edit($b_idx){
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다',400,'Bad Request');
		}
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('게시물이 없습니다',404,'File not found');
		}
		$this->extends_b_row($b_row,$this->input->get());


		$this->_mode_form($b_row,'edit');
	}

	public function mode_answer($b_idx){
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다',400,'Bad Request');
		}
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('게시물이 없습니다',404,'File not found');
		}
		$b_row['m_idx'] = null;
		$b_row['b_name'] = $this->common->get_login('m_nick');
		$b_row['b_insert_date'] = null;
		$b_row['b_title'] = preg_replace('/^(RE\:)*/','',$b_row['b_title']);
		$b_row['b_title'] = 'RE:'.$b_row['b_title'];
		$b_row['b_text'] = $b_row['b_text']."\n=-----------------=\n";
		$this->extends_b_row($b_row,$this->input->get());

		$this->_mode_form($b_row,'answer');
	}
	public function mode_write(){
		$b_row = $this->bbs_m->generate_empty_b_row();
		$b_row['b_name']=$this->common->get_login('m_nick');
		$this->extends_b_row($b_row,$this->input->get());

		$this->_mode_form($b_row,'write');
	}

	private function _mode_form($b_row,$mode){
		//print_r($conf);

		$permission = $this->get_permission_lists($b_row['m_idx']);
		if(!$permission[$mode]){
			show_error('권한이 없습니다.',403,'Permission denied');
		}
		//print_r($permission);

		if($mode =='edit'){
			$b_pass = $this->input->post('b_pass');
			if(!$this->required_password($b_row,$b_pass)){
				return;
			}
		}

		if($this->input->post('process')){
			return $this->_mode_process($b_row);
		}


		$get = $this->input->get();
		$post = $this->input->post();

		$this->extends_b_row($b_row,$get);

		$this->config->set_item('layout_head_contents',$this->get_head_contents($mode));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',''.$mode.' : '.$b_row['b_title'].' : '.$this->bm_row['bm_title']);

		if(isset($post['b_pass'])){
			$b_row['b_pass'] = $post['b_pass'];
		}else{
			$b_row['b_pass'] = '';
		}
		if($mode =='write' || $mode =='answer'){
			$b_row['b_pass'] = '';
		}

		if($this->bm_row['bm_use_file']=='1'){
			$view_form_file = $this->load->view($this->skin_path.'/form_file',array(
				'mode'=>$mode,
				'get'=>$get,
				'bm_row' => $this->bm_row,
				'bbs_conf'=>$this->bbs_conf,
				'permission'=>$permission,
				'bf_rows'=>($mode=='edit')?$this->get_bf_rows_by_b_row($b_row):array(),
			),true);
		}else{
			$view_form_file = '';
		}

		$this->config->set_item('layout_og_title', $this->config->item('layout_og_title')." : 작성폼");
		$this->config->set_item('layout_og_description', "작성폼");

		$this->load->view($this->skin_path.'/form',array(
		'b_row' => $b_row,
		'bm_row' => $this->bm_row,
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		'mode'=>$mode,
		'process'=>$mode,
		'm_row' => $this->m_row,
		'logedin' => $this->logedin,
		'input_b_name'=>!isset($b_row['b_insert_date']) && !$this->logedin, //이름을 입력 받아야하는가?
		'input_b_pass'=>isset($b_row['b_pass'][0]) || !$this->logedin, //비밀번호를 입력 받아야하는가?
		'permission'=>$permission,
		'view_form_file'=>$view_form_file,
		));
	}

	public function mode_delete($b_idx){
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다',400,'Bad Request');
		}
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('게시물이 없습니다',404,'File not found');
		}


		$get = $this->input->get();
		$post = $this->input->post();

		$this->extends_b_row($b_row,$get);

		$permission = $this->get_permission_lists($b_row['m_idx']);
		if(!$permission['delete']){
			show_error('권한이 없습니다.',403,'Permission denied');
		}

		$this->config->set_item('layout_head_contents',$this->get_head_contents('read'));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',''.$this->bbs_conf['mode'].' : '.$b_row['b_title'].' : '.$this->bm_row['bm_title']);

		$b_pass = $this->input->post('b_pass');
		if(!$this->required_password($b_row,$b_pass,'삭제하시겠습니까?',$b_row['b_title'])){
			return;
		}

		$b_row['b_pass'] = $b_pass;

		//print_r($conf);
		if($this->input->post('process')){
			return $this->_mode_process($b_row);
		}
		$error_msg = '';

		$this->config->set_item('layout_og_title', $this->config->item('layout_og_title')." : 삭제폼");
		$this->config->set_item('layout_og_description', "삭제폼");

		$this->load->view($this->skin_path.'/delete',array(
		'b_row' => $b_row,
		'bm_row' => $this->bm_row,
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		'process'=>$this->bbs_conf['mode'],
		'm_row' => $this->m_row,
		'logedin' => $this->logedin,
		'error_msg' => $error_msg,
		'permission'=>$permission,
		));
	}
	private function extends_b_row_for_m_row(&$b_row){
		if($this->common->logedin){
			$b_row['m_idx'] = $this->common->get_login('m_idx');
			$b_row['b_name'] = $this->common->get_login('m_nick');
		}
	}
	private function _mode_process($b_row){
		$process = $this->input->post('process');
		$get = $this->input->get();
		$b_idx = $b_row['b_idx'];
		$post = $this->input->post();
		unset($post['process']);

		$permission = $this->get_permission_lists($b_row['m_idx']);
		if(!$permission[$process]){
			show_error('권한이 없습니다.',403,'Permission denied');
		}

		$this->config->set_item('layout_head_contents',$this->get_head_contents($process));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',''.$this->bbs_conf['mode'].' : process : '.$this->bm_row['bm_title']);

		$r = 0;
		switch($process){
			case 'edit':
				unset($post['b_pass']);
				$r = $this->bbs_m->update_b_row($b_idx,$post);
				if($this->bm_row['bm_use_file']=='1'){
					if(isset($_FILES['upf'])) $bf_r = $this->bf_m->upload_files($b_idx,$_FILES['upf']);
					if(isset($_POST['ext_urls']) && isset($_POST['ext_urls_types'])) {
						$bf_ext_r = $this->bf_m->insert_external_url($b_idx,$_POST['ext_urls'],$_POST['ext_urls_types']);
					}
					if($this->input->post('delf')){
						$delf_r = $this->bf_m->delete_bf_rows_by_b_idx_bf_idxs($b_idx,$this->input->post('delf'));
						//print_r($delf_r);
					}
				}
				// $this->delete_tags($b_idx);
				if(isset($post['b_text'][0])){$this->apply_tags($b_idx,$post,'update');}
			break;
			case 'write':

				$this->extends_b_row_for_m_row($post);
				$b_idx = $r = $this->bbs_m->insert_b_row($post);
				if($b_idx){
					if($this->bm_row['bm_use_file']=='1'){
						if(isset($_FILES['upf'])) {
							$bf_r = $this->bf_m->upload_files($b_idx,$_FILES['upf']);
						}
						if(isset($_POST['ext_urls']) && isset($_POST['ext_urls_types'])) {
							$bf_ext_r = $this->bf_m->insert_external_url($b_idx,$_POST['ext_urls'],$_POST['ext_urls_types']);
						}
						if(isset($_FILES['upf']) || isset($_POST['ext_urls']) && isset($_POST['ext_urls_types'])) {
							$this->bf_m->set_represent_by_b_idx($b_idx);
						}

					}
				}
				if(isset($post['b_text'][0])){$this->apply_tags($b_idx,$post,'insert');}
			break;
			case 'answer':
				$this->extends_b_row_for_m_row($post);
				$b_idx = $r = $this->bbs_m->insert_answer_b_row($b_idx,$post);
				if($b_idx){
					if($this->bm_row['bm_use_file']=='1'){
						if(isset($_FILES['upf'])) $bf_r = $this->bf_m->upload_files($b_idx,$_FILES['upf']);
					}
				}
				if(isset($post['b_text'][0])){$this->apply_tags($b_idx,$post,'insert');}
			break;
			case 'delete':
				$r = $this->bbs_m->delete_b_row($b_idx);
				$delf_r = $this->bf_m->delete_bf_rows_by_b_idx($b_idx);
				$b_idx = $r;
				if(isset($post['b_text'][0])){$this->apply_tags($b_idx,$post,'delete');}
			break;
			case 'set_represent':
				$delf_r = $this->bf_m->set_represent_by_b_idx_bf_idx($b_idx,$this->input->post('bf_idx'));
			break;
			default:
				show_error('허용되지 않는 요청');
			break;
		}


		$b_row = array('b_idx'=>$b_idx);
		$this->extends_b_row($b_row,$get);

		if($process =='delete'){
			$ret_url = $this->bbs_conf['list_url'];
		}else{
			$ret_url = $b_row['read_url'];
		}
		$this->config->set_item('layout_hide',true);

		$this->config->set_item('layout_og_title', $this->config->item('layout_og_title')." : 처리중");
		$this->config->set_item('layout_og_description', "처리중");

		$this->load->view($this->skin_path.'/process',array(
		//'b_row' => $b_row,
		'bm_row' => $this->bm_row,
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		'process'=>$this->bbs_conf['mode'],
		'ret_url'=>$ret_url,
		'msg'=>'처리완료.',
		));

	}
	
	public function select_tags($b_idx){
		if($this->bm_row['bm_use_tag']!='0'){
			return $this->bt_m->select_by_b_idx($b_idx);
		}
		return null;
	}
	public function apply_tags($b_idx,$b_row,$mode="update"){
		if($this->bm_row['bm_use_tag']!='0'){
			// $tags = $this->bt_m->pickup_tags('#'.$b_row['b_category'].' '.strip_tags($b_row['b_text'])); //카테고리도 기본으로 넣는 경우
			$tags = $this->bt_m->pickup_tags($b_row['b_text']);
			$tags = array_slice($tags,0,20); // 태그는 20개 까지만
			
			if($mode=='update' ||$mode=='delete'){
				$this->bt_m->delete_by_b_idx($b_idx,$tags);	
			}
			if($mode=='update' ||$mode=='insert'){
				foreach($tags as $tag){
					$this->bt_m->insert($b_idx,$tag);
				}
			}
		}
		
	}
}
