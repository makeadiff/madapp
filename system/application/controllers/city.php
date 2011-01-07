<?php
class City extends Controller {
	private $message;
	
	function City() {
		parent::Controller();
		$message = array('success'=>false, 'error'=>false);
	
		$this->load->scaffolding('City');
		$this->load->model('City_model','model', TRUE);
		$this->load->helper('url');
	}
	
	function index() {
		$all_cities = $this->model->getCities();
		
		$this->load->view('city/index', array('all_cities'	=> $all_cities, 'message'=>$this->message));
	}
	
	function create() {
		// Make a new city.
		if($this->input->post('action') == 'New') {
			$data = array(
						'name'			=>	$this->input->post('name'), 
						'president_id'	=>	$this->input->post('president_id'),
					);
			$this->model->createCity($data);
			$this->message['success'] = 'The City has been added';
			$this->index();
		
		} else {
		// Show the form to make a new city.
			$this->load->helper('form');
			$this->load->model('User_model','user_model');
			
			$president_ids = $this->user_model->getUsersById();
			
			$this->load->view('city/form.php', array(
				'action' => 'New',
				'president_ids' => $president_ids
				));
		}
	}
	
	function edit() {
		if($this->input->post('action') == 'Edit') {
			$data = array(
						'name'			=>	$this->input->post('name'), 
						'president_id'	=>	$this->input->post('president_id'),
					);
			$this->model->editCity($data);
				
			$this->message['success'] = 'The City has been edited successfully';
			$this->index();
		
		} else {
			$this->load->helper('misc');
			$this->load->helper('form');
			$city_id = $this->uri->segment(3);
			$this->load->model('User_model','user_model');
			
			$president_ids = $this->user_model->getUsersById();
			$city = $this->model->getCity($city_id);
			
			$this->load->view('city/form.php', array(
				'action' 		=> 'Edit',
				'president_ids' => $president_ids,
				'city'			=> $city
				));
		}
	}
	function view()
	{
	echo "helooooooooooo";
	}


}