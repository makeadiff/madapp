<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class CenterSupport extends Controller  {
    function centersupport() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->user_auth->logged_in();
        if(!$logged_user_id) {
            redirect('auth/login');
        }


    }


    function dashboard_view() {

        $data['title'] = 'Center Support';

        $this->load->view('layout/flatui/header',$data);
        $this->load->view('center_support/center_support_dashboard');
        $this->load->view('layout/flatui/footer',$data);
    }
}