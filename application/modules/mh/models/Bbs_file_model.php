<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Bbs_file_model extends CI_Model {
	public $bm_row = array();
	public $error = '';
	private $tbl = '';
	public $msg = '';
	public $msgs = '';
	private $file_dir = '';
	private $save_file_dir = '';
	public function __construct()
	{
		// Call the CI_Model constructor
		parent::__construct();
		$this->config->load('bbs');
		$conf_bbs = $this->config->item('bbs');
		$this->file_dir = $conf_bbs['file_dir'];
		
	}
	public function hash($str){
		return md5($str);
	}
	public function set_bm_row($bm_row){
		$this->bm_row = $bm_row;
		//-- 테이블
		if(!isset($this->bm_row['bm_table'])){
			$this->error = '게시판 테이블 정보가 없습니다.';
			return false;
		}
		$this->tbl = DB_PREFIX.'bbs_'.$this->bm_row['bm_table'].'_file';
		$this->save_file_dir = realpath($this->file_dir.'/'.$this->bm_row['bm_table']);
	}
	
	public function select_for_list($b_idx){
		return $this->db->from($this->tbl)->where('bf_isdel',0)->where('b_idx',(int)$b_idx)
		->get()->result_array();
	}
	public function extends_save_dir($b_idx){
		return $this->save_file_dir.'/'.(floor($b_idx/1000)).'/'.$b_idx;
	}
	public function create_save_dir($b_idx){
		$save_dir = $this->extends_save_dir($b_idx);
		if(!file_exists($save_dir) && !mkdir($save_dir,0777,true)){
			$this->msg ='MKDIR FAIL : '.$save_dir;
			return false;
		}
		return realpath($save_dir);
	}
	public function insert_bf_row($sets){
		unset($sets['bf_idx']);
		$this->db->from($this->tbl)
		->set($sets)
		->set('bf_insert_date','now()',false)
		->set('bf_update_date','now()',false)->insert();
		return $this->db->insert_id();
	}
	//-- 삭제 (DB 삭제에 파일도 삭제)
	private function _delete_bf_rows($bf_rows){
		$arr = array();
		$bf_idxs2 = array();
		foreach($bf_rows as $k=>$bf_row){
			$r = @$this->delete_file($bf_row['b_idx'],$bf_row['bf_save']); 
			$arr[$bf_row['bf_idx']] = ($r?'SUCCESS':'FAIL').' : '.$this->msg;
			$bf_idxs2[] = $bf_row['bf_idx'];
		}
		if(count($bf_idxs2)>0){
			$this->db->from($this->tbl)->where('bf_isdel',0)->where_in('bf_idx',$bf_idxs2)->set('bf_isdel',1)->update();
		}
		
		return $arr;
	}
	//-- b_idx,bf_idx
	public function delete_bf_rows_by_b_idx_bf_idxs($b_idx,$bf_idxs){
		if(!is_array($bf_idxs)){
			$bf_idxs = array((int)$bf_idxs);
		}
		$bf_rows = $this->db->from($this->tbl)->where('bf_isdel',0)->where('b_idx',(int)$b_idx)->where_in('bf_idx',$bf_idxs)->get()->result_array();
		//print_r($bf_rows);
		return $this->_delete_bf_rows($bf_rows);
	}
	//-- 폴더 강제 삭제
	public static function delTree($dir) { 
   $files = array_diff(scandir($dir), array('.','..')); 
    foreach ($files as $file) { 
      (is_dir("{$dir}/{$file}")) ? self::delTree("{$dir}/{$file}") : unlink("{$dir}/{$file}"); 
    } 
    return rmdir($dir); 
  }
	//b_idx 관련 파일 모두 삭제!
	public function delete_bf_rows_by_b_idx($b_idx){
		$this->db->from($this->tbl)->where('bf_isdel',0)->where_in('b_idx',(int)$b_idx)->set('bf_isdel',1)->update();
		
		$save_dir = $this->extends_save_dir($b_idx);
		self::delTree($save_dir);
		return true;
	}
	public function delete_file($b_idx,$bf_save){
		$save_dir = $this->extends_save_dir($b_idx);
		$save_file = $save_dir.'/'.$bf_save;
		if(!unlink($save_file)){
			$this->msg = 'FAIL UNLINK FILE : '.$save_file;
			return false;
		}
		return true;
	}
	public function upload_files($b_idx,$files){
		$rlt = array();
		for($i=0,$m=count($files['name']);$i<$m;$i++){
			$file = array(
				'name'=>$files['name'][$i],
				'type'=>$files['type'][$i],
				'tmp_name'=>$files['tmp_name'][$i],
				'error'=>$files['error'][$i],
				'size'=>$files['size'][$i],
			);
			
			$r = $this->upload_file($b_idx,$file);
			$rlt[]=$file['name'].' : '.($r?'SUCCESS':'FAIL').' : '.$this->msg;
		}
		return $rlt;
	}
	public function upload_file($b_idx,$file){
		$this->msg = '';
		switch($file['error']){
			case 0: //UPLOAD_ERR_OK
			break;
			case 1: //UPLOAD_ERR_INI_SIZE
				$this->msg = 'UPLOAD_ERR_INI_SIZE';
				return false;
			break;
			case 2: //UPLOAD_ERR_FORM_SIZE
				$this->msg = 'UPLOAD_ERR_INI_SIZE';
				return false;
			break;
			case 3: //UPLOAD_ERR_PARTIAL
				$this->msg = 'UPLOAD_ERR_PARTIAL';
				return false;
			break;
			case 4: //UPLOAD_ERR_NO_FILE
				$this->msg = 'UPLOAD_ERR_NO_FILE';
				return true; // not ERROR!
			break;
			case 5: //????
				$this->msg = '????';
				return false;
			break;
			case 6: //UPLOAD_ERR_NO_TMP_DIR
				$this->msg = 'UPLOAD_ERR_NO_TMP_DIR';
				return false;
			break;
			case 7: //UPLOAD_ERR_CANT_WRITE
				$this->msg = 'UPLOAD_ERR_CANT_WRITE';
				return false;
			break;
			case 8: //UPLOAD_ERR_EXTENSION
				$this->msg = 'UPLOAD_ERR_EXTENSION';
				return false;
			break;
		}
		
		if($file['size']==0){	
			$this->msg = 'EMPTY FILE';
			return false;
		}
		$save_dir = $this->create_save_dir($b_idx);
		$pti = pathinfo($file['name']);
		if(!isset($pti['extension'][0])){
			$this->msg = 'NO EXTENSION';
			return false;
		}
		$pti['extension'] = strtolower($pti['extension']);
			
		$vals = array(
			'b_idx' => $b_idx,
			'bf_save' => time().'_'.md5($pti['filename']).'.'.$pti['extension'],
			'bf_name' => $file['name'],
			'bf_size' => $file['size'],
			'bf_type' => $file['type'],
		);
		
		$save_file = $save_dir.'/'.$vals['bf_save'];
		if(!move_uploaded_file($file['tmp_name'], $save_file)){
			$this->msg = 'UPLOAD FAIL';
			return false;
		}
		chmod($save_file,0777);
		return $this->insert_bf_row($vals);
	}
}