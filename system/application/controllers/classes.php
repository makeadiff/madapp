<?php
class Classes extends Controller {
	private $message;
	
	function Classes() {
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
	
		$this->load->model('Class_model','class_model');
		$this->load->model('Level_model','level_model');
		$this->load->model('Users_model','user_model');
		
		$this->load->library('session');
        $this->load->library('user_auth');
        $this->user_details = $this->user_auth->getUser();
		if(!$this->user_details) {
			redirect('auth/login');
		}
		
		$this->load->helper('url');
		$this->load->helper('misc');
	}
	
	function index() {
		$all_classes = $this->class_model->get_all($this->user_details->id);
		$all_levels = array();
		if($all_classes) {
			$all_levels[$all_classes[0]->level_id] = $this->level_model->get_level_details($all_classes[0]->level_id);
		}
		
		$this->load->view('classes/index', array('all_classes' => $all_classes, 'all_levels'=>$all_levels, 'level_model'=>$this->level_model));
	}
	
	function batch_view($batch_id=0) {
		
	}
	
	function edit_class($class_id) {
		$this->load->helper('form');
	
		$class_details = $this->class_model->get_class($class_id);
		$level_details = $this->level_model->get_level($class_details['level_id']);
		$teachers = idNameFormat($this->user_model->get_users_in_center($level_details->center_id));
		$substitutes = idNameFormat($this->user_model->get_users_in_city($level_details->center_id));
		$substitutes[0] = 'No Substitute';
		
		$statuses = array(
			'projected'	=> 'Projected',
			'confirmed'	=> 'Confirmed', 
			'attended'	=> 'Attended', 
			'absent'	=> 'Absent',
			'cancelled'	=> 'Cancelled',
		);
		
		$this->load->view('classes/form', 
			array('class_details'=>$class_details, 'teachers'=>$teachers,'substitutes'=>$substitutes,'statuses'=>$statuses, 'message'=>$this->message));
	}
	
	function edit_class_save() {
		$teacher_ids = $this->input->post('user_id');
		$user_class_id = $this->input->post('user_class_id');
		$substitute_ids = $this->input->post('substitute_id');
		$statuses = $this->input->post('status');
		
		$teacher_count = count($user_class_id);
		// There might be multiple teachers in a class.
		for($i = 0; $i<$teacher_count; $i++) {
			$this->class_model->save_class_teachers($user_class_id[$i], array(
				'user_id'	=>	$teacher_ids[$i],
				'substitute_id'=>$substitute_ids[$i],
				'status'	=> $statuses[$i],
			));
		}
		
		$this->message['success'] = 'Saved the class details';
		$this->edit_class($this->input->post('class_id'));
	}

}