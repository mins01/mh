<?php
class InitIpHook {
    public function setInitialIp() {
        $CI =& get_instance();
        $CI->load->library('session');
        if (!$CI->session->userdata('ip_address_init')) {
            $CI->session->set_userdata('ip_address_init', $CI->input->ip_address());
            $CI->mh_log->info(array(
              'title'=>__METHOD__,
              'msg'=>'첫방문',
              'result'=>'성공',
              'ip_address_init'=>$CI->input->ip_address(),
            ));
        }
    }
}