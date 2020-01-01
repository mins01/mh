<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mh_cache extends MX_Controller {

	public function __construct($bbs_conf=array())
	{
		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		$this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//print_r($this->cache);
	}
	public function get($key){
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->cache->get($key);
		if($r!==false){
			header('X-Cache-Cached: '.$key);
		}else{
			header('X-Cache-No-Cached: '.$key);
		}
		return $r;
	}
	public function save($key,$val,$ttl=60){
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->cache->save($key,$val,$ttl);
		if(!$r){
			header('X-Cache-No-Saved: '.$key);
		}else{
			header('X-Cache-Saved: '.$key);
		}
		return $r;
	}
	public function delete($key){
		$key = preg_replace('/[^\w\s\.]/','_',$key);
		$r = $this->cache->delete($key);
		if(!$r){
			header('X-Cache-No-Deleted: '.$key);
		}else{
			header('X-Cache-Deleted: '.$key);
		}
		return $r;
	}
}
