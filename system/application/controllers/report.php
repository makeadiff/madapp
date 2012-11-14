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
		$this->user_auth->check_permission('report_index');
		$this->load->view('report/index');
	}
	
	function users_with_low_credits($credit=0, $sign='less', $city_id=-1) {
		$this->user_auth->check_permission('report_view');
		$credit = intval($credit);
		
		$signs = array('more'=>'>', 'less'=>'<=');
		$report_data = $this->report_model->get_users_with_low_credits($credit, $signs[$sign], $city_id);
		$this->show_report($report_data, array('name'=>'Name', 'credit'=>'Credits'), 'Users With Low Credits('.$credit.' or less)');
	}
	
	function absent() {
		$report_data = $this->report_model->get_users_absent_without_substitute();
		$this->show_report($report_data, array('name'=>'Name', 'class_on'=>'Class Time', 'center_name'=>'Center Name'), 
			'Users Who Were Absent Without a Substitute');
	}
	
	function volunteer_requirement() {
		$report_data = $this->report_model->get_volunteer_requirements();
		$this->show_report($report_data, array('name'=>'Center', 'requirement'=>'Volunteers Required'), 
			'Volunteer Required for all Centers');
	}
	
	function get_volunteer_admin_credits() {
		$report_data = $this->report_model->get_volunteer_admin_credits();
		$this->show_report($report_data, array(
				'name'		=> 'Intern', 
				'credit'	=> 'Credits',
				'april'		=> 'April',
				'may'		=> 'May',
				'june'		=> 'June',
				'july'		=> 'July',
				'august'	=> 'August',
				'september'	=> 'September',
				'october'	=> 'October',
				'november'	=> 'November',
				'december'	=> 'December',
				'january'	=> 'January',
				'february'	=> 'February',
				'march'		=> 'March',
			), 
			'Admin Credits of all Interns');
	}
	
	function show_report($data, $fields, $title) {
		$this->user_auth->check_permission('report_view');
		$this->load->view('report/report', array('data'=>$data, 'fields'=>$fields, 'title'=>$title));
	}

}