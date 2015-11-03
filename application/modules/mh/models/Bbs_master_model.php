<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//== 게시판 마스터 관리 모델

class Bbs_master_model extends CI_Model {
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
	}
	
	public function get_bm_row($b_id){
		$bm_row = $this->db->from(DB_PREFIX.'bbs_master')->where('b_id',$b_id)->get()->row_array();
		$this->extends_bo_row($bm_row);
		return $bm_row;
	}
	public function extends_bo_row(& $bm_row){
		$t = explode(';',trim($bm_row['bm_category']));
		$bm_row['categorys'] = array_merge(array(''=>'#없음#'),array_combine ( $t , $t ));
	}
	
	

}