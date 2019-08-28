<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class dashboard extends Controller  {
    function dashboard() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $this->load->model('Review_Parameter_model','review_model');
        $logged_user_id = $this->user_auth->accessControl();
    }

    function dashboard_view() {
        $data['title'] = 'MADApp';

        set_city_year($this);

        $user_id = $this->session->userdata('id');
        $happiness_index_data_entry_status = $this->review_model->get_happiness_index_data_entry_status($user_id);

        $this->load->view('layout/flatui/header',$data);
        $this->load->view('common_dashboard/common_dashboard', array('happiness_index_data_entry_status'=>$happiness_index_data_entry_status));
        $this->load->view('layout/flatui/footer',$data);
    }
}
