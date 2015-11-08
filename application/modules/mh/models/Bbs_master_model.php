<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//== 게시판 마스터 관리 모델

class Bbs_master_model extends CI_Model {
	private $tbl = '';
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->tbl = DB_PREFIX.'bbs_master';
	}
	public function select_by_b_id($b_id){
		return $this->get_bm_row($b_id);
	}
	public function get_bm_row($b_id){
		$bm_row = $this->db->from($this->tbl)->where('b_id',$b_id)->get()->row_array();
		$this->extends_bm_row($bm_row);
		return $bm_row;
	}
	public function extends_bm_row(& $bm_row){
		$t = explode(';',trim($bm_row['bm_category']));
		$bm_row['categorys'] = array_merge(array(''=>'#없음#'),array_combine ( $t , $t ));
	}
	public function extends_bm_rows(& $bm_rows){
		foreach($bm_rows as $k=> &$bm_row){
			$this->extends_bm_row($bm_row);
		}
	}
	
	//페이지 값으로 limit와 offset 계산
	public function get_limit_offset($page){
		if(!isset($page) || !is_numeric($page) || $page < 0){
				$page = 1;
		}
		$page = (int)$page;
		//$limit = $this->bm_row['bm_page_limit'];
		$limit = 5;
		$offset = ($page-1)*$limit;
		return array($limit,$offset);
	}
	
	//-- 시작 번호 계산
	public function get_start_num($cnt,$get){
		list($limit,$offset) = $this->get_limit_offset($get['page']);
		return $cnt - $offset;
	}
	
	//-- 목록과 카운팅용
	private function _apply_list_where($get){
		$this->db->from($this->tbl);
		//$this->db->where('b_id',$get['b_id']);
		
		return true;

	}
	
	//목록용
	public function select_for_list($get){
		if(!$this->_apply_list_where($get)){
			return false;
		}

		$this->db->order_by('b_id');
		
		list($limit,$offset) = $this->get_limit_offset($get['page']);
		$this->db->limit($limit,$offset);

		$bm_rows = $this->db->get()->result_array();
		//echo $this->db->last_query();
		$this->extends_bm_rows($bm_rows);
		return $bm_rows;
	}
	//-- 목록 갯수
	public function count($get){
		if(!$this->_apply_list_where($get)){
			return false;
		}

		return $this->db->count_all_results();
	}

}