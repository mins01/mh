<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Db_table_manager extends MX_Controller {
	public function __construct()
	{
		$this->config->load('db_table_manager');
		$this->dtm_conf = $this->config->item('db_table_manager');
		// print_r($this->dtm_conf);
		$this->load->model('mh_admin/db_table_manager_model','db_table_manager_m');
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
		$mode = isset($param[0][0])?$param[0]:'show_tables';
		$tbl_name = isset($param[1][0])?$param[1]:'';
		//$mode = $this->uri->segment(3,'list');//option
		
		$this->set_base_url(ADMIN_URI_PREFIX.'bbs_manager');
		$this->action($mode,$tbl_name);
	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$tbl_name = isset($param[1][0])?$param[1]:'';
		$mode = isset($param[0][0])?$param[0]:'show_tables';
		$this->set_base_url($base_url);
		$this->action($mode,$tbl_name);
	}

	public function action($mode,$tbl_name){
		//-- 게시판 마스터 정보 가져오기

		$this->skin_path = 'mh_admin/db_table_manager';
		$mode = $this->input->get_post('mode');
		$tbl_name = $this->input->get_post('tbl_name');
		if(!isset($mode)){
			$mode = 'show_tables';
		}
		
		if(!method_exists($this,'mode_'.$mode)){
			show_error('잘못된 모드입니다.');
		}
		//echo $this->base_url;
		
		// $this->bbs_conf['list_url'] = $this->base_url . "/list?".http_build_query($this->input->get());
		// $this->bbs_conf['write_url'] = $this->base_url . "/write?".http_build_query($this->input->get());
		// $this->bbs_conf['mode'] = $mode;
		
		$this->{'mode_'.$mode}($tbl_name);

	}
	
	public function mode_show_tables($tbl_name){
		// $rows = $this->db_table_manager_m->show_tables();
		$rows = array_keys($this->dtm_conf);
		// print_r($rows);
		$this->load->view('mh_admin/db_table_manager/show_tables',array(
			'rows'=>$rows,	
			'base_url'=>$this->base_url,			
		));
		
	}
	public function mode_lists($tbl_name){
		if(!isset($this->dtm_conf[$tbl_name])){
			show_error('지원되지 않는 테이블 입니다.');
		}
		$cnf = $this->dtm_conf[$tbl_name];
		if(!isset($cnf['pks'][0])){
			show_error('pks 설정이 없습니다.');	
		}
		$wheres = isset($cnf['wheres'])?$cnf['wheres']:array();
		$get = $this->input->get();
		foreach($get as $k=>$v){
			if(strpos($k,'_!SH_')!==0){
				continue;
			}
			$k2 = str_replace('_!SH_','',$k);
			$v2 = $this->db->escape_like_str($v);
			$t = "{$k2} like '%{$v}%' ";
			$wheres[$t] = null;
		}
		$order_by = isset($cnf['order_by'])?$cnf['order_by']:'';
		$order_by = preg_replace('/[^_0-9a-z ]/','',$order_by);
		$rows = $this->db_table_manager_m->select($tbl_name,$wheres,$order_by,100,0);
		// echo $this->db->last_query();
		// print_r($rows);
		$this->load->view('mh_admin/db_table_manager/lists',array(
			'cnf'=>$cnf,
			'rows'=>$rows,	
			'base_url'=>$this->base_url,
			'tbl_name'=>$tbl_name,
		));
		
	}
	public function mode_form($tbl_name){

		
		if(!isset($this->dtm_conf[$tbl_name])){
			show_error('지원되지 않는 테이블 입니다.');
		}
		$pks = $this->input->get_post('pks');
		$cnf = $this->dtm_conf[$tbl_name];
		if(!isset($cnf['pks'][0])){
			show_error('pks 설정이 없습니다.');	
		}
		if(count($cnf['pks']) != count($pks)){
			show_error('입력된 pks와 설정된 pks의 수가 다릅니다.');	
		}
		$wheres = isset($cnf['wheres'])?$cnf['wheres']:array();
		$i=0;
		foreach($cnf['pks'] as $f){
			$wheres[$f]=$pks[$i++];
		}
		// $order_by = isset($cnf['order_by'])?$cnf['order_by']:'';
		// $order_by = preg_replace('/[^_0-9a-z ]/','',$order_by);
		$order_by = '';

		$rows = $this->db_table_manager_m->select($tbl_name,$wheres,$order_by,100,0);
		if(!isset($rows[0])){
			$referer = $this->base_url;
			header('Location: '.$referer,true,302);
			return;
		}
		$columns = $this->db_table_manager_m->show_columns($tbl_name);
		// print_r($columns);
		$this->load->view('mh_admin/db_table_manager/form',array(
			'cnf'=>$cnf,
			'rows'=>$rows,
			'columns'=>$columns,
			'base_url'=>$this->base_url,
			'tbl_name'=>$tbl_name,
		));
		
	}
	public function mode_process($tbl_name){
		$process = $this->input->get_post('process');
		if(isset($process[0])){
			return $this->{'process_'.$process}($tbl_name);
		}
	}
	public function process_update($tbl_name){
		$columns = $this->db_table_manager_m->show_columns($tbl_name);
		$sets = array();
		$wheres = array();
		foreach($columns as $column){
			$k = $column['Field'];
			$v = $this->input->post($k);
			if($column['Key']=='PRI'){
				$v2 = $this->input->post('_!@#$_'.$k);
				if(!isset($v2[0])){
					show_error('PK 값이 없습니다.');
					return false;
				}
				$wheres[$k] = $v2;
			}
			if(!isset($_POST[$k])){
				continue;
			}
			$sets[$k] = $v;
		}
		$affected_rows = $this->db_table_manager_m->update($tbl_name,$wheres,$sets);
		// echo $this->db->last_query();
		$referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'/';
		// exit($referer);
		header('Location: '.$referer,true,302);
		return true;
	}

}






