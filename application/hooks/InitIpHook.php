<?php
class InitIpHook {
    public function setInitialIp() {
        $CI =& get_instance();
        $CI->load->library('session');
        if (!$CI->session->userdata('ip_address_init')) {
            $cnt = $CI->mh_log->countFromLogIpForInit($_SERVER['REMOTE_ADDR'],date('Y-m-d 00:00:00',time()-60*60));

            // if($cnt>100){
            //     $CI->mh_log->error(
            //         array(
            //             'title'=>__METHOD__,
            //             'msg'=>'접근차단',
            //             'result'=>'redirect',
            //             'count'=>$cnt,
            //             'user_agent'=>$CI->input->user_agent(),

            //         )
            //     );
            //     // redirect('https://www.fbi.gov/', 'location', 302);
            //     redirect('https://thetestdata.com/assets/video/mp4/highquality/4k_Thetestdata.mp4', 'location', 302); // MP4	154.47 MB
            //     exit();
            // }

            $CI->mh_log->info(array(
              'title'=>__METHOD__,
              'msg'=>'첫방문',
              'result'=>'성공',
              'ip_address_init'=>$CI->input->ip_address(),
              'user_agent'=>$CI->input->user_agent(),
            ));
            
            if($cnt>30){ //같은 IP로 첫방문 체크
                $CI->session->set_userdata('ip_address_init', $CI->input->ip_address());
                $CI->mh_log->error(array(
                'title'=>__METHOD__,
                'msg'=>'이상방문자',
                'result'=>'sleep',
                'count'=>$cnt,
                // 'sleep'=>round($cnt/2),
                'user_agent'=>$CI->input->user_agent(),
                ));
                // sleep(round($cnt/2)); // 이상 반문 수에 비례해서 sleep
            }
        }
    }
}