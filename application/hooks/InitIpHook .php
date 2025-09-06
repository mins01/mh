<?php
class InitIpHook {
    public function setInitialIp() {
        $CI =& get_instance();
        $CI->load->library('session');

        if (!$CI->session->userdata('ip_address_init')) {
            $CI->session->set_userdata('ip_address_init', $CI->input->ip_address());
        }
    }
}