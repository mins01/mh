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

	public function select_by_bf_idx($bf_idx){
		$bf_row = $this->db->from($this->tbl)->where('bf_isdel',0)->where('bf_idx',(int)$bf_idx)->get()->row_array();
		$this->extends_bf_row($bf_row);
		return $bf_row;
	}
	
	public function select_for_list($b_idx){
		$bf_rows = $this->db->from($this->tbl)->where('bf_isdel',0)->where('b_idx',(int)$b_idx)->get()->result_array();
		$this->extends_bf_rows($bf_rows);
		return $bf_rows;
	}

	public function extends_bf_rows(& $bf_rows){
		if(isset($bf_rows[0])){
			$save_dir = $this->extends_save_dir($bf_rows[0]['b_idx']);
		}
		foreach($bf_rows as & $bf_row){
			$bf_row['save_file'] = $save_dir.'/'.$bf_row['bf_save'];
			$this->_extends_bf_row($bf_row);
		}
	}	
	public function extends_bf_row(& $bf_row){
		if(isset($bf_row['b_idx'])){
			$save_dir = $this->extends_save_dir($bf_row['b_idx']);
			$bf_row['save_file'] = $save_dir.'/'.$bf_row['bf_save'];
			$this->_extends_bf_row($bf_row);
		}
		
	}
	private function _extends_bf_row(& $bf_row){
		$bf_row['is_image'] = preg_match('/\.(gif|jpg|jpeg|jpe|png)$/i',$bf_row['bf_name']);
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
	
	public function download_by_bf_row($bf_row,$inline=false,$resume=true,$debug=0){

		if(!is_file($bf_row['save_file'])){
			$this->msg = '서버에 파일이 없습니다.';
			return false;
		}
		//echo $bf_row['save_file'];
		
		//==== 이어받기
		$seek_start = 0; 
		$seek_end = 0; 
		if(isset($_SERVER['HTTP_RANGE']) && $resume) { 
			$seek_range = substr($_SERVER['HTTP_RANGE'] , 6);        
			$range = explode( '-', $seek_range);        
			if($range[0] > 0) { $seek_start = intval($range[0]); }        
			if($range[1] > 0) { $seek_end  =  intval($range[1]); } 
		} 		

		//--- 정보정의
		$file_type = isset($bf_row['bf_type']{0})?$bf_row['bf_type']:'application/octet-stream'; //파일타입
		if($debug) $file_type = 'text/plain';
		$file_name = $bf_row['bf_name']; //파일이름
		$file_name = str_replace(array("'",'"',';',':','/','\\'),'_',$file_name);	//위험단어 변환
		$save_file = $bf_row['save_file'];
		$save_file_size = @filesize($save_file); //파일크기
		$save_file_size = bcsub(sprintf("%u", $save_file_size),sprintf("%u", $seek_start)); //2~4G 지원용 : 크기 잘못 알아올 수 있음
		
		//--- 브라우저별 처리
		if (preg_match('/Opera(\/| )([0-9].[0-9]{1,2})/', $_SERVER['HTTP_USER_AGENT']))	$UserBrowser = "Opera";
		elseif (strpos($_SERVER['HTTP_USER_AGENT'],'Chrome'))	$UserBrowser = "Chrome";
		elseif (preg_match('/MSIE ([0-9].[0-9]{1,2})/', $_SERVER['HTTP_USER_AGENT']))	$UserBrowser = "IE";
		elseif (strpos($_SERVER['HTTP_USER_AGENT'],'Safari')!==false)	$UserBrowser = "Safari";
		elseif (strpos($_SERVER['HTTP_USER_AGENT'],'Firefox')!==false)	$UserBrowser = "Firefox";
		else	$UserBrowser = '';
		
		//MSIE,Safari는 UTF-8로 된 파일이름의 다운로드에 문제가 있다.
		//FF 에서는 UTF-8을 알아서 처리한다.
		if($UserBrowser=='Safari'){ 
			$file_name =  iconv('UTF-8','EUC-KR//IGNORE',$file_name);
		}else if($UserBrowser=='IE'){
			$file_name = rawurlencode($file_name);
		}
		$this->load->library('mheader');
		//--- 다운로드 출력
		$fp = fopen($save_file,'r+') ;
		if ($fp) {
			fseek($fp,$seek_start);
			//-- 웹 캐시 설정 			
			$sec = 60*60*24; //하루. 더 길게해도 문제 없다.(파일 수정 기능이 없기 때문에)
			$etag = date('Hi').ceil(date('s')/$sec);

			//$msgs = array();
			if(false && MHeader::etag($etag)){ //etag는 사용하지 말자.
			//$msgs[] = 'etag 동작';//실제 출력되지 않는다.(304 발생이 되기 때문에)
				exit('etag 동작');
			}else if(MHeader::lastModified($sec)){
			//$msgs[] = 'lastModified 동작'; //실제 출력되지 않는다.(304 발생이 되기 때문에)
				exit('lastModified 동작');
			}
			MHeader::expires($sec);


			//--- 다운로드 해더출력
			header("Content-type: {$file_type}");
			if(!$inline) header("Content-Disposition: attachment; filename=\"{$file_name}\" "); //첨부파일로 처리 : 무조건 다운로드
			else header("Content-Disposition: inline; filename=\"{$file_name}\" "); //가능하다면 직접 보여줌
			header("Content-Transfer-Encoding: binary"); 
			header("Content-Length: {$save_file_size}");			
			while (!feof($fp)) {
				set_time_limit(30);	//타임아웃 30씩 :30초가 지났는데도 문제가 있다면 파일읽어오는 데 문제가 있다!
				echo fgets($fp, 1024*1204*4); //메모리 제한 넘지 않는한 큰 숫자가 효과가 크다.
			}
			fclose($fp);
		}else{
			$this->msg =$row['bf_name'].' : 다운로드오류 : 파일오픈 오류입니다.';
			return false;
		}
		return true;
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		
		return true;
	}
}