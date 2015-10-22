<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

/* load the MX_Loader class */
require APPPATH."third_party/MX/Loader.php";

class MY_Loader extends MX_Loader {
    // function __construct() {
    //     parent::__construct();
    //     $this->_ci_view_paths = array(SKIN_PATH => TRUE);
    // }
    
    // public function view($view, $vars = array(), $return = FALSE) {
    //     if ( substr($view, -5) !== '.html' && substr($view, -5) !== '.php' ){
    //         $view .= '.html';
    //     }

    //     return $this->_ci_load(array('_ci_view' => $view, '_ci_vars' => $this->_ci_object_to_array($vars), '_ci_return' => $return));
    // }

    // .html 적용되게 하면 로더연결시 깨짐.
}