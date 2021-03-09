<?php
defined('BASEPATH') OR exit('No direct script access allowed');

//== 관리자 로그 라이브러리

require_once(dirname(__FILE__).'/Mh_log.php');

class Mh_admin_log extends Mh_log{
	// protected $table = 'mh_admin_log';
	public function __construct()
	{
		parent::__construct();
		$this->table = DB_PREFIX.'admin_log';
	}
}
