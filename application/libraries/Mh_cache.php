<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if(!defined('USE_MH_CACHE')){
  define('USE_MH_CACHE',true);
}

class Mh_cache {
	private $act_count = 0;
  private $CI = 0;
  public $use_cache = false; //사용유무
	public function __construct()
	{
    $this->CI =& get_instance();
		$this->CI->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
    $this->use_cache = USE_MH_CACHE;
		//print_r($this->CI->cache);
	}
	public function get($key){
		if(!$this->use_cache){return null;}
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->CI->cache->get($key);
		if($r!==false){
			header("X-Cache-{$this->act_count}: [Cached] {$key}");
		}else{
			header("X-Cache-{$this->act_count}: [No-Cached] {$key}");
		}
		$this->act_count++;
		return $r;
	}
	public function save($key,$val,$ttl=60){
		if(!$this->use_cache){return null;}
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->CI->cache->save($key,$val,$ttl);
		if(!$r){
			header("X-Cache-{$this->act_count}: [No-Saved] {$key} ({$ttl})");
		}else{
			header("X-Cache-{$this->act_count}: [Saved] {$key} ({$ttl})");
		}
		$this->act_count++;
		return $r;
	}
	public function delete($key){
		if(!$this->use_cache){return null;}
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->CI->cache->delete($key);
		if(!$r){
			header("X-Cache-{$this->act_count}: [No-Deleted] {$key}");
		}else{
			header("X-Cache-{$this->act_count}: [Deleted] {$key}");
		}
		$this->act_count++;
		return $r;
	}
	public function clean(){
    if(!$this->use_cache){return null;}
		$r = $this->CI->cache->clean();
		return $r;
	}
	public function cache_info(){
		return $this->CI->cache->cache_info();
	}
}
