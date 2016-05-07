<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- Sdgn 무기

class Sdgn_weapon_model extends CI_Model {
	
	public function select_weapons_by_unit_idx($unit_idx){
		$v_unit_idx = $this->db->escape((int)$unit_idx);
		$sql="SELECT svw.*,m.m_nick FROM sdgn_view_weapons svw
			LEFT JOIN mh_member m on(m.m_idx = svw.m_idx)
			WHERE unit_idx = {$v_unit_idx}
			ORDER BY `sw_is_change`,`sw_is_transform`,`sw_sort`";
		return $this->db->query($sql)->result_array();
		
	}
	public function select_assoc_weapons_by_rows($rows){
		$r_rows = array(
			
		);
		$r_rows[] = array(array(),array(),array());
		$r_rows[] = array(array(),array());
		
		foreach($rows as $row){
			if(!isset($r_rows[$row['sw_is_change']])){
				$r_rows[$row['sw_is_change']] = array();
			}
			if(!isset($r_rows[$row['sw_is_change']][$row['sw_is_transform']])){
				$r_rows[$row['sw_is_change']][$row['sw_is_transform']] = array();
			}
			$r_rows[$row['sw_is_change']][$row['sw_is_transform']][] = $row;
		}
		return $r_rows;
	}
	public function update_weapons_add_by_sw_key($post){
		if(!isset($post['sw_key'][0])) return false;
		 $fs = array(//'sw_key',
								'sw_desc',
								'sw_cost',
								'sw_atack_count',
								'sw_reload_type',
								'sw_bullet_count',
								'sw_reload_time',
								'sw_range',
								'sw_range_type',
								'sw_effect',
								'm_idx',
								//'swa_isdel',
								);
		
		
		$this->db->from('sdgn_weapons_add swa')
		->set('swa_isdel',0)
		->set('swa_update_date','now()',false)
		->where('sw_key',$post['sw_key']);
		foreach($fs as $f){
			if(!isset($post[$f])) continue;
			if(!isset($post[$f][0])){
				$v = 'NULL';
			}else{
				$v = $this->db->escape($post[$f]);
			}
			$this->db->set($f,$v,false);
			if($post['is_admin']){
				
			}else{
				$this->db->where("{$f} is null",null,false);
			}
		}
		$this->db->update();
		$affected_rows = $this->db->affected_rows();
		// echo $this->db->last_query();exit;
		$this->mh_log->info(array(
			'title'=>__METHOD__,
			'msg'=>'무기추가정보변경',
			'result'=>$affected_rows,
			'post'=>@$post,
		));
		return true;
	}
}