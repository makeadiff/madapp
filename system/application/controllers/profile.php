<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Profile extends Controller  {
    function profile() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->user_auth->logged_in();
        if(!$logged_user_id) {
            redirect('auth/login');
        }


    }


    function dashboard_view() {

        $data['title'] = 'Profile';

        $current_user = $this->users_model->get_user($this->session->userdata('id'));

        $this->load->view('layout/flatui/header',$data);
        $this->load->view('profile/profile_dashboard', array('current_user'=>$current_user));
        $this->load->view('layout/flatui/footer',$data);
    }
}