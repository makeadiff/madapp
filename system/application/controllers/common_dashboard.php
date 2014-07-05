<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Common_dashboard extends Controller  {
    function Common_dashboard() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->user_auth->logged_in();
        if(!$logged_user_id) {
            redirect('auth/login');
        }


    }


    function dashboard_view() {
           $this->load->view('common_dashboard/common_dashboard');
    }
}
