<?php
class City extends Controller {
	private $message;
	
	function City() {
		parent::Controller();
		$message = array('success'=>false, 'error'=>false);
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
	
		$this->load->model('City_model','city_model', TRUE);
		$this->load->helper('url');
	}
	
	function index() {
		$this->user_auth->check_permission('city_index');
		$all_cities = $this->city_model->getCities();
		$this->load->view('city/index', array('all_cities'	=> $all_cities, 'message'=>$this->message));
	}

	// Shows the important numbers of a city - Center count, Vol count, student count, etc.
	function info($city_id = 0) {
		set_city_year($this);
		$info =  $this->city_model->get_info($city_id);
		$this->load->view('city/info', $info);
	}
	
	function create() {
		$this->user_auth->check_permission('city_create');
		
		// Make a new city.
		if($this->input->post('action') == 'New') {
			if($this->input->post('name') != '') {
				$data = array(
					'name'			=>	$this->input->post('name'), 
					'president_id'	=>	$this->input->post('president_id'),
				);
				
				$this->city_model->createCity($data);
				$this->message['success'] = 'The City has been added';
				$this->session->set_flashdata('success', 'The City has been added successfully');
				redirect('city/index');
			} else {
				$this->session->set_flashdata('success', 'City Not Added ');
				redirect('city/index');
			}
		
		} else {
		// Show the form to make a new city.
			$this->load->helper('form');
			$this->load->helper('misc');
			$this->load->model('Users_model','user_model');
			
			$president_ids = idNameFormat($this->user_model->get_users_in_city(0));
			$president_ids['0'] = 'None';
			
			$this->load->view('city/form.php', array(
				'action' => 'New',
				'president_ids' => $president_ids
				));
			
		}
		
	}
	/**
    *
    * Function to edit
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function edit() {
		$this->user_auth->check_permission('city_edit');
		
		if($this->input->post('action') == 'Edit') {
			$data = array(
						'name'			=>	$this->input->post('name'), 
						'president_id'	=>	$this->input->post('president_id'),
					);
			$flag=$this->city_model->editCity($data);
			if($flag){
			$this->message['success'] = 'The City has been edited successfully';
			redirect('city/index');
			} else
			{
			$this->message['success'] = 'Failed to edit city';
			redirect('city/index');
			}
		
		} else {
			$this->load->helper('misc');
			$this->load->helper('form');
			$city_id = $this->uri->segment(3);
			$this->load->model('Users_model','user_model');
			
			$president_ids = $this->user_model->getUsersById();
			$city = $this->city_model->getCity($city_id);
			
			$this->load->view('city/form.php', array(
				'action' 		=> 'Edit',
				'president_ids' => $president_ids,
				'city'			=> $city
				));
		}
	}
	
	function view()
	{
	}
}