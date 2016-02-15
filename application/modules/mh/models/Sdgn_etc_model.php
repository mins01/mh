<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- Sdgn 유닛

class Sdgn_etc_model extends CI_Model {
	public $bm_row = array();
	public $error = '';
	private $tbl = '';
	
	public function select_comment_for_main(){
		$sql = "SELECT bc_name,COUNT(*) cnt FROM mh_bbs_sdgnunits_comment bc
				WHERE bc_isdel = 0 AND bc_number > 0
				GROUP BY bc_name
				ORDER BY cnt DESC
				LIMIT 10
			";
		return $this->db->query($sql)->result_array();
		
	}
	public function select_units_for_main(){
		$select_avg_star = "(SELECT AVG(bc_number) avg_star FROM mh_bbs_sdgnunits_comment bc WHERE b_idx= su.unit_idx and bc.bc_isdel =0 AND bc_number>0)";
		$sql = "	SELECT 
				*, {$select_avg_star} as avg_star
				FROM(SELECT b_idx unit_idx,max(bc_insert_date) max_bc_insert_date,COUNT(*) cnt FROM mh_bbs_sdgnunits_comment bc
				WHERE bc_isdel = 0 AND bc_number > 0
				GROUP BY b_idx
				ORDER BY cnt DESC , max_bc_insert_date desc
				LIMIT 10) bc
				JOIN sdgn_units su USING(unit_idx)
			";
		return $this->db->query($sql)->result_array();
	}
	
	public function select_last_comment_for_main(){
		$sql = "	SELECT 
				*
				FROM(SELECT *,b_idx unit_idx FROM mh_bbs_sdgnunits_comment
				WHERE bc_isdel = 0 AND bc_number > 0
				ORDER BY bc_idx DESC
				LIMIT 10) bc
				JOIN sdgn_units su USING(unit_idx)
			";
		return $this->db->query($sql)->result_array();
	}
	


	
}