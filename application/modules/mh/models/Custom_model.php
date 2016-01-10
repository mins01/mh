<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Custom_model extends CI_Model {
	private function last_bbs_sql($table,$b_id,$limit,$day,$type){
		// $bm_row['tbl_data']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_data';
		// $bm_row['tbl_file']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_file';
		// $bm_row['tbl_comment']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_comment';
		// $bm_row['tbl_hit']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_hit';
		
		$from = DB_PREFIX.'bbs_'.$table.'_data';
		$from_menu = DB_PREFIX.'menu';
		$v_b_insert_date = $this->db->escape(date('Y-m-d 00:00:00',time()-60*60*24*$day));
		$v_b_id = $this->db->escape($b_id);
		$v_select = "b_idx,b_id,b_gidx,b_gpos,b_pidx,b_insert_date,b_update_date
		,b_isdel,m_idx,b_name,b_pass,b_ip,b_notice,b_secret,b_html
		,b_link,b_category,b_title,b_etc_0,b_etc_1,b_etc_2,b_etc_3,b_etc_4
		";
		if($type==0){
			$sql = "select {$v_select} from {$from} b
			
			where b_isdel=0 and b_insert_date >= {$v_b_insert_date}
			and b_id = {$v_b_id}
			order by b_idx desc
			limit {$limit} 
			";
		}else{
			$v_b_etc_0 = $this->db->escape(date('Y-m-d 00:00:00',time()-60*60*24*2));
			$v_b_etc_1 = $this->db->escape(date('Y-m-d 00:00:00',time()+60*60*24*$day));
		
			$sql = "select {$v_select} from {$from} b
			
			where b_isdel=0 
			and b_etc_0 >= {$v_b_etc_0}
			and b_etc_1 <= {$v_b_etc_1}
			and b_id = {$v_b_id}
			order by b_etc_0 desc,b_etc_1 desc
			limit {$limit}
			";
			//echo $sql;
		}
		return $sql;
	}
	public function last_bbs_rowss($tbl_b_id,$limit,$day){
		$sqls = array();
		foreach($tbl_b_id as $v){
			$sqls[] = '('.$this->last_bbs_sql($v[0],$v[1],$limit,$day,$v[2]).')';
		}
		$sql = implode("\nUNION ALL\n",$sqls);
		$query = $this->db->query($sql);
		$rowss = array();
		$rows = $query->result_array();
		foreach($rows as $row){
			$query->next_row();
			if(!isset($rowss[$row['b_id']])){
				$rowss[$row['b_id']] = array();
			}
			$rowss[$row['b_id']][]=$row;
		}
		return $rowss;
	}
	
	private function last_bbs_comment_sql($table,$b_id,$limit,$day,$type){
		// $bm_row['tbl_data']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_data';
		// $bm_row['tbl_file']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_file';
		// $bm_row['tbl_comment']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_comment';
		// $bm_row['tbl_hit']= DB_PREFIX.'bbs_'.$bm_row['bm_table'].'_hit';
		
		$from = DB_PREFIX.'bbs_'.$table.'_comment';
		$from_data = DB_PREFIX.'bbs_'.$table.'_data';
		$v_b_insert_date = $this->db->escape(date('Y-m-d 00:00:00',time()-60*60*24*$day));
		$v_b_id = $this->db->escape($b_id);
		$v_select = "b_id,bc.*
		";
		$sql = "select {$v_select} from {$from} bc
		JOIN {$from_data} b on(b_id={$v_b_id} and b.b_idx=bc.b_idx)
		where bc_isdel=0  and b_isdel=0  and bc_insert_date >= {$v_b_insert_date}
		order by bc_idx desc
		limit {$limit} 
		";
		//echo $sql;
		return $sql;
	}
	public function last_bbs_comment_rowss($tbl_b_id,$limit,$day){
		$sqls = array();
		foreach($tbl_b_id as $v){
			$sqls[] = '('.$this->last_bbs_comment_sql($v[0],$v[1],$limit,$day,$v[2]).')';
		}
		$sql = implode("\nUNION ALL\n",$sqls);
		//echo $sql;
		$query = $this->db->query($sql);
		$rowss = array();
		$rows = $query->result_array();
		foreach($rows as $row){
			$query->next_row();
			if(!isset($rowss[$row['b_id']])){
				$rowss[$row['b_id']] = array();
			}
			$rowss[$row['b_id']][]=$row;
		}
		return $rowss;
	}
	
}