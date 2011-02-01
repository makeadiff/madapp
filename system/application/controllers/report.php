<?php
class Report extends Controller {
	private $message;
	
	function Report() {
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
	
		$this->load->model('Report_model', 'report_model');
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
	}
	
	function index() {
		$this->load->view('report/index');
	}
	
	function users_with_low_credits() {
		$report_data = $this->report_model->get_users_with_low_credits();
		
		$this->show_report($report_data, array('name'=>'Name', 'credit'=>'Credits'), 'Users With Low Credits(0 or less)');
	}
	
	function absent() {
		$report_data = $this->report_model->get_users_absent_without_substitute();
		$this->show_report($report_data, array('name'=>'Name', 'class_on'=>'Class Time', 'center_id'=>'Center Name'), 
			'Users Who Were Absent Without a Substitute');
	}
	
	
	function show_report($data, $fields, $title) {
		$this->load->view('report/report', array('data'=>$data, 'fields'=>$fields, 'title'=>$title));
	}

}