<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//== 게시판 마스터 관리 모델

class Bbs_master_model extends CI_Model {
	public $tbl = '';
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
		if(!isset($bm_row['bm_table'][0])){
			return null;
		}
		$this->extends_bm_row($bm_row);
		return $bm_row;
	}
	public function extends_bm_row(& $bm_row){
		$t = explode(';',trim($bm_row['bm_category']));
		$bm_row['categorys'] = array_merge(array(''=>'#없음#'),array_combine ( $t , $t ));
		$bm_row['tbl_data']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_data';
		$bm_row['tbl_file']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_file';
		$bm_row['tbl_comment']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_comment';
		$bm_row['tbl_hit']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_hit';
		$bm_row['tbl_tag']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_tag';
	}
	public function extends_bm_rows(& $bm_rows){
		foreach($bm_rows as $k=> &$bm_row){
			$this->extends_bm_row($bm_row);
		}
	}

	//페이지 값으로 limit와 offset 계산 (사용안함.)
	public function get_offset($page,$limit=5){
		if(!isset($page) || !is_numeric($page) || $page < 0){
				$page = 1;
		}
		$page = (int)$page;
		//$limit = $this->bm_row['bm_page_limit'];
		//$limit = 5;
		$offset = ($page-1)*$limit;
		return $offset;
	}

	//-- 시작 번호 계산
	public function get_start_num($cnt,$get,$limit){
		$offset = get_offset_by_page($get['page'],$limit);
		return $cnt - $offset;
	}

	//-- 목록과 카운팅용
	private function _apply_list_where($get){
		$this->db->from($this->tbl);
		// $this->db->where('b_id',$get['b_id']);
		if(isset($get['q'][0])){
			$this->db->or_like('b_id',$get['q']);
			$this->db->or_like('bm_title',$get['q']);
		}


		return true;

	}

	public function select_for_list_for_menu(){
		if(!$this->_apply_list_where(array())){
			return false;
		}
		$this->db->order_by('b_id');
		$bm_rows = $this->db->get()->result_array();
		// echo $this->db->last_query();
		//$this->extends_bm_rows($bm_rows);
		foreach($bm_rows as $r){
			$rt[$r['b_id']] = $r['bm_title'];
		}
		return $rt;
	}

	//목록용
	public function select_for_list($get,$limit=5,$offset=0){
		if(!$this->_apply_list_where($get)){
			return false;
		}

		$this->db->order_by('b_id');

		//list($limit,$offset) = $this->get_limit_offset($get['page']);
		$this->db->limit($limit,$offset);

		$bm_rows = $this->db->get()->result_array();
		// echo $this->db->last_query();
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
	//-- 글 수정
	public function update_bm_row($b_id,$sets){
		return $this->update_bm_row_as_where(array('b_id'=>$b_id),$sets);
	}
	public function update_bm_row_as_where($where,$sets){
		unset($sets['b_id']);
		$this->db->from($this->tbl)
		->where($where)
		->set($sets)->set('bm_update_date','now()',false)->update();
		return $this->db->affected_rows();
	}
	//-- 빈 게시물 만들기
	public function generate_empty_bm_row(){
		// $sql="DESC {$this->tbl_bbs_data}";
		// $rows = $this->db->query($sql)->result_array();
		// foreach($rows as $r){
		// echo "'{$r['Field']}'=>'',\n";
		// }
		// print_r($rows);
		$bm_row = array(
			'b_id'=>'',
			'bm_table'=>'',
			'bm_title'=>'',
			'bm_insert_date'=>'',
			'bm_update_date'=>'',
			'bm_open'=>'1',
			'bm_skin'=>'bbs',
			'bm_page_limit'=>'10',
			'bm_title_length'=>'',
			'bm_category'=>'',
			'bm_use_category'=>'',
			'bm_list_type'=>'',
			'bm_list_def'=>'list',
			'bm_mode_def'=>'list',
			'bm_use_file'=>'',
			'bm_file_limit'=>'2',
			'bm_use_thumbnail'=>'',
			'bm_use_secret'=>'',
			'bm_new'=>'86400',
			'bm_read_with_list'=>'',
			'bm_lv_list'=>'',
			'bm_lv_read'=>'',
			'bm_lv_write'=>'',
			'bm_lv_answer'=>'',
			'bm_lv_edit'=>'',
			'bm_lv_delete'=>'',
			'bm_lv_down'=>'',
			'bm_lv_admin'=>'',
			'bm_use_comment'=>'',
			'bm_use_commnet_number'=>'0',
			'bm_bc_lv_list'=>'',
			'bm_bc_lv_write'=>'',
			'bm_bc_lv_edit'=>'',
			'bm_bc_lv_delete'=>'',
			'bm_bc_lv_answer'=>'',
			'bm_use_tag'=>'0',
		);
		return $bm_row;
	}
	public function count_bm_row_by_b_id($b_id){
		$cnt = $this->db->from($this->tbl)
		->where('b_id',$b_id)
		->count_all_results();
		return $cnt;
	}
	//-- 글 작성
	public function insert_bm_row($sets){
		if(!isset($sets['b_id'])){
			return false;
		}
		$this->db->from($this->tbl)
		->set($sets)
		->set('bm_insert_date','now()',false)
		->set('bm_update_date','now()',false)->insert();
		return $sets['b_id'];
	}
	public function lists_of_skins(){
		$path=APPPATH.'/modules/mh/views/bbs/skin/';
		$arr = array();
		$d = dir($path);
		while (false !== ($entry = $d->read())) {
			if($entry=='.' || $entry=='..'){continue;}
			if(is_dir($path.$entry)){
				$arr[] = $entry;
			}
		}
		$d->close();
		sort($arr);
		return array_combine ( $arr , $arr );
	}
	public function lists_of_tables(){
		//$sql = "SHOW TABLES LIKE '".DB_PREFIX."bbs_%_data'";
		$pt = DB_PREFIX.'bbs_(.*)_data';
		$rows = $this->db->list_tables();
		$arr = array();
		$matches = array();
		foreach($rows as $r){
			if(preg_match("/{$pt}/",$r,$matches)){
				$arr[] = $matches[1];
			}

		}
		return array_combine ( $arr , $arr );
	}


}
