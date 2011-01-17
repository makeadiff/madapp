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
		
		$this->load->helper('url');
		$this->load->helper('misc');
	}
	
	function index() {
		$all_classes = $this->class_model->get_all($this->user_details->id);
		if($all_classes) {
			$all_levels[$all_classes[0]->level_id] = $this->level_model->get_level_details($all_classes[0]->level_id);
		}
		
		$this->load->view('classes/index', array('all_classes' => $all_classes, 'all_levels'=>$all_levels, 'level_model'=>$this->level_model));
	}

}