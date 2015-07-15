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
		$this->user_model->year = get_year();
		$this->class_model->year = get_year();
		$this->level_model->year = get_year();
		$this->batch_model->year = get_year();
		$this->user_model->project_id = 1;
		$this->class_model->project_id = 1;
		$this->level_model->project_id = 1;
		$this->batch_model->project_id = 1;

		header("Content-type: application/json");
		header('Access-Control-Allow-Origin: *');
	}

	/**
	 * This will login the user into the system. 
	 * Arguments : 	email - The username of the user to be logged in
	 * 				password - Don't make me explain this :-P
	 * Returns :$user_id - The ID of the user. Use this to make more user level calls.
	 *			$city_id - The ID of the city the user belongs to.
	 *			$key - The Auth Key. Include this with every call you make or else you will get a error.
	 *			$groups - All the Groups this user is a part of.
	 * Example: http://makeadiff.in/madapp/index.php/api/user_login?email=cto@makeadiff.in&password=pass
	 */
	function user_login() {
		$data = array(
			'username' => $this->get_input('email'),
			'password' => $this->get_input('password')
		);
		if(!$data['username'] or !$data['password']) {
			return $this->error("Username or password not provided.");
		}

		$status = $this->user_model->login($data);
		if(!$status) {
			return $this->error("Invalid Username or password.");
		}

		$mentor = "0";
		if(in_array('Mentors', array_values($status['groups']))) $mentor = "1";

		$this->send(array(
			'user_id'	=> $status['id'],
			'key'		=> $this->key,
			'name'		=> $status['name'],
			'city_id'	=> $status['city_id'],
			'mentor'	=> $mentor,
			'groups'	=> array_values($status['groups']),
		));
	}

	/**
	 * Returns the class details of the last class of the given user.
	 * Arguments :	$user_id
	 * Returns : 	Class Details.
	 * Example : http://makeadiff.in/madapp/index.php/api/?&key=am3omo32hom4lnv32vO
	 */
	function class_get_last() {
		$this->check_key();

		$user_id = $this->get_input('user_id');
		if(!$user_id) return $this->error("User ID is empty");

		$class_info = $this->class_model->get_last_class($user_id);
		if(!$class_info) return $this->error("No classes found.");
		$this->open_class($class_info->id, $class_info, $user_id);
	}

	/**
	 * Get the class using the user_id and class date.
	 */ 
	function get_class_on() {
		$this->check_key();

		$user_id = $this->get_input('user_id');
		$class_on= $this->get_input('class_on');

		$class_info = $this->class_model->get_class_on($user_id, $class_on);

		if(!$class_info) return $this->error("Can't find any classes matching that criteria.");
		$this->open_class($class_info->id, $class_info, $user_id);
	}

	/**
	 * Returns the details of the class with the given ID.
	 */
	function open_class($class_id, $class_info = false, $user_id=0) {
		$class_details = $this->class_model->get_class($class_id);

		$class_details['center_name'] = $class_info->name;
		$class_details['class_time'] = date('d M, Y, h:i A', strtotime($class_details['class_on']));
		
		$level_info = $this->level_model->get_level($class_details['level_id']);
		$class_details['level_name'] = $level_info->name;

		foreach($class_details['teachers'] as $index => $teacher) {
			$user = $this->user_model->get_user($teacher['user_id']);
			$class_details['teachers'][$index]['name'] = $user->name;
			if($user_id == $teacher['user_id']) {
				$class_details['teachers'][$index]['current_user'] = true;
			}
		}

		$students = $this->level_model->get_kids_in_level($class_info->level_id);

		$participation = $this->class_model->get_attendence($class_id);
		$class_details['students'] = array();

		foreach ($students as $id => $name) {
			// if(!isset($class_details['students'][$id])) continue;
			$class_details['students'][$id] = array(
				'name'			=> $name,
				'id'			=> $id,
				'participation' => (!isset($participation[$id]) ? 3 : $participation[$id])
			);
		}

		$this->send($class_details);
	}

	/*
	 * Input: user_id, class_id, students - [{student_id, participation}, ]
	 */
	function class_save_student_participation() {
		$this->check_key();

		$user_id = $this->get_input('user_id');
		if(!$user_id) $this->error("User ID is empty");

		$class_id = $this->get_input('class_id');
		$students = json_decode($this->get_input('students'), true);

		$all_students = array();
		$attendence = array();
		foreach($students as $student_id => $student) {
			$all_students[$student_id] = $student_id;
			$attendence[$student_id] = $student['participation'];
		}
		//dump($class_id, $all_students, $attendence);
		$this->class_model->save_attendence($class_id, $all_students, $attendence);

		$this->send(array('status' => "Class saved."));
	}

	/**
	 * Returns the last batch of the given user. 
	 * Arguments :	$user_id - ID of the user who's batch must be found.
	 * Returns : 	the last batch that happened for the given user
	 * Example : http://makeadiff.in/madapp/index.php/api/class_get_last_batch?user_id=1&key=am3omo32hom4lnv32vO
	 */
	function class_get_last_batch() {
		$this->check_key();

		$user_id = $this->get_input('user_id');
		if(!$user_id) $this->error("User ID is empty");

		//$batch_id = $this->user_model->get_users_batch($user_id);
		$batch_id = $this->user_model->get_mentoring_batch($user_id);

		$this->class_get_batch($batch_id);
	}

	/// Open a specific Class based on the Batch ID and the date that class has happened
	function open_batch($batch_id='', $from_date='') {
		$this->check_key();

		// $from_date = '2015-01-11';
		if(!$batch_id) $batch_id = $this->get_input('batch_id');
		if(!$from_date) $from_date = $this->get_input('class_on');

		$batch = $this->batch_model->get_batch($batch_id);
		$center_id = $batch->center_id;
		$center = $this->center_model->edit_center($center_id)->row();
		$center_name = $center->name;
		$city_id = $center->city_id;
		$data = $this->class_model->search_classes(array('batch_id'=>$batch_id, 'from_date'=>$from_date));
		$all_users = $this->user_model->search_users(array('user_type'=>'volunteer', 'status' => '1', 'user_group'=>9, 'city_id' => $city_id));

		$classes = array();
		$class_done = array();
		$index = 0;
		foreach($data as $row) {
			$attendence = $this->class_model->get_attendence($row->id);
			$level_id = $row->level_id;
			
			$present_count = 0;
			$total_kids_in_level = count($this->level_model->get_kids_in_level($level_id));
			foreach($attendence as $id=>$status) if($status == 1) $present_count++;
			$attendence_count = $present_count . '/' . $total_kids_in_level;

			if(!isset($class_done[$row->id])) { // First time we are encounting such a class.
				$class_done[$row->id] = $index;
				$classes[$index] = array(
					'id'			=> $row->id,
					'level_id'		=> $row->level_id,
					'level_name'	=> $row->name,
					'grade'			=> $row->grade,
					'class_status'	=> ($row->status == 'cancelled') ? '0' : '1',
					'student_attendance'	=> $attendence_count,
					'teachers'		=> array(array(
						'id'		=> $row->user_id,
						'name'		=> isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->name : 'None',
						'status'	=> ($row->status == 'attended') ? true : false,
						'user_type'	=> isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->user_type : 'None',
						'substitute_id'=> $row->substitute_id,
						'substitute'=> ($row->substitute_id != 0 and isset($all_users[$row->substitute_id])) ? 
											$all_users[$row->substitute_id]->name : 'None',
						'zero_hour_attendance'	=> ($row->zero_hour_attendance) ? true : false
					)),
				);
				$index++;

			} else { // We got another class with same id. Which means more than one teachers in the same class. Add the teacher to the class.
				$classes[$class_done[$row->id]]['teachers'][] = array(
					'id'			=> $row->user_id,
					'name'			=> isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->name : 'None',
					'status'		=> ($row->status == 'attended') ? true : false,
					'user_type'		=> isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->user_type : 'None',
					'substitute_id'	=> $row->substitute_id,
					'substitute'	=> ($row->substitute_id != 0 and isset($all_users[$row->substitute_id])) ? 
											$all_users[$row->substitute_id]->name : 'None',
					'zero_hour_attendance'	=> ($row->zero_hour_attendance) ? true : false
				);
			}
		}
		$class_on = '';
		$class_date = '';
		if(isset($data[0]->class_on)) {
			$class_on = date('Y-m-d', strtotime($data[0]->class_on));
			$class_date = date('dS M, Y', strtotime($data[0]->class_on));
		}

		$this->send(array(
				'classes'		=> $classes, 
				'center_name'	=> $center_name, 
				'batch_id'		=> $batch_id, 
				'batch_name'	=> $batch->name,
				'class_on' 		=> $class_on,
				'class_date'	=> $class_date,
			));
	}

	/**
	 * Get the enter Mentor view data in one call - just specify which Batch ID should be shown
	 * Arguments:	$batch_id
	 * Returns 	: 	REALLY complicated JSON. Just call it and parse it to see what comes :-P
	 * Example	: 	http://makeadiff.in/madapp/index.php/api/?&key=am3omo32hom4lnv32vO
	 */	
	function class_get_batch($batch_id = 0) {
		$this->check_key();
		// Lifted off classes.php:batch_view
		if(!$batch_id) $batch_id = $this->get_input('batch_id');

		if(!$batch_id) return $this->error("User doesn't have a batch");

		$last_class = $this->class_model->get_last_class_in_batch($batch_id);
		if(!$last_class) return $this->send(array('error' => "This batch does not have any past batches"));

		$from_date = date('Y-m-d', strtotime($last_class->class_on));
		$this->open_batch($batch_id, $from_date);
	}

	/**
	 * Returns a list of all the teachers in the given city. This can be used to populate the Substitute dropdown.
	 * Arguments: $city_id - The ID of the city of which teachers you want.
	 * Returns : A list of all the teachers in the city
	 * http://makeadiff.in/madapp/index.php/api/user_get_teachers?city_id=10&key=am3omo32hom4lnv32vO
	 */
	function user_get_teachers() {
		$this->check_key();
		$city_id = $this->get_input('city_id');
		if(!$city_id) return $this->error("Invalid City ID");

		$teachers = $this->user_model->search_users(array('user_type'=>'volunteer', 'user_group'=>9, 'city_id'=>$city_id));
		if(!$teachers) return $this->error("No Data from server");

		$return = array();

		foreach ($teachers as $t) {
			$return[] = array(
					'id'	=> $t->id,
					'name'	=> $t->name
				);
		}

		$this->send(array('teachers'=>$return));
	}

	/*
	class_save_level
		class_id=129404
		lesson_id=7
		teacher_id[0]=43880
		substitute_id[0]=0
		status[0]='attended'
		zero_hour_attendance[0]=1
		teacher_id[1]=35382
		substitute_id[1]=1
		status[1]='absent'
		zero_hour_attendance[1]=0

	*/
	function class_save() {
		$this->check_key();

		$class_data = json_decode($this->get_input('class_data'));

		foreach ($class_data as $class_info) {
			$class_id = $class_info->id;
			if(!$class_info->class_status) {
				$this->class_model->cancel_class($class_id);
				continue;
			}

			// Figure out things...
			foreach($class_info->teachers as $teacher_info) {
				$teacher_id = $teacher_info->id;
				$substitute_id = $teacher_info->substitute_id;
				$status = ($teacher_info->status) ? 'attended' : 'absent';
				$zero_hour_attendance = ($teacher_info->zero_hour_attendance) ? '1' : '0';

				$this->class_model->save_class_teachers(0, array(
					'user_id'	=> $teacher_id,
					'class_id'	=> $class_id,
					'substitute_id'=> $substitute_id,
					'status'	=> $status,
					'zero_hour_attendance'	=> $zero_hour_attendance,
				));
			}
		}

		$this->send(array('success' => "Class Attendance Updated", 'status'=>'1'));
	}


	///////////////////////////////////////// Internal ////////////////////////////////
	function get_input($name) {
		$return = '';

		$return = $this->input->post($name);
		if(!$return) $this->input->get($name);
		if(!$return and isset($_REQUEST[$name])) $return = $_REQUEST[$name];

		return $return;
	}

	function check_key() {
		$key = $this->get_input('key');
		if($key != $this->key) {
			$this->error("Invalid Key");
			exit;
		}
	}


	function error($text) {
		$this->send(array('error' => $text, 'status' => "0", "success" => false));
		exit;
	}

	function send($data) {
		if(!isset($data['status']) and !isset($data['success'])) {
			$data['status'] = "1";
			$data['success'] = "1";
		}

		print json_encode($data);
		return true;
	}
}