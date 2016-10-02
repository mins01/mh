<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sitemap extends MX_Controller {
	
	public function __construct($conf=array())
	{
		$this->load->library('Array2XML');
	}
	public function index(){

	}
	// front 컨트롤에서 접근할 경우.
	public function index_as_front($conf,$param){
		$base_url = $conf['base_url'];
		$view = $conf['menu']['mn_arg1'];
		$this->action($conf,$param);
	}
	
	public function action($conf,$param){
		// $this->config->set_item('layout_head_contents','<script>console.log("xxx");</script>');
		$this->config->set_item('layout_hide',false);
		$this->config->set_item('layout_title','');
		//$this->load->view('mh/main/main',array('conf'=>$conf));
		switch($param){
			case 'google':
			default:
			return $this->google($conf,$param);
			break;
		}
		
	}
	public function google($conf,$param){
		$this->config->set_item('layout_disable',true);
		$menu_rows = $this->config->item('menu_rows');
		
		$rr = array(
			'@attributes'=>array(
				'xmlns'=>'http://www.sitemaps.org/schemas/sitemap/0.9',
			),
			'url'=>array(),
		);
		// print_r($menu_rows);
		$lastmod = date('Y-m-d');
		foreach($menu_rows[0]['child'] as $row){
			if($row['mn_hide'] && !$row['mn_use']){continue;}
			// print_r($row);
			$rr['url'][]=array(
			'loc'=>array('@value'=>$row['url'],
						'@attributes'=>array(
							'mn_text'=>$row['mn_text'],
						),),
			'lastmod'=>$lastmod,
			'changefreq'=>'monthly',
			);
		}
		// print_r($rr);
		Array2XML::init('1.0','UTF-8');
		$xml = Array2XML::createXML('urlset', $rr);
		header('Content-Type: application/xml; charset=UTF-8');
		echo $xml->saveXML();
		
	}


}






