<?php
class Api extends Controller {
	public $key = 'am3omo32hom4lnv32vO';

	function Api() {
		parent::Controller();

		$this->load->model('users_model', 'user_model');
		$this->load->model('class_model');
		$this->load->model('batch_model');
		$this->load->model('level_model');
		$this->load->model('center_model');
	}

	function user_login() {
		$data = array(
			'username' => $this->input->post('email'),
			'password' => $this->input->post('password')
		);
		$status = $this->user_model->login($data);

		$this->send(array(
			'user_id'	=> $status['id'],
			'key'		=> $this->key,
			'name'		=> $status['name'],
			'city_id'	=> $status['city_id'],
			'groups'	=> array_values($status['groups']),
		));
	}

	function class_get_last() {
		$this->check_key();

		$user_id = $this->input->post('user_id');

		$class_info = $this->class_model->get_last_class($user_id);
		$class_details = $this->class_model->get_class($class_info->id);
		$class_details['center_name'] = $class_info->name;

		$this->send($class_details);
	}
	function class_get() {
		$this->check_key();

		$class_id = $this->input->post('class_id');

		$class_details = $this->class_model->get_class($class_id);
		$this->send($class_details);
	}

	/** Returns a list of all the teachers in the given city in the format {"user_id":"name", "user_id":"name"}. This can be used to populate the Substitute dropdown.
	 * Arguments: $city_id - The ID of the city of which teachers you want.
	 */
	function user_get_teachers() {
		$this->check_key();
		$city_id = $this->input->post('city_id');

		$teachers = idNameFormat($this->user_model->search_users(array('user_type'=>'volunteer', 'user_group'=>9, 'city_id'=>$city_id)));

		$this->send($teachers);
	}

	function class_get_last_batch_id() {
		$this->check_key();
		$user_id = $this->input->post('user_id');
		$batch_id = $this->user_model->get_users_batch($user_id);

		$this->send(array('batch_id'=>$batch_id));
	}

	function class_get_students() {
		$this->check_key();
		$class_id = $this->input->post('class_id');
		
		$class_info = $this->class_model->get_class($class_id);
		$level_id = $class_info['level_id'];
		
		$students = $this->level_model->get_kids_in_level($level_id);
		$attendence = $this->class_model->get_attendence($class_id);

		$this->show(array('students'=>$students, 'attendence'=>$attendence, 'class_info'=>$class_info));
	}

	
	function class_get_batch() {
		$this->check_key();
		// Lifted off classes.php:batch_view
		$batch_id = $this->input->post('batch_id');

		if(!$batch_id) return $this->send(array('error' => "User doesn't have a batch"));

		$last_class = $this->class_model->get_last_class_in_batch($batch_id);
		if(!$last_class) return $this->send(array('error' => "This batch does not have any past batches"));
		
		$from_date = date('Y-m-d', strtotime($last_class->class_on));
		$batch = $this->batch_model->get_batch($batch_id);
		$center_id = $batch->center_id;
		$center_name = $this->center_model->get_center_name($center_id);
		$data = $this->class_model->search_classes(array('batch_id'=>$batch_id, 'from_date'=>$from_date));
		$all_users = $this->user_model->search_users(array('user_type'=>'volunteer', 'status' => '1', 'user_group'=>9));
		
		$classes = array();
		foreach($data as $row) {
			$attendence = $this->class_model->get_attendence($row->id);
			$level_id = $row->level_id;
			
			// Each level must have only the units in the book given to that level.
			if(empty($all_lessons[$level_id])) {
				$all_lessons[$level_id] = array();
				$all_lessons[$level_id][0] = "None";
				for($i=1; $i<=20; $i++) $all_lessons[$level_id][$i] = $i;
				$all_lessons[$level_id][-1] = "Revision";
				$all_lessons[$level_id][-2] = "Test";
			}
			
			$present_count = 0;
			$total_kids_in_level = count($this->level_model->get_kids_in_level($level_id));
			foreach($attendence as $id=>$status) if($status == 1) $present_count++;
			$attendence_count = $present_count . '/' . $total_kids_in_level;
			
			if(!isset($classes[$row->id])) { // First time we are encounting such a class.
				$classes[$row->id] = array(
					'id'			=> $row->id,
					'level_id'		=> $row->level_id,
					'level_name'	=> $row->name,
					'lesson_id'		=> $row->lesson_id,
					'student_attendence'	=> $attendence_count,
					'teachers'		=> array(array(
						'id'		=> $row->user_id,
						'name'		=> isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->name : 'None',
						'status'	=> $row->status,
						'user_type'	=>isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->user_type : 'None',
						'substitute_id'=>$row->substitute_id,
						'substitute'=> ($row->substitute_id != 0 and isset($all_users[$row->substitute_id])) ? 
											$all_users[$row->substitute_id]->name : 'None',
						'zero_hour_attendance'	=> $row->zero_hour_attendance
					)),
				);
			} else { // We got another class with same id. Which means more than one teachers in the same class. Add the teacher to the class.
				$classes[$row->id]['teachers'][] = array(
					'id'	=> $row->user_id,
					'name'	=> isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->name : 'None',
					'status'=> $row->status,
					'user_type'	=>isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->user_type : 'None',
					'substitute_id'=>$row->substitute_id,
					'substitute' => ($row->substitute_id != 0 and isset($all_users[$row->substitute_id])) ? 
											$all_users[$row->substitute_id]->name : 'None',
					'zero_hour_attendance'	=> $row->zero_hour_attendance
				);
			}
		}

		$this->send(array('classes'=>$classes, 'center_name'=>$center_name, 'batch_id'=>$batch_id, 'batch_name'=>$batch->name));
	}


	function class_cancel() {
		$this->check_key();
		$class_id = $this->input->post('class_id');

		$this->class_model->cancel_class($class_id);
		$this->send(array('success' => "Class cancelled."));
	}
	function class_uncancel() {
		$this->check_key();
		$class_id = $this->input->post('class_id');

		$this->class_model->uncancel_class($class_id);
		$this->send(array('success' => "Class un-cancelled."));
	}



	function check_key() {
		return true;

		$key = $this->input->post('key');
		if($key != $this->key) {
			$this->send(array('error' => "Invalid Key"));
			exit;
		}
	}

	function send($data) {
		print json_encode($data);
		return true;
	}
}