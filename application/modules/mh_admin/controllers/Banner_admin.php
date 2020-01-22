<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Banner_admin extends MX_Controller {
	private $base_url = null;
	public function __construct()
	{
		$this->load->library('mh_cache');
		$this->load->model('mh/banners_model','banners_m');
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

		$this->set_base_url(ADMIN_URI_PREFIX.'banner_admin');
		$this->action($mode);
	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$mode = isset($param[0][0])?$param[0]:'list';
		$bn_idx = isset($param[1][0])?$param[1]:null;
		$this->set_base_url($base_url);
		$this->action($mode,$bn_idx);
	}

	public function action($mode,$bn_idx){
		$process = $this->input->post('process');
		if($process != null){
			$post = $this->input->post();
			$this->{'process_'.$process}($post);
		}else{
			$this->{'mode_'.$mode}($bn_idx);
		}

	}

  public function mode_list(){
    $skin = 'mh_admin/banner_admin/list';
		$rows = $this->banners_m->select(array());
    $this->load->view($skin,array(
			'rows'=>$rows,
			'base_url'=>$this->base_url,
		));
  }

	public function mode_form($bn_idx=null){
		$this->config->set_item('layout_head_contents',$this->config->item('layout_head_contents')
			.'<link href="/web_work/mb_wysiwyg_dom/bootstrap.css?t='.REFLESH_TIME.'" rel="stylesheet" type="text/css" />'
			.'<link href="/web_work/mb_wysiwyg_dom/mb_wysiwyg.css?t='.REFLESH_TIME.'" rel="stylesheet" type="text/css" />');

    $skin = 'mh_admin/banner_admin/form';
		if($bn_idx==null){
			$row = $this->banners_m->empty_row();
		}else{
			$rows = $this->banners_m->select(array('bn_idx'=>(int)$bn_idx));
			if(!isset($rows[0])){
				show_error('데이터가 없습니다.');
			}
			$row = $rows[0];
		}

    $this->load->view($skin,array(
			'row'=>$row,
			'base_url'=>$this->base_url,
		));
  }

  public function process_update($post){
		if(!isset($post['bn_idx'][0])){
			show_error('관리번호가 없습니다.');
		}
		$bn_idx = $post['bn_idx'];
		$this->banners_m->update_row($bn_idx,$post);
		$ret_url = isset($_SERVER['HTTP_REFERER'][0])?$_SERVER['HTTP_REFERER']:'/';
		$msg = '처리완료';
		$this->common->redirect($msg,$ret_url);
	}
	public function process_insert($post){
		if(isset($post['bn_idx'][0])){
			show_error('잘못된 입력');
		}
		$bn_idx = $this->banners_m->insert_row($post);
		$ret_url = $this->base_url.'/form/'.$bn_idx.$_SERVER['QUERY_STRING'];
		$msg = '처리완료';
		$this->common->redirect($msg,$ret_url);
	}


}
