<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//-- 게시판 모델

class Bbs_file_model extends CI_Model {
	private $base_url = '';
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
	public function set_base_url($base_url){
		$this->base_url = $base_url;
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
		$this->save_file_dir = str_replace('\\','/',realpath($this->file_dir.'/'.$this->bm_row['bm_table']));

	}

	public function select_by_bf_idx($bf_idx){
		$select = "bbsf.*
			, IF(bf_type LIKE 'external/%',1,0) AS is_external
			, IF(bf_type LIKE '%image%',1, IF(bf_name REGEXP '.(gif|jpg|jpeg|jpe|png)$',1,0) ) AS is_image
			, CONCAT('{$this->save_file_dir}/',FLOOR(b_idx/1000),'/',b_idx,'/',bf_save) AS save_file
		";
		$bf_row = $this->db->select($select)->from($this->tbl.'  bbsf')->where('bf_isdel',0)->where('bf_idx',(int)$bf_idx)->get()->row_array();
		// $this->extends_bf_row($bf_row); //더이상 필요 없음, 쿼리에서 처리함.
		return $bf_row;
	}

	public function select_for_list($b_idx){
		$select = "bbsf.*
			, IF(bf_type LIKE 'external/%',1,0) AS is_external
			, IF(bf_type LIKE '%image%',1, IF(bf_name REGEXP '.(gif|jpg|jpeg|jpe|png)$',1,0) ) AS is_image
			, CONCAT('{$this->save_file_dir}/',FLOOR(b_idx/1000),'/',b_idx,'/',bf_save) AS save_file
			, CONCAT('{$this->base_url}/download/{$b_idx}?bf_idx=',bf_idx) AS download_url
			, CONCAT('{$this->base_url}/download/{$b_idx}?bf_idx=',bf_idx,'&inline=1') AS view_url
			, IF(bf_type LIKE 'external/%',bf_save,IF(bf_type LIKE '%image%',concat('{$this->base_url}/thumbnail/{$b_idx}?bf_idx=',bf_idx,'&inline=1'),'')) AS thumbnail_url
			, IF(bf_type LIKE 'external/%',1,0) AS is_external
		";

		$bf_rows = $this->db->select($select)->from($this->tbl.'  bbsf')->where('bf_isdel',0)->where('b_idx',(int)$b_idx)->get()->result_array();
		// $this->extends_bf_rows($bf_rows); //더이상 필요 없음, 쿼리에서 처리함.
		return $bf_rows;
	}

	// public function extends_bf_rows(& $bf_rows){ //더이상 필요 없음, 쿼리에서 처리함.
	// 	if(isset($bf_rows[0])){
	// 		$save_dir = $this->extends_save_dir($bf_rows[0]['b_idx']);
	// 	}
	// 	foreach($bf_rows as & $bf_row){
	// 		$bf_row['save_file'] = $save_dir.'/'.$bf_row['bf_save'];
	// 		$this->_extends_bf_row($bf_row);
	// 	}
	// }
	// public function extends_bf_row(& $bf_row){ //더이상 필요 없음, 쿼리에서 처리함.
	// 	if(isset($bf_row['b_idx'])){
	// 		$save_dir = $this->extends_save_dir($bf_row['b_idx']);
	// 		$bf_row['save_file'] = $save_dir.'/'.$bf_row['bf_save'];
	// 		// $this->_extends_bf_row($bf_row);
	// 	}
	//
	// }
	//
	// private function _extends_bf_row(& $bf_row){ //더이상 필요 없음, 쿼리에서 처리함.
	//
	// 	$bf_row['is_external'] = (strpos($bf_row['bf_type'],'external')===0);
	// 	if($bf_row['is_external']){
	// 		$bf_row['is_image'] = $bf_row['bf_type']=='external/image';
	// 	}else{
	// 		$bf_row['is_image'] = preg_match('/\.(gif|jpg|jpeg|jpe|png)$/i',$bf_row['bf_name']);
	// 	}
	// }

	public function extends_save_dir($b_idx){
		return $this->save_file_dir.'/'.(floor($b_idx/1000)).'/'.$b_idx;
	}
	public function create_save_dir($b_idx){
		$save_dir = $this->extends_save_dir($b_idx);
		if(!file_exists($save_dir) && !mkdir($save_dir,0777,true)){
			$this->msg ='MKDIR FAIL : '.$save_dir;
			return false;
		}
		chmod($save_dir,0777);
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
		if(!is_dir($dir)){return;}
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
	//-- 하나의 이미지 첨부파일을 대표 이미지로 설정
	public function set_represent_by_b_idx($b_idx){
		$this->db->from($this->tbl)->where('bf_isdel',0)->where('b_idx',$b_idx)->set('bf_represent',0)->update();
		$this->db->from($this->tbl)->where('bf_isdel',0)->where('b_idx',$b_idx)->set('bf_represent',1)->like('bf_type','image','both')->limit(1)->update();
		return true;
	}
	//-- 지정 첨부파일을 대표 이미지로 설정.
	public function set_represent_by_b_idx_bf_idx($b_idx,$bf_idx){
		$this->db->from($this->tbl)->where('bf_isdel',0)->where('b_idx',$b_idx)->set('bf_represent',0)->update();
		$this->db->from($this->tbl)->where('bf_isdel',0)->where('b_idx',$b_idx)->where('bf_idx',$bf_idx)->set('bf_represent',1)->update();
		return true;
	}

	//-- 외부 url 설정
	public function insert_external_url($b_idx,$ext_urls,$ext_url_types){
		$rlt = array();
		$this->msg = '';

		foreach($ext_urls as $k => $ext_url){
			if(!isset($ext_url[0])){continue;}
			if(!isset($ext_url_types[$k][0])){continue;}
			if($ext_url_types[$k]=='attach/dataurl'){
				$save_dir = $this->create_save_dir($b_idx);
				$ext_url_type = $ext_url_types[$k];
				$bf_name = '';
				$bf_size = 0;
				$bf_type = '';
				$bf_save = $this->save_data_url($save_dir,$ext_url,$bf_name,$bf_type,$bf_size);
				if(!$bf_save){
					continue;
				}
			}else{
				$ext_url_type = $ext_url_types[$k];
				// $bf_name = basename($ext_url);
				$bf_name = $ext_url_type=='external/image'?'외부이미지':'외부링크';
				$bf_size = 0;
				$bf_type = $ext_url_type;
				$bf_save = $ext_url;
			}

			// $bf_name = $ext_url_type;
			$vals = array(
				'b_idx' => $b_idx,
				'bf_save' => $bf_save,
				'bf_name' => $bf_name,
				'bf_size' => $bf_size,
				'bf_type' => $bf_type,
			);
			$r = $this->insert_bf_row($vals);
			$rlt[]=$vals['bf_save'].' : '.($r?'SUCCESS':'FAIL').' : '.$this->msg;
		}
		return $rlt;
	}
	public function save_data_url($save_dir,$dataurl , &$bf_name , &$bf_type  , &$bf_size){
		$pos = strpos($dataurl,'base64,')+7;
		if($pos===false){
			return false;
		}
		// echo $pos;
		$header = substr($dataurl,0,$pos);
		$bf_type = preg_replace('|^.*:(.*/.*);.*|','$1',$header);
		if(!isset($bf_type[0])){
			return false;
		}
		// echo '======='.$bf_type.'-------<br>';
		$body = base64_decode(substr($dataurl,$pos));
		if($body === false){
			return false;
		}
		$bf_size = strlen($body);
		$exp = preg_replace('|^.*/|','',$bf_type);
		$bf_save = md5(microtime(true).rand(0,100000).$bf_size).'.'.$exp;
		$save_path = $save_dir.'/'.$bf_save;
		$r = file_put_contents($save_path,$body);
		if($r === false){
			return false;
		}
		$bf_name = 'du_'.date('YmdHis').'_'.rand(100,999).'.'.$exp;;
		return $bf_save;
	}

	//-- 업로드
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

	//-- 업로드
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

	//== 이미지 리사이즈 출력
	public function echo_image_resize($filePath,$new_width=200){
		header('X-Resized: 1');
		list($width, $height) = getimagesize($filePath);
		//$new_width = 200;
		$new_height = floor($height * $new_width/$width);
		$pif = pathinfo ($filePath);
		$image = null;
		switch(strtolower($pif['extension'])){
			case 'jpg':
			case 'jpeg':$image = imagecreatefromjpeg($filePath); break;
			case 'gif':$image = imagecreatefromgif($filePath); break;
			case 'png':$image = imagecreatefrompng($filePath); break;
		}

		if ($image) {
			// Content type
			// Get new dimensions
			$image_p = imagecreatetruecolor($new_width, $new_height);
			// Resample
			imagecopyresampled($image_p, $image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
			// Output
			imagejpeg($image_p, null, 70);
			imagedestroy($image_p);
		}else{
			header('X-Resized: 0');
			$this->msg = '이미지 생성 오류';
			return false;
		}
		return true;
	}

	//= 썸네일 출력
	public function thumbnail_by_bf_row($bf_row,$inline=false,$resume=true,$debug=0){
		if($bf_row['is_external']){
			header('Location: '.$bf_row['bf_save'],true,302);
			exit;
		}
		if(!is_file($bf_row['save_file'])){
			$this->msg = '서버에 파일이 없습니다.';
			return false;
		}


		list($width, $height) = getimagesize($bf_row['save_file']);
		if(!$width){
			$this->msg = '서버에 파일이 없습니다.';
			return false;
		}


		//$config = array();
		//$config['image_library'] = 'gd2';
		//$config['source_image']	= $bf_row['save_file'];
		//$config['create_thumb'] = TRUE;
		// //$config['maintain_ratio'] = TRUE;
		//$config['master_dim'] = 'width';
		//$config['width']	= 200;
		// //$config['height']	= 200;
		// //$config['thumb_marker']	= '_thumb';
		//$config['dynamic_output']	= true;

		// $this->load->library('image_lib',$config); //사용안함. 이미지 캐싱 처리에 문제.(304처리 불가!)
		// if(!$this->image_lib->initialize($config)){
			// echo $this->image_lib->display_errors();
			// return false;
		// }
		header('X-Thumbnail: 1');
		//-- 웹 캐시 설정
		$this->load->library('mheader');
		$sec = 60*60*24; //하루. 더 길게해도 문제 없다.(파일 수정 기능이 없기 때문에)
		$etag = date('Hi').ceil(date('s')/$sec);

		$pif = pathinfo ($bf_row['bf_name']);
		$fileName = 'thumb_'.$pif['filename'].'.jpg'; //jpg로 고정
		if(!$inline) header("Content-Disposition: attachment; filename=\"{$fileName}\" "); //첨부파일로 처리 : 무조건 다운로드
		else header("Content-Disposition: inline; filename=\"{$fileName}\" "); //가능하다면 직접 보여줌
		header('Content-type: image/jpeg');

		//$msgs = array();
		if(false && MHeader::etag($etag)){ //etag는 사용하지 말자.
		//$msgs[] = 'etag 동작';//실제 출력되지 않는다.(304 발생이 되기 때문에)
			exit('etag 동작');
		}else if(MHeader::lastModified($sec)){
		//$msgs[] = 'lastModified 동작'; //실제 출력되지 않는다.(304 발생이 되기 때문에)
			exit('lastModified 동작');
		}
		MHeader::expires($sec);



		// if($this->image_lib->resize()){
			// return true;
		// }
		if($this->echo_image_resize($bf_row['save_file'],200)){
			return true;
		}else{

			$this->msg = $this->image_lib->display_errors();
			return false;
		}

	}
	//= 첨부파일 출력
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

	}

	//다운로드 수 증가. (하루 한번 증가 시킴)
	public function hitup($b_idx,$ip,$m_idx=0){
		$tbl = $this->bm_row['tbl_hit'];
		$bh_parent_table ="'file'";
		$bh_parent_idx = $this->db->escape((int)$b_idx);
		$bh_m_idx = $this->db->escape((int)$m_idx);
		$v_ip = $this->db->escape($ip);
		$bh_ip_number = "inet_aton({$v_ip})";
		$bh_insert_date ='now()';
		$bh_update_date ='now()';
		$bh_hit_cnt = 1;

		$v_bh_update_date = $this->db->escape(date('Y-m-d 00:00:00'));

		$sql = "INSERT INTO {$tbl} (bh_parent_table,bh_parent_idx,bh_m_idx,bh_ip_number,bh_insert_date,bh_update_date,bh_hit_cnt)
		values({$bh_parent_table},{$bh_parent_idx},{$bh_m_idx},{$bh_ip_number},{$bh_insert_date},{$bh_update_date},{$bh_hit_cnt})
		ON DUPLICATE KEY UPDATE
			bh_hit_cnt = IF(bh_update_date < {$v_bh_update_date},bh_hit_cnt+1,bh_hit_cnt),
			bh_update_date = IF(bh_update_date < {$v_bh_update_date},now(),bh_update_date)
		";
		$this->db->query($sql);
		return $this->db->affected_rows();
	}
}
