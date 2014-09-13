<?php
class Batch extends Controller {
	private $message;
	
	function Batch() {
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		
		$this->load->model('Batch_model','model');
		$this->load->model('Center_model','center_model');
		$this->load->model('Users_model','user_model');
		$this->load->model('Level_model','level_model');
		$this->load->model('subject_model');
			
		$this->load->helper('url');
		$this->load->helper('misc');
	}
	
	function index($type='center', $item_id = 0) {
		$this->user_auth->check_permission('batch_index');
		
		if(!is_numeric($item_id) or !$item_id) {
			show_error("Choose a center/level.");
		}
		
		if($type == 'center') {
			$all_batches = $this->model->get_batches_in_center($item_id);
			$center_id = $item_id;
			$item_name = $this->center_model->get_center_name($center_id);

		} elseif($type == 'level') {
			$level = $this->level_model->get_level($item_id);
			$all_batches = $this->model->get_batches_in_level($item_id);
			
			if($all_batches) $center_id = $all_batches[0]->center_id;
			else $center_id = $level->center_id;
			
			$item_name = $level->name .  ' at ' . $this->center_model->get_center_name($center_id);
		}
		$all_users = idNameFormat($this->users_model->getuser_details()->result());
		
		$this->load->view('batch/index', array('all_batches' => $all_batches,'center_name'=>$item_name, 'center_id'=>$center_id, 'all_users'=>$all_users));
	}
	
	function add_volunteers($batch_id) {
		$this->user_auth->check_permission('batch_add_volunteers');
		
		$this->load->helper('form');
		
		// Get Batch details.
		$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$batch = $this->model->get_batch($batch_id);
		$batch_name =  $day_list[$batch->day] . ' ' . date('h:i A', strtotime('2000-01-01 ' . $batch->class_time));
		
		// Get the rest of the necessary stuff...
		$levels_in_center = $this->level_model->get_all_levels_in_center($batch->center_id);
		$all_teachers = idNameFormat($this->user_model->get_users_in_city($this->session->userdata('city_id')));
		$volunteer_requirement = idNameFormat($this->model->get_volunteer_requirement_in_batch($batch->id));
		
		// This array will be used later to decide who belongs to which level.
		$level_teacher = array();
		foreach($levels_in_center as $level) {
			$teachers_in_level = $this->model->get_teachers_in_batch_and_level($batch_id, $level->id);
			foreach($teachers_in_level as $user) {
				$level_teacher[$level->id][$user] = true;
			}
		}
		
		$this->load->view('batch/add_volunteers', array('batch' => $batch,'batch_name'=>$batch_name, 'center_id'=>$batch->center_id, 'volunteer_requirement'=>$volunteer_requirement,
				'levels_in_center'=>$levels_in_center,'all_teachers'=>$all_teachers, 'level_teacher'=>$level_teacher, 'message'=>$this->message));
	}
	
	function add_volunteers_action() {
		$this->user_auth->check_permission('batch_add_volunteers');
		$this->load->model('Class_model','class_model');
		
		$batch_id = $this->input->post('batch_id');
		$teacher_levels = $this->input->post('teachers_in_level');
		$volunteer_requirement = $this->input->post('volunteer_requirement');
		
		$old_volunteers = array();
		
		foreach($volunteer_requirement as $level_id => $requirement) {
			// Save the details of the old volunteers in the batch/level
			$old_volunteers[$level_id] = $this->model->get_teachers_in_batch_and_level($batch_id, $level_id);
		
			// Then delete all the users in this batch/level before insert the new data(even the old ones).
			$this->user_model->unset_user_batch_and_level($batch_id, $level_id);
			
			$this->model->set_volunteer_requirement($batch_id, $level_id, $requirement);
		}
		
		foreach($teacher_levels as $level_id => $teacher_ids) {
			// If someone removes a volunteer from a batch, make sure his future classes are deleted
			$delete_future_class_of = array_diff($old_volunteers[$level_id], $teacher_ids);
			foreach($delete_future_class_of as $user_id) $this->class_model->delete_future_classes($user_id, $batch_id, $level_id);
			
			foreach($teacher_ids as $user_id) {
				$this->user_model->set_user_batch_and_level($user_id, $batch_id, $level_id);
			}
		}
		
		// :TODO: Call the class scheduler manually.
		
		$this->session->set_flashdata('success','Saved the new teachers');
		redirect('batch/add_volunteers/'.$batch_id);
	}
	
	function create($holder, $center_id = 0) {
		$this->user_auth->check_permission('batch_create');
		
		if(!is_numeric($center_id)) {
			show_error("Choose a center." . $center_id);
		}
		
		$this->load->helper('misc');
		$this->load->helper('form');
		
		$center_ids = idNameFormat($this->center_model->get_all());
		$batch_volunters = idNameFormat($this->user_model->get_users_in_city());
		$center_name = $this->center_model->get_center_name($center_id);
		$all_subjects = idNameFormat($this->subject_model->get_all_subjects());
		
		$this->load->view('batch/form.php', array(
			'action' 		=> 'Create',
			'center_ids' 	=> $center_ids,
			'center_name'	=> $center_name,
			'batch_volunters'=>$batch_volunters,
			'all_subjects'	=> $all_subjects,
			'batch'	=> array(
				'id'		=> 0,
				'center_id'	=> $center_id,
				)
			));
	}
	
	function create_action() {
		$this->user_auth->check_permission('batch_create');
		
		$this->model->create(array(
					'day'		=>	$this->input->post('day'),
					'class_time'=>	$this->input->post('class_time'),
					'batch_head_id' =>	$this->input->post('batch_head_id'),
					'subjects'	=>  $this->input->post('subjects'),
					'center_id'	=>	$this->input->post('center_id'),
					'project_id'=>	$this->input->post('project_id'),
				));

			
			$this->session->set_flashdata('success', 'The Batch has been added');
			/*$this->index('center', $this->input->post('center_id'));*/
			redirect('batch/index/center/'.$this->input->post('center_id'));
	}
	
	function edit($batch_id) {
		$this->user_auth->check_permission('batch_edit');
		
		$this->load->helper('misc');
		$this->load->helper('form');
		
		$batch = $this->model->get_batch_as_array($batch_id);
		$center_ids = idNameFormat($this->center_model->get_all());
		$batch_volunters = idNameFormat($this->user_model->get_users_in_city());
		$center_name = $this->center_model->get_center_name($batch['center_id']);
		$all_subjects = idNameFormat($this->subject_model->get_all_subjects());

		$batch['selected_subjects'] = array_keys($this->model->get_subjects_in_batch($batch_id));
		
		$this->load->view('batch/form.php', array(
			'action' 	=> 'Edit',
			'center_ids'=> $center_ids,
			'center_name'=> $center_name,
			'batch_volunters'=>$batch_volunters,
			'all_subjects'	=> $all_subjects,
			'batch'		=> $batch,
			));
	}
	
	function edit_action() {
		$this->user_auth->check_permission('batch_edit');
		
		$this->model->edit($this->input->post('id'),  array(
				'day'		=>	$this->input->post('day'),
				'class_time'=>	$this->input->post('class_time'),
				'batch_head_id' =>	$this->input->post('batch_head_id'),
				'center_id'	=>	$this->input->post('center_id'),
				'subjects'	=>  $this->input->post('subjects'),
				'project_id'=>	$this->input->post('project_id'),
			));

		$this->session->set_flashdata('success', 'The Batch has been edited successfully');
		redirect('batch/index/center/'.$this->input->post('center_id'));
	}
	
	function delete($batch_id) {
		$this->user_auth->check_permission('batch_delete');
		
		$batch = $this->model->get_batch_as_array($batch_id);
		$this->model->delete($batch_id);
		$this->message['success'] = 'The Batch has been deleted successfully';
		$this->index('center',$batch['center_id']);
	}
}
