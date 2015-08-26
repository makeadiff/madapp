<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Setting extends Controller  {
    function setting() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->user_auth->logged_in();
        if(!$logged_user_id) {
            redirect('auth/login');
        }


    }


    function dashboard_view() {

        $data['title'] = 'Settings';

        set_city_year($this);

        $this->load->view('layout/flatui/header',$data);
        $this->load->view('setting/setting_dashboard');
        $this->load->view('layout/flatui/footer',$data);
    }
}