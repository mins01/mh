<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 간단한 DB 테이블 관리 툴.
 */
class Crlud extends MX_Controller {
	// private $crlud_m = null;
	private $from = 'mcv_body';
	private $read_select = 'gr_nick,gr_game,gr_date';
	// private $show_fields = array('gr_nick','gr_game','gr_date');
	private $show_fields = array();
	public $modul_path = 'mh_util';
	public function __construct()
	{
		
		
		$this->config->load('conf_front'); // 프론트 사이트 설정

		$this->load->module('mh/common');
		$this->load->module('mh/layout');
		// $this->config->set_item('layout_disable',true);
		$this->config->set_item('layout_hide',1);
		
		$this->load->model($this->modul_path.'/crlud_model','crlud_m');
		
		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		// $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//print_r($this->cache);
	}
	
	public function index(){
		$mode = $this->input->get_post('mode');
		if($mode == 'process'){
			return $this->process();	
		}else{
			return $this->view();	
		}
		
	}
	
	public function view(){
		$field_rowss = $this->crlud_m->show_columns($this->from);
		if(count($this->show_fields)==0){
			$show_fields = array_keys($field_rowss);
		}else{
			$show_fields = $this->show_fields;
		}
		$get = array();
		$wheres = array();
		
		foreach($show_fields as $k){
			$get[$k]=$this->input->get($k);
			if(isset($get[$k][0])){
				$wheres[$k] = $get[$k];
			}
			
		}
	
		$rows = $this->crlud_m->lists($this->from,implode(',',$show_fields),$wheres);
		echo $this->db->last_query();
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
		$this->load->view($this->modul_path.'/crlud/index',$data);
	}
	
	public function get_pk_from_field_rowss($field_rowss){
		$pks = array();
		foreach($field_rowss as $field_rows){
			if($field_rows['Key']!='PRI'){ continue; }
			$pks[]=$field_rows['Field'];
		}
		return $pks;
	}
	
	public function create(){
		
	}
	public function read(){
		
	}
	public function update(){
		
	}
	public function delete(){
		
	}
	public function lists(){
		
	}
	
	public function process(){
		
	}
}






