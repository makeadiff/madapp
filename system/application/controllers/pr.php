<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class pr extends Controller  {
    function pr() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->user_auth->logged_in();
        if(!$logged_user_id) {
            redirect('auth/login');
        }


    }


    function dashboard_view() {
        $this->load->view('pr/pr_dashboard');
    }
}