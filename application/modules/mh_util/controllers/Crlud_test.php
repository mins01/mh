<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 간단한 DB 테이블 관리 툴.
 */
class Crlud_test extends MX_Controller {
	// private $crlud_m = null;
	public $from = 'mcv_body';
	public $read_select = 'gr_nick,gr_game,gr_date';
	// private $show_fields = array('gr_nick','gr_game','gr_date');
	public $show_fields = array();
	public $modul_path = 'mh_util';
	// public $return_view = true;
	public function __construct()
	{
		
		
		$this->config->load('conf_front'); // 프론트 사이트 설정

		$this->load->module('mh/common');
		$this->load->module('mh/layout');
		// $this->config->set_item('layout_disable',true);
		$this->config->set_item('layout_hide',false);
		
		$this->load->module($this->modul_path.'/crlud');
		
		//$this->load->driver('cache', array('adapter' => 'apc', 'backup' => 'file'));
		// $this->load->driver('cache', array('adapter' => 'file', 'backup' => 'file'));
		//print_r($this->cache);
	}
	
	public function index(){
		$this->crlud->form = 'mcv_body';
		$this->crlud->read_select = 'gr_nick,gr_game,gr_date';
		// $this->crlud->show_fields = array('gr_nick','gr_game','gr_date');
		$this->crlud->show_fields = array();
		$this->crlud->modul_path = 'mh_util';
		$this->load->view($this->modul_path.'/crlud_test',array('view'=>$this->crlud->action(true)));
	}
	
}






