<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!defined('USE_MH_CACHE')){
  define('USE_MH_CACHE',true);
}

class Mh_cache {
	private $act_count = 0;
  private $CI = null;
  public $use_cache = false; //사용유무
  public $use_log_header = false; //해더로 로그 출력
  public $auto_readjust_time = 3600; //자동 정리 시간
  private $auto_readjust_meta = null;
  public $file_cache_path = '';
  public $use_file_driver = false;
	public function __construct()
	{
    $this->CI =& get_instance();
		$this->CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
    $this->use_cache = USE_MH_CACHE;
		//print_r($this->CI->cache);
    $tmp_path = $this->CI->config->item('cache_path');
    $this->file_cache_path = ($tmp_path === '') ? APPPATH.'cache/' : $tmp_path;
    $this->use_file_driver = true;
	}
  public function header($msg){
    if($this->use_log_header){
      @header($msg);
    }
  }
	public function get($key){
		if(!$this->use_cache){return null;}
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->CI->cache->get($key);
		if($r!==false){
			$this->header("X-Cache-{$this->act_count}: [Cached] {$key}");
		}else{
			$this->header("X-Cache-{$this->act_count}: [No-Cached] {$key}");
		}
		$this->act_count++;
		return $r;
	}
	public function save($key,$val,$ttl=60){
		if(!$this->use_cache){return null;}
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = @$this->CI->cache->save($key,$val,$ttl);
		if(!$r){
			$this->header("X-Cache-{$this->act_count}: [No-Saved] {$key} ({$ttl})");
		}else{
			$this->header("X-Cache-{$this->act_count}: [Saved] {$key} ({$ttl})");
		}
		$this->act_count++;
    $this->chmod0777($key);
    $this->auto_readjust();
		return $r;
	}
  public function chmod0777($key){
    if($this->use_file_driver){
      $path = $this->file_cache_path.'/'.$key;
      if(is_file($path)){
        return @chmod($path,0777);
      }
    }
    return;
  }
	public function delete($key){
		if(!$this->use_cache){return null;}
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->CI->cache->delete($key);
		if(!$r){
			$this->header("X-Cache-{$this->act_count}: [No-Deleted] {$key}");
		}else{
			$this->header("X-Cache-{$this->act_count}: [Deleted] {$key}");
		}
		$this->act_count++;
		return $r;
	}
	public function clean(){
    if(!$this->use_cache){return null;}
		$r = $this->CI->cache->clean();
		return $r;
	}
	// public function cache_info(){
  //   // return $this->cache_detail_info();
	// 	return $this->CI->cache->cache_info();
	// }
  public function cache_info($detail=false){
		$rows = $this->CI->cache->cache_info();
    $tm = time();
    if($detail){
      foreach($rows as & $r){
        $r = array_merge($r,$this->CI->cache->get_metadata($r['name']));
        $r['ttl'] = $r['expire'] - $r['mtime'];
        $r['expired'] = ($tm > $r['expire']);
        unset($r);
      }
    }
    return $rows;
	}

  public function auto_readjust(){
    $fn = "__auto_readjust__";
    if($this->auto_readjust_meta == null){
      $this->auto_readjust_meta = $this->CI->cache->get_metadata($fn);
    }
    if($this->auto_readjust_meta == null){
      $this->CI->cache->save($fn,1,$this->auto_readjust_time);
      $this->chmod0777($fn);
      // exit('정리0');
    }else{
      if($this->auto_readjust_meta['expire'] < time()){
        $this->readjust();
        $this->CI->cache->save($fn,1,$this->auto_readjust_time);
        $this->chmod0777($fn);
        // exit('정리');
      }
    }
  }
  public function readjust(){
    $rows = $this->cache_info(true);
    foreach($rows as & $r){
      // $this->CI->cache->get($r['name']);
      if($r['expired']){
        $this->CI->cache->delete($r['name']);
      }
    }
  }
}
