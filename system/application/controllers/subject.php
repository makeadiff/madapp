<?php
class Subject extends Controller {	
	function Subject() {
		parent::Controller();
		$this->load->model('Users_model','user_model');
		$this->load->model('Subject_model','subject_model');
		
		$this->load->helper('url');
		$this->load->helper('misc');
		
		$this->load->library('session');
        $this->load->library('user_auth');
        
        $this->user_id = $this->user_auth->logged_in();
		if(!$this->user_id) {
			redirect('auth/login');
		}
        $this->user_details = $this->user_auth->getUser();
	}

	function index() {
		$subjects = $this->subject_model->get_all_subjects();
		$this->load->view('subject/index', array('subjects' => $subjects));
	}

	function edit($subject_id) {
		$subject = $this->subject_model->get_subject($subject_id);
		$this->load->view('subject/edit', array('subject' => $subject));
	}

	function add() {
		$this->load->view('subject/edit');
	}

	function delete($subject_id) {
		$this->subject_model->delete($subject_id);

		$this->session->set_flashdata('success', 'Subject Deleted.');
		redirect('subject/index');
	}

	function save() {
		$subject_id = $this->input->post('subject_id');
		$data = array(
			'name'			=> $this->input->post('name'),
			'unit_count'	=> $this->input->post('unit_count'),
			'city_id'		=> $this->subject_model->city_id,
		);

		if($subject_id) {
			$this->subject_model->edit($subject_id, $data);
			$this->session->set_flashdata('success', 'Subject edited.');
		} else {
			$this->subject_model->add($data);
			$this->session->set_flashdata('success', 'Subject created.');
		}

		redirect('subject/index');
	}
}

