<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mh_cache extends MX_Controller {
	private $act_count = 0;
	public function __construct($bbs_conf=array())
	{
		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//print_r($this->cache);
	}
	public function get($key){
		if(!USE_MH_CACHE){return null;}
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->cache->get($key);
		if($r!==false){
			header("X-Cache-Cached-{$this->act_count}: {$key}");
		}else{
			header("X-Cache-No-Cached-{$this->act_count}: {$key}");
		}
		$this->act_count++;
		return $r;
	}
	public function save($key,$val,$ttl=60){
		if(!USE_MH_CACHE){return null;}
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->cache->save($key,$val,$ttl);
		if(!$r){
			header("X-Cache-No-Saved-{$this->act_count}: {$key} ({$ttl})");
		}else{
			header("X-Cache-Saved-{$this->act_count}: {$key} ({$ttl})");
		}
		$this->act_count++;
		return $r;
	}
	public function delete($key){
		if(!USE_MH_CACHE){return null;}
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->cache->delete($key);
		if(!$r){
			header("X-Cache-No-Deleted-{$this->act_count}: {$key}");
		}else{
			header("X-Cache-Deleted-{$this->act_count}: {$key}");
		}
		$this->act_count++;
		return $r;
	}
	public function clean(){
		$r = $this->cache->clean();
		return $r;
	}
	public function cache_info(){
		return $this->cache->cache_info();
	}
}
