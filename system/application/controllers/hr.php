<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class hr extends Controller  {
    function hr() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->user_auth->logged_in();
        if(!$logged_user_id) {
            redirect('auth/login');
        }


    }


    function dashboard_view() {
        $this->load->view('hr/hr_dashboard');
    }
}