<?php
class Review extends Controller {
	private $message;
	private $all_cycles = array('Nothing', 'Aug', 'Sept', 'Oct', 'Nov', 'Dec');
	
	function Review() {
		parent::Controller();
		$this->load->model('Users_model','user_model');
		$this->load->model('city_model');
		$this->load->model('Review_Parameter_model','review_model');
		
		$this->load->helper('url');
		$this->load->helper('misc');
		
		$this->load->library('session');
        $this->load->library('user_auth');
        
        $this->user_id = $this->user_auth->logged_in();        
		if(!$this->user_id) {
			redirect('auth/login');
		}
        $this->user_details = $this->user_auth->getUser();
        $this->cycle = 1;
	}

	function select_people() {
		$this->user_auth->check_permission('review_fellows');
		$city_id = $this->session->userdata('city_id');

		$fellows = $this->user_model->get_fellows_or_above($city_id);
		$this->load->view('review/select_people', array('fellows'=>$fellows));
	}

	function review_fellow($user_id, $cycle = 0) {
		$this->user_auth->check_permission('review_fellows');
		$city_id = $this->session->userdata('city_id');
		if(!$cycle) $cycle = $this->cycle;
		// :TODO: Check if the current user has permission to review the said fellow.

		$parameter_reviews = $this->review_model->get_reviews($user_id, $cycle, 'parameter');
		$milestone_reviews = $this->review_model->get_reviews($user_id, $cycle, 'milestone');

		$user = $this->user_model->get_user($user_id);
		$this->load->view('review/review_fellow', array('parameter_reviews' => $parameter_reviews, 
														'milestone_reviews' => $milestone_reviews,
														'user_id'=>$user_id, 'user' => $user,
														'cycle'=>$cycle, 'auth'=>$this->user_auth));
	}

	function ajax_get_comment($parameter_id) {
		$comment = $this->review_model->get_comment($parameter_id);
		print $comment;
	}

	function ajax_save_comment($parameter_id, $comment) {
		$this->review_model->set_comment($parameter_id, $comment);
		print '{"success":true}';
	}

	function ajax_save_value($parameter_id, $value) {
		$this->review_model->save_value($parameter_id, $value);
		print '{"success":true}';	
	}
	
	function milestone_select_people() {
		$this->user_auth->check_permission('milestone_list');
		$current_user = $this->user_details->id;
		$people = $this->user_model->get_subordinates($current_user);

		if($this->input->post('action') == 'Search') {
			$highest_group = $this->user_model->get_highest_group($current_user);

			if($highest_group == 'national') $search_for_groups = array('fellow','strat');
			elseif($highest_group == 'strat') $search_for_groups = array('fellow');

			$people = $this->user_model->search_users(array(
				'user_group_type' => $search_for_groups,
				'city_id' => 0,
				'user_type' => 'volunteer',
				'name' => $this->input->post('name')));
		}

		$this->load->view('review/milestone_select_people', array('people'=>$people));
	}

	function list_milestones($user_id, $cycle=0) {
		$this->user_auth->check_permission('milestone_list');

		$milestones = $this->review_model->get_all_milestones($user_id, $cycle);
		$user_details=$this->user_model->user_details($user_id);
		$this->load->view('review/list_milestones', array('milestones' => $milestones,'user_details'=>$user_details, 'user_id'=>$user_id, 'all_cycles' => $this->all_cycles));
	}

	function edit_milestone($milestone_id) {
		$this->user_auth->check_permission('milestone_create');

		$milestone = $this->review_model->get_milestone($milestone_id);
		$this->load->view('review/edit_milestone', array('milestone' => $milestone, 'all_cycles' => $this->all_cycles));
	}

	function new_milestone($user_id) {
		$this->user_auth->check_permission('milestone_create');

		$this->load->view('review/edit_milestone', array('user_id'=>$user_id, 'all_cycles' => $this->all_cycles));
	}

	function save_milestone() {
		$this->user_auth->check_permission('milestone_create');

		$milestone_id = $this->input->post('milestone_id');
		$data = array(
			'name'			=> $this->input->post('name'),
			'status'		=> $this->input->post('status'),
			'due_on' 		=> $this->input->post('due_on'),
			'user_id' 		=> $this->input->post('user_id'),
			'created_by_user_id' => $this->user_id
		);
		$data['cycle'] = $this->review_model->find_cycle($data['due_on']);

		if($milestone_id) {
			$this->review_model->edit_milestone($milestone_id, $data);
			$this->session->set_flashdata('success', 'Milestone edited.');
		} else {
			$this->review_model->create_milestone($data);
			$this->session->set_flashdata('success', 'Milestone created.');
		}

		redirect('review/list_milestones/'.$this->input->post('user_id'));
	}

	function delete_milestone($milestone_id) {
		$user_id = $this->review_model->delete_milestone($milestone_id);

		$this->session->set_flashdata('success', 'Milestone deleted.');
		redirect('review/list_milestones/'.$user_id);	
	}

	function list_timeframes($user_id) {
		$timeframes = $this->review_model->get_timeframes_with_milestone($user_id);
		$this->load->view('review/list_timeframes', array('user_id'=>$user_id, 'timeframes'=>$timeframes, 'all_cycles' => $this->all_cycles));
	}


	function my_milestones() {
		$this->user_auth->check_permission('milestone_my');

		$overdue_milestones = $this->review_model->get_overdue_milestones($this->user_id, $this->cycle);
		$current_milestones = $this->review_model->get_all_milestones($this->user_id, $this->cycle);

		$this->load->view('review/my_milestones', 
			array('overdue_milestones' => $overdue_milestones, 'current_milestones' => $current_milestones));
	}

	function do_milestone($milestone_id, $status, $done_on) {
		$this->user_auth->check_permission('milestone_do');

		$this->review_model->do_milestone($milestone_id, $status, $done_on);
		print '{"success":true, "milestone_id":'.$milestone_id.',"error":false}';
	}
}

