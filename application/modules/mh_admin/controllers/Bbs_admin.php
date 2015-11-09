<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Bbs_admin extends MX_Controller {
	private $bbs_conf = array();
	private $bm_row = array();
	private $m_row = array();
	private $skin_path = '';
	private $base_url = '';
	private $logedin = null;
	private $limit = 20;
	public function __construct()
	{
				$this->load->helper('form');
		
		$this->load->model('mh/bbs_master_model','bm_m');
		$this->load->model('mh/bbs_model','bbs_m');
		$this->load->module('mh_admin/layout');
		$this->load->module('mh_admin/common');
		
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
		$mode = isset($param[0][0])?$param[0]:'list';
		$b_id = isset($param[1][0])?$param[1]:'';
		//$mode = $this->uri->segment(3,'list');//option
		
		$this->set_base_url(ADMIN_URI_PREFIX.'bbs_admin');
		$this->action($mode,$b_id);
	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$b_id = $conf['menu']['mn_key1'];
		$mode = isset($param[0][0])?$param[0]:'list';
		//$this->set_base_url($base_url);
		$this->action($mode,$b_id);
	}

	public function action($mode,$b_id){
		//-- 게시판 마스터 정보 가져오기

		$this->skin_path = 'mh_admin/bbs_admin';

		$this->bbs_conf['page'] = (int)$this->input->get('page',1);
		if(!is_int($this->bbs_conf['page']) || $this->bbs_conf['page'] <= 0){
			$this->bbs_conf['page'] = 1;
		}
		if(!isset($mode)){
			$mode = 'list';
		}
		
		
		if(!method_exists($this,'mode_'.$mode)){
			show_error('잘못된 모드입니다.');
		}
		//echo $this->base_url;
		
		$this->bbs_conf['list_url'] = $this->base_url . "/list?".http_build_query($this->input->get());
		$this->bbs_conf['write_url'] = $this->base_url . "/write?".http_build_query($this->input->get());
		$this->bbs_conf['mode'] = $mode;
		
		
		$this->{'mode_'.$mode}($b_id);

	}

	private function pagination($get,$total_rows){
		$max_page = ceil($total_rows/$this->limit);
		$uri = $this->base_url . "/list";
		return generate_paging($get,$max_page,$uri);
	}
	
	private function get_permission_lists($m_idx=''){
		$adm_level = $this->common->get_login('adm_level');
		$is_admin = 1<=$adm_level;
		
		if(!isset($m_level)) $m_level = 0;
		return array(
			'list'=>$is_admin,
			'read'=>$is_admin,
			'write'=>$is_admin,
			'edit'=>$is_admin,
			'answer'=>$is_admin,
			'delete'=>$is_admin,
			'admin'=>$is_admin,
			'mine'=>$is_admin,
		);
	}
	private function extends_bm_row(& $bm_row,$get){
				
		$bm_row['read_url'] = $this->base_url . '/read/'.$bm_row['b_id'].'?'.http_build_query($get);
		
		$bm_row['answer_url'] = $this->base_url . '/answer/'.$bm_row['b_id'].'?'.http_build_query($get);
		
		$bm_row['edit_url'] = $this->base_url . '/edit/'.$bm_row['b_id'].'?'.http_build_query($get);
		
		$bm_row['delete_url'] = $this->base_url . '/delete/'.$bm_row['b_id'].'?'.http_build_query($get);
		
		$bm_row['write_url'] = $this->base_url . '/write?'.http_build_query($get);
	}
	private function extends_bm_rows(&$bm_rows,$get){
		foreach($bm_rows as & $r){
			$this->extends_bm_row($r,$get);
		}
	}

	public function mode_list($b_id=null){
		
		$permission = $this->get_permission_lists();
		if(!$permission['list']){
			if($with_read){
				return;
			}else{
				show_error('권한이 없습니다.');
			}
		}
		
		$get = $this->input->get();
		if(!isset($get['page']) || !is_numeric($get['page']) || $get['page']<1){ $get['page'] = 1; }
		if(!isset($get['tq'])){ $get['tq'] = ''; }
		if(!isset($get['q'])){ $get['q'] = ''; }
		if(!isset($get['ct'])){ $get['ct'] = ''; }
		$get['page']=$this->bbs_conf['page'];
		
		$bm_rows = $this->bm_m->select_for_list($get);
		//var_dump($bm_rows);
		$this->extends_bm_rows($bm_rows,$get);
		$count = $this->bm_m->count($get);
		$start_num = $this->bm_m->get_start_num($count,$get);
		
		$tmp = $this->input->get();
		$tmp['page'] ='page';
		$def_url = $this->base_url . "/list?".str_replace('page=page','page={{page}}',http_build_query($tmp));

		$pagination = $this->load->view($this->skin_path.'/pagination',array(
		'max_page' => ceil($count/$this->limit),
		'page'=>$this->bbs_conf['page'],
		'def_url'=>$def_url
		),true);
		
		$this->config->set_item('layout_head_contents',$this->get_head_contents('list'));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','list : 게시판관리자');
	
		$this->load->view($this->skin_path.'/list',array(
		'bm_rows' => $bm_rows,
		'count' => $count,
		'max_page' => ceil($count/$this->limit),
		'start_num' => $start_num,
		'get'=>$get,
		'pagination' => $pagination,
		'bbs_conf'=>$this->bbs_conf,
		'b_id'=>$b_id,
		'permission'=>$permission,
		));
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
		return $this->load->view( $this->skin_path.'/head_contents',array('mode'=>$mode,'bm_row'=>$this->bm_row),true);
	}
	//-- 이건 사용안됨.
	public function mode_read($b_idx){
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다');
		}
		$get = $this->input->get();
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('데이터가 없습니다');
		}
		$this->extends_b_row($b_row,$get);
		$permission = $this->get_permission_lists($b_row['m_idx']);
		if(!$permission['read']){
			show_error('권한이 없습니다.');
		}
		if($b_row['b_secret']=='1' && !$permission['mine']){
			$b_pass = $this->input->post('b_pass');
			if(!$this->required_password($b_row,$b_pass,'비밀번호 확인')){
				return;
			}
		}


		$this->config->set_item('layout_head_contents',$this->get_head_contents('read'));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','read : '.$b_row['b_title'].' : '.$this->bm_row['bm_title']);
		
		$comment_url = base_url('bbs_comment/'.$this->bm_row['b_id'].'/'.$b_idx);
		$this->load->view($this->skin_path.'/read',array(
		'b_row' => $b_row,
		'bm_row' => $this->bm_row,
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		'html_comment'=>$this->load->view($this->skin_path.'/comment',array('comment_url'=>$comment_url),true),
		'permission'=>$permission,
		));
		
		if($this->bm_row['bm_read_with_list']=='1'){
			$this->mode_list($b_idx,true);
		}
	}
	public function mode_edit($b_id){
		if(!$b_id){
			show_error('게시판 아이디가 없습니다');
		}
		$bm_row = $this->bm_m->select_by_b_id($b_id);
		if(!$bm_row){
			show_error('게시판 없습니다');
		}
		$this->extends_bm_row($bm_row,$this->input->get());
		

		$this->_mode_form($bm_row,'edit');
	}

	public function mode_answer($b_idx){
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다');
		}
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('게시물이 없습니다');
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
		$bm_row = $this->bm_m->generate_empty_bm_row();
		$this->extends_bm_row($bm_row,$this->input->get());
		
		$this->_mode_form($bm_row,'write');
	}

	private function _mode_form($bm_row,$mode){
		$this->load->library('form_validation');
		$this->form_validation->set_error_delimiters('<div class="text-danger">', '</div>');
		//print_r($conf);
		
		$permission = $this->get_permission_lists();
		if(!$permission[$mode]){
			show_error('권한이 없습니다.');
		}
		//print_r($permission);
		
		if($this->input->post('process')){
			
			if($this->input->post('process')=='write'){
				$this->form_validation->set_rules('b_id', '게시판아이디', 'required|min_length[2]|max_length[100]|is_unique['.$this->bm_m->tbl.'.b_id]');
				if ($this->form_validation->run() == FALSE){
					$bm_row = array_merge($bm_row,$this->input->post());
				}else{
					return $this->_mode_process($bm_row);
				}
			}else{
				return $this->_mode_process($bm_row);
			}
		}
		
		
		$get = $this->input->get();
		$post = $this->input->post();

		$this->extends_bm_row($bm_row,$get);

		$this->config->set_item('layout_head_contents',$this->get_head_contents($mode));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',''.$mode.' : '.$bm_row['bm_title'].' : 게시판관리자');
		
		
		$this->load->view($this->skin_path.'/form',array(
		'bm_row' => $bm_row,
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		'mode'=>$mode,
		'process'=>$mode,
		'm_row' => $this->m_row,
		'logedin' => $this->logedin,
		'permission'=>$permission,
		'skins' => $this->bm_m->lists_of_skins(),
		'tables'=>$this->bm_m->lists_of_tables(),
		));
	}

	public function mode_delete($b_idx){
		if(!$b_idx){
			show_error('게시물 아이디가 없습니다');
		}
		$b_row = $this->bbs_m->select_by_b_idx($b_idx);
		if(!$b_row){
			show_error('게시물이 없습니다');
		}
		

		$get = $this->input->get();
		$post = $this->input->post();
		
		$this->extends_b_row($b_row,$get);
		
		$permission = $this->get_permission_lists($b_row['m_idx']);
		if(!$permission['delete']){
			show_error('권한이 없습니다.');
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
	
	private function _mode_process($bm_row){
		$msg = '처리완료.';
		$process = $this->input->post('process');
		$get = $this->input->get();
		$b_id = $bm_row['b_id'];
		$post = $this->input->post();
		unset($post['process']);
		
		$permission = $this->get_permission_lists();
		if(!$permission[$process]){
			show_error('권한이 없습니다.');
		}
		
		$this->config->set_item('layout_head_contents',$this->get_head_contents($process));
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title',''.$this->bbs_conf['mode'].' : process : 게시판관리자');
		
		$r = 0;
		switch($process){
			case 'edit':
				$r = $this->bm_m->update_bm_row($b_id,$post);
			break;
			case 'write':
				$b_id = $this->input->post('b_id');
				if(!isset($b_id)){
					$b_id = null;
					$msg = '필수 값이 없습니다.';
				}else if($this->bm_m->count_bm_row_by_b_id($b_id)>0){
					$b_id = null;
					$msg = '중복된 게시판 아이디입니다.';
				}else{
					$b_id = $this->bm_m->insert_bm_row($post);
				}
			break;
			case 'delete':
				//$r = $this->bm_m->delete_b_row($b_idx);
				//$b_id = $r;
				$b_id = null;
			break;
		}

		$bm_row = array('b_id'=>$b_id);
		$this->extends_bm_row($bm_row,$get);
		
		if(!isset($b_id)){
			$ret_url = $this->bbs_conf['list_url'];
		}else{
			$ret_url = $bm_row['edit_url'];
		}
		
		$this->load->view($this->skin_path.'/process',array(
		'get'=>$get,
		'bbs_conf'=>$this->bbs_conf,
		'process'=>$process,
		'ret_url'=>$ret_url,
		'msg'=>$msg,
		));

	}

}






