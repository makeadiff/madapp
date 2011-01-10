<?php
class Batch extends Controller {
	private $message;
	
	function Batch() {
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
	
		$this->load->scaffolding('Batch');
		$this->load->model('Batch_model','model');
		$this->load->model('Center_model','center_model');
		$this->load->model('Users_model','user_model');
		$this->load->helper('url');
		$this->load->helper('misc');
	}
	
	function index($type='center', $item_id = 0) {
		if(!is_numeric($item_id) or !$item_id) {
			show_error("Choose a center/level.");
		}
		
		if($type == 'center') {
			$all_batches = $this->model->get_batches_in_center($item_id);
			$center_id = $item_id;
			$item_name = $this->center_model->get_center_name($center_id);
			
		} elseif($type == 'level') {
			$this->load->model('Level_model','level_model');
			
			$level = $this->level_model->get_level($item_id);
			$all_batches = $this->model->get_batches_in_level($item_id);
			
			if($all_batches) $center_id = $all_batches[0]->id;
			else $center_id = $level->center_id;
			
			$item_name = $level->name .  ' at ' . $this->center_model->get_center_name($center_id);
		}
		
		$this->load->view('batch/index', array('all_batches' => $all_batches,'center_name'=>$item_name, 'center_id'=>$center_id, 'message'=>$this->message));
	}
	
	function add_volunteers($batch_id) {
		$this->load->helper('form');
		
		// Get Batch details.
		$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		$batch = $this->model->get_batch($batch_id);
		$batch_name =  $day_list[$batch->day] . ' ' . date('h:i A', strtotime('2000-01-01 ' . $batch->class_time));
		
		// Get the rest of the necessary stuff...
		$levels_in_batch = $this->model->get_levels_in_batch($batch_id);
		$teachers_in_center = $this->user_model->get_users_in_center($batch->center_id);
		
		// This array will be used later to decide who belongs to which level.
		$level_teacher = array();
		foreach($levels_in_batch as $level) {
			$teachers_in_level = $teachers_in_batch = $this->model->get_teachers_in_batch_and_level($batch_id, $level->id);
			foreach($teachers_in_batch as $user) {
				$level_teacher[$level->id][$user->id] = true;
			}
		}
		
		$this->load->view('batch/add_volunteers', array('batch' => $batch,'batch_name'=>$batch_name, 'center_id'=>$batch->center_id, 
				'levels_in_batch'=>$levels_in_batch,'teachers_in_center'=>$teachers_in_center, 'level_teacher'=>$level_teacher, 'message'=>$this->message));
	}
	
	function add_volunteers_action() {
		$batch_id = $this->input->post('batch_id');
		$teacher_levels = $this->input->post('teachers_in_level');
		
		foreach($teacher_levels as $level_id => $teacher_ids) {
			// First delete all the users in this batch/level before insert the new teachers(even the old ones).
			$this->user_model->unset_user_batch_and_level($batch_id, $level_id);
			foreach($teacher_ids as $user_id) {
				$this->user_model->set_user_batch_and_level($user_id, $batch_id, $level_id);
			}
		}
		
		$this->message['success'] = 'Saved the new teachers';
		$this->add_volunteers($batch_id);
	}
	
	function create($holder, $center_id = 0) {
		if(!is_numeric($center_id)) {
			show_error("Choose a center." . $center_id);
		}
		
		$this->load->helper('misc');
		$this->load->helper('form');
		
		$center_ids = idNameFormat($this->center_model->get_all());
		$batch_volunters = idNameFormat($this->user_model->get_users_in_center($center_id));
		$center_name = $this->center_model->get_center_name($center_id);
		
		$this->load->view('batch/form.php', array(
			'action' => 'New',
			'center_ids' => $center_ids,
			'center_name'=> $center_name,
			'batch_volunters'=>$batch_volunters,
			'batch'	=> array(
				'id'		=> 0,
				'center_id'	=> $center_id,
				)
			));
	}
	
	function create_action() {
		$this->model->create(array(
					'day'		=>	$this->input->post('day'),
					'class_time'=>	$this->input->post('class_time'),
					'batch_head_id' =>	$this->input->post('batch_head_id'),
					'center_id'	=>	$this->input->post('center_id'),
					'project_id'=>	$this->input->post('project_id'),
				));

			$this->message['success'] = 'The Batch has been added';
			$this->index('center', $this->input->post('center_id'));
	}
	
	function edit($batch_id) {
		$this->load->helper('misc');
		$this->load->helper('form');
		
		$batch = $this->model->get_batch_as_array($batch_id);
		$center_ids = idNameFormat($this->center_model->get_all());
		$batch_volunters = idNameFormat($this->user_model->get_users_in_center($batch['center_id']));
		$center_name = $this->center_model->get_center_name($batch['center_id']);
		
		$this->load->view('batch/form.php', array(
			'action' 	=> 'Edit',
			'center_ids'=> $center_ids,
			'center_name'=> $center_name,
			'batch_volunters'=>$batch_volunters,
			'batch'		=> $batch,
			));
	}
	
	function edit_action() {
		$this->db->where('id', $this->input->post('id'))->update('Batch', 
				array(
					'name'		=>	$this->input->post('name'), 
					'center_id'	=>	$this->input->post('center_id'),
					'project_id'=>	$this->input->post('project_id'),
				));
		$this->message['success'] = 'The Batch has been edited successfully';
		$this->index('center', $this->input->post('center_id'));
		$this->input->post('center_id');
	}
	
	function delete($batch_id) {
		$batch = $this->model->get_batch_as_array($batch_id);
		$this->model->delete($batch_id);
		
		$this->message['success'] = 'The Batch has been deleted successfully';
		$this->index('center',$batch['center_id']);
	}
}