<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== GA 도매국 키워드(검색어) 모델

class Keyword_rank_score_model extends CI_Model {

	public $tbl_score='keyword_rank_????_score';
	public $tbl_words='keyword_rank_naver_words';
	public $tbl_data='keyword_rank_naveralldepth_data';


	public $fileds = array();

	public function __construct()
	{
		// $this->load->library('mh_cache');
		// Call the CI_Model constructor
		parent::__construct();
		$this->fileds = array(
			'kr_kwid',
			'kr_monthlyPcQcCnt',
			'kr_monthlyMobileQcCnt',
			'kr_monthlyAvePcClkCnt',
			'kr_monthlyAveMobileClkCnt',
			'kr_monthlyAvePcCtr',
			'kr_monthlyAveMobileCtr',
			'kr_plAvgDepth',
			'kr_compIdx',
			'kr_search_total_shop',
			'kr_relKeywordCount',
			// 'kr_update_at',

		);
	}
	public function count_date_cid($kr_date,$kr_cid){
		$wheres['kr_date'] = $kr_date;
		$wheres['kr_cid'] = $kr_cid;
		return $this->count($wheres);
	}
	public function count($wheres,$order_by='',$limit=null,$offset=null){
		$select='count(*) CNT';
		$rows = $this->db->select($select)->from($this->tbl_score.'  krs')->where($wheres)->order_by($order_by)->limit($limit, $offset)->get()->result_array();
		return $rows[0]['CNT'];
	}
  public function select($wheres,$select='*',$order_by='',$limit=null,$offset=null){
    return $this->db->select($select)->from($this->tbl_score.'  krs')->join($this->tbl_words.' krw','kr_kwid','inner')->where($wheres)->order_by($order_by)->limit($limit, $offset)->get()->result_array();
  }
	public function select_by_keyword($keyword){
		$v_keyword = $this->db->escape($keyword);
		$sql = "select * from {$this->tbl_score} join {$this->tbl_words} using(kr_kwid) where kr_keyword = {$v_keyword}";
		echo $sql;
	}
	public function select_target_rows($limit=10000,$modN=0,$divN=0){
		$wheres = array();
		$wheres['kr_update_at <= DATE_SUB(NOW(),INTERVAL 30 DAY)'] = null;
		$wheres['kr_iserror'] = 0;
		if($divN!=0){
			$wheres['(kr_kwid%'.$divN.')'] = $modN;
		}

		return $this->select($wheres,'kr_keyword,kr_kwid','',$limit);
	}
	public function select_openapi_target_rows($limit=10000,$modN=0,$divN=0){
		$wheres = array();
		$wheres['kr_openapi_update_at <= DATE_SUB(NOW(),INTERVAL 30 DAY)'] = null;
		$wheres['kr_iserror'] = 0;
		if($divN!=0){
			$wheres['(kr_kwid%'.$divN.')'] = $modN;
		}

		return $this->select($wheres,'kr_keyword,kr_kwid','kr_kwid',$limit);
	}
	public function select_searchad_target_rows($limit=10000,$modN=0,$divN=0){
		$wheres = array();
		$wheres['kr_searchad_update_at <= DATE_SUB(NOW(),INTERVAL 30 DAY)'] = null;
		$wheres['kr_iserror'] = 0;
		if($divN!=0){
			$wheres['(kr_kwid%'.$divN.')'] = $modN;
		}
		$order_by = 'nsc.nsc_depth';
		$offset = 0;
		$select = 'kr_keyword,kr_kwid';
		$rows = $this->db->select($select)->from($this->tbl_score.'  krs')
		->join($this->tbl_words.' krw','kr_kwid','inner')
		->join($this->tbl_data.' krd','kr_kwid','inner')
		->join('naver_shopping_category nsc','nsc.nsc_id = krd.kr_cid','inner')
		->where($wheres)->order_by($order_by)->limit($limit, $offset)->get()->result_array();
		// echo $this->db->last_query();
		// print_r($rows);exit;

		// return $this->select($wheres,'kr_keyword,kr_kwid','kr_kwid',$limit);
		return $rows;
	}
	public function insert_row($row){
		//여기서 insert 안한다.
	}
	public function update_row_by_keyword($kr_keyword,$row){
		// UPDATE keyword_rank_naver_score krs JOIN keyword_rank_naver_words krw ON(krs.kr_kwid = krw.kr_kwid)
		// SET kr_monthlyPcQcCnt = 0
		// WHERE kr_keyword = '가습기';
		if(isset($row['kr_search_total_shop'])){
			$this->db->set('kr_openapi_update_at','now()',false);
		}
		if(isset($row['kr_monthlyPcQcCnt'])){
			$this->db->set('kr_searchad_update_at','now()',false);
			$this->db->where('kr_searchad_update_at<=','DATE_SUB(NOW(),INTERVAL 30 DAY)',false);
		}
		$wheres = array();
		$wheres['kr_keyword'] = $kr_keyword;
		$this->db->from($this->tbl_score.'  krs join '.$this->tbl_words.' krw using(kr_kwid)')->set($row)->set('kr_update_at','now()',false)->where($wheres)->update();
		return $this->db->affected_rows();
	}
}
