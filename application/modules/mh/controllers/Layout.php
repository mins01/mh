<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Layout extends MX_Controller {
	public $prefix_title = '';
	public $suffix_title = '';
	public $vars = array();

	public function __construct()
	{
		// print_r(func_get_args());
		// print_r($this->config);
		$this->prefix_title = $this->config->item('prefix_title','layout');
		$this->suffix_title = $this->config->item('suffix_title','layout');
		$this->load->helper('url');

	}

	public function get_conf_from_config($reload=false){
		static $conf = array();
		if(!$reload && count( $conf)>0){
			// echo 'no-reload';
			return $conf;
		}

		$conf['view_head'] = $this->config->item('layout_view_head');
		$conf['view_tail'] = $this->config->item('layout_view_tail');
		$conf['menu_tree'] = $this->config->item('menu_tree');
		$conf['menu_rows'] = $this->config->item('menu_rows');
		$conf['menu'] = $this->config->item('menu');
		$conf['head_contents'] = $this->config->item('layout_head_contents');
		$conf['tail_contents'] = $this->config->item('layout_tail_contents');
		$conf['hide'] = $this->config->item('layout_hide');
		$conf['title'] = $this->config->item('layout_title');
		$conf['logedin'] = $this->config->item('layout_logedin');
		$conf['login_label'] = $this->common->get_login('m_nick');
		$conf['login_info']	=	 $this->common->get_login();
		$conf['use_banners'] = $this->config->item('layout_use_banners'); //배너 사용여부

		$conf['layout_keywords'] = $this->config->item('layout_keywords');
		$conf['layout_og_title'] = $this->config->item('layout_og_title');
		$conf['layout_og_description'] = $this->config->item('layout_og_description');
		$conf['layout_og_image'] = $this->config->item('layout_og_image');
		$conf['layout_og_image_width'] = $this->config->item('layout_og_image_width');
		$conf['layout_og_image_height'] = $this->config->item('layout_og_image_height');
		$conf['layout_og_site_name'] = $this->config->item('layout_og_site_name');
		$conf['layout_og_type'] = $this->config->item('layout_og_type');


		if(!isset($conf['head_contents'])) $conf['head_contents'] = '';
		if(!isset($conf['tail_contents'])) $conf['tail_contents'] = '';
		if(!isset($conf['hide'])) $conf['hide'] = false;
		if(!isset($conf['title'])) $conf['title'] = '';
		$conf['top_html'] = '';
		$current_menu = $this->config->item('current_menu');
		if(isset($current_menu)){
			$conf['head_contents'].="\n".$current_menu['mn_head_contents'];
			$conf['top_html'] = $current_menu['mn_top_html'];
		}
		$conf['current_url'] = current_url();
		if(preg_match('|/list|', $conf['current_url'])){
			$conf['canonical_url'] = $conf['current_url'];
		}else{
			$conf['canonical_url'] = preg_replace('/\?.*$/', '', $conf['current_url']);
		}

		return $conf;
	}

	public function get_output($__content__){
		return $this->layout_head().$__content__.$this->layout_tail();
	}

	public function layout_head($conf=array()){
		$conf = array_merge($this->get_conf_from_config(),$conf);

		if(!isset($conf['title'][0])){
			$conf['title'] = $this->prefix_title . $conf['menu']['mn_text']. $this->suffix_title;
		}else{
			$conf['title'] = $this->prefix_title . $conf['title']. $this->suffix_title;
		}


		$conf['seo_contents'] = $this->load->view('mh/layout/seo',$conf,true);
		$conf['head_banners'] = $this->load->view('mh/layout/head_banners',$conf,true);

		return $this->load->view('mh/layout/'.$conf['view_head'],$conf,true);
	}
	public function layout_tail($conf = array()){
		$conf = array_merge($this->get_conf_from_config(),$conf);

		return $this->load->view('mh/layout/'.$conf['view_tail'],$conf,true);
	}




	public function head($conf = array()){
		print_r(debug_backtrace());
		exit('x');

		echo $this->layout_head($conf);
	}
	public function tail($conf = array()){
		echo $this->layout_tail($conf);
	}

}
