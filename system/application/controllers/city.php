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
		$all_cities = $this->model->db->get('City')->result();
		
		$this->load->view('city/index', array('all_cities'	=> $all_cities));
	}
	
	function create() {
		if($this->input->post('action') == 'New') {
			$this->db->insert('City', 
				array(
					'name'			=>	$this->input->post('name'), 
					'president_id'	=>	$this->input->post('president_id'),
					'added_on'		=>	date('Y-m-d H:i:s')
				));

			$this->message['success'] = 'The City has been added';
			$this->index();
		
		} else {
			$this->load->helper('misc');
			$this->load->helper('form');
			
			$president_ids = getById("SELECT id, name FROM User", $this->model->db);
			$this->load->view('city/form.php', array(
				'action' => 'New',
				'president_ids' => $president_ids
				));
		}
	}
	
	function edit() {
		if($this->input->post('action') == 'Edit') {
			$this->db->where('id', $this->input->post('id'))->update('City', 
				array(
					'name'			=>	$this->input->post('name'), 
					'president_id'	=>	$this->input->post('president_id')
				));
				
			$this->message['success'] = 'The City has been edited successfully';
			$this->index();
		
		} else {
			$this->load->helper('misc');
			$this->load->helper('form');
			$city_id = $this->uri->segment(3);
			
			$city = $this->db->where('id',$city_id)->get('City')->row_array();
			
			$president_ids = getById("SELECT id, name FROM User", $this->model->db);
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