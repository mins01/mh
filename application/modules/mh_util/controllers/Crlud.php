<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 간단한 DB 테이블 관리 툴.
 */
class Crlud extends MX_Controller {
	// private $crlud_m = null;
	public $from = 'mcv_body';
	public $read_select = 'gr_nick,gr_game,gr_date';
	// private $show_fields = array('gr_nick','gr_game','gr_date');
	public $show_fields = array();
	public $modul_path = 'mh_util';
	public function __construct()
	{
		
		
		// $this->config->load('conf_front'); // 프론트 사이트 설정
		// 
		// $this->load->module('mh/common');
		// $this->load->module('mh/layout');
		// // $this->config->set_item('layout_disable',true);
		// $this->config->set_item('layout_hide',1);
		
		$this->load->model($this->modul_path.'/crlud_model','crlud_m');
		
		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		// $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//print_r($this->cache);
	}
	public function index(){
		return $this->action(false);
		
	}
	public function action($return_view){
		$mode = $this->input->get_post('_mode');
		// print_r($_POST);
		if($mode == 'process'){			
			return $this->process($return_view);	
		}else{
			return $this->view($return_view);	
		}
		
	}	
	
	public function get_show_field($show_fields,$field_rowss){
		if(count($show_fields)==0){
			return array_keys($field_rowss);
		}else{
			return $show_fields;
		}
	}
	
	public function view($return_view){
		$field_rowss = $this->crlud_m->show_columns($this->from);
		$show_fields = $this->get_show_field($this->show_fields,$field_rowss);
		$get = array();
		$wheres = array();
		foreach($show_fields as $k){
			$get[$k]=$this->input->get($k);
			if(isset($get[$k][0])){
				$wheres[$k] = $get[$k];
			}
		}
	
		$rows = $this->crlud_m->lists($this->from,implode(',',$show_fields),$wheres);
		// echo $this->db->last_query();
		// print_r($field_rowss);
		// print_r($rows);
		$data = array(
			'from'=>$this->from,
			'rows'=>$rows,			
			'field_rowss'=>$field_rowss,
			'show_fields'=>$show_fields,
			'pks'=>$this->get_pk_from_field_rowss($field_rowss),
			'get'=>$get,
		);
		return $this->load->view($this->modul_path.'/crlud/index',$data,$return_view);
	}
	
	public function get_pk_from_field_rowss($field_rowss){
		$pks = array();
		foreach($field_rowss as $field_rows){
			if($field_rows['Key']!='PRI'){ continue; }
			$pks[]=$field_rows['Field'];
		}
		return $pks;
	}
	
	public function process($return_view){
		
		$process = $this->input->post('_process');
		
		$referer = isset($_SERVER['HTTP_REFERER'])?$_SERVER['HTTP_REFERER']:'';
		if(!isset($referer[0])){
			show_error("잘못된 접근!");
		}
		
		$field_rowss = $this->crlud_m->show_columns($this->from);
		$show_fields = $this->get_show_field($this->show_fields,$field_rowss);
		
		switch($process){
			case 'create':
			// print_r($_POST);exit;
				$sets = array();
				foreach($show_fields as $k){
					$sets[$k]=$this->input->post($k);
				}	
				$this->crlud_m->create($this->from,$sets);
				// echo $this->db->last_query();
				header('Location: '.$referer);
				return;
			break;
			case 'update':
			// print_r($_POST);exit;
				$pks = $this->get_pk_from_field_rowss($field_rowss);
				$sets = array();
				$wheres = array();
				foreach($show_fields as $k){
					if(in_array($k,$pks)){
						$wheres[$k]=$this->input->post($k);	
					}else{
						$sets[$k]=$this->input->post($k);	
					}
					
				}	
				$this->crlud_m->update($this->from,$wheres,$sets);
				// echo $this->db->last_query();
				
				header('Location: '.$referer);
				return;
			break;
		}
	}
}






