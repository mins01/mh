<?php
defined('BASEPATH') OR exit('No direct script access allowed');
//== �Խ��� ������ ���� ��

class Bbs_master_model extends CI_Model {
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		
	}
	
	public function get_bm_row($b_id){
		return $this->db->from(DB_PREFIX.'bbs_master')->where('b_id',$b_id)->get()->row_array();
	}
	
	

}