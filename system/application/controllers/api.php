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
		$this->user_model->year = 2014;
		$this->class_model->year = 2014;
		$this->level_model->year = 2014;
		$this->batch_model->year = 2014;

		header("Content-type: application/json");
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
	 * Returns the ID of the last class of the given user.
	 * Arguments :	$user_id
	 * Returns : 	Class Details.
	 * Example : http://makeadiff.in/madapp/index.php/api/?&key=am3omo32hom4lnv32vO
	 */
	function class_get_last() {
		$this->check_key();

		$user_id = $this->get_input('user_id');
		if(!$user_id) $this->error("User ID is empty");

		$class_info = $this->class_model->get_last_class($user_id);
		$class_details = $this->class_model->get_class($class_info->id);
		$class_details['center_name'] = $class_info->name;

		$this->send($class_details);
	}

	/**
	 * Gets the class details of the class who's ID is given
	 * Arguments :	$class_id
	 * Returns : 	Class Details
	 * Example : http://makeadiff.in/madapp/index.php/api/?&key=am3omo32hom4lnv32vO
	 */
	function class_get() {
		$this->check_key();

		$class_id = $this->get_input('class_id');
		if(!$class_id) $this->error("Class ID is empty");

		$class_details = $this->class_model->get_class($class_id);

		if(!$class_details) return $this->error("Invalid Class ID");
		$this->send($class_details);
	}

	/**
	 * Returns a list of all the teachers in the given city in the format {"user_id":"name", "user_id":"name"}. This can be used to populate the Substitute dropdown.
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

		$this->send($return);
	}

	/**
	 * Returns a list of all the volunteers in the given city.
	 * Arguments: $city_id - The ID of the city of which volunteer you want.
	 * Returns : A list of all the volunteer in the city
	 * Example : http://makeadiff.in/madapp/index.php/api/user_get_all?city_id=10&key=am3omo32hom4lnv32vO
	 */
	function user_get_all() {
		$this->check_key();
		$city_id = $this->get_input('city_id');
		if(!$city_id) return $this->error("Invalid City ID");

		$volunteers = $this->user_model->search_users(array('user_type'=>'volunteer', 'city_id'=>$city_id));
		if(!$volunteers) return $this->error("No Data from server");
		$return = array();
		foreach ($volunteers as $t) {
			$return[] = array(
					'id'	=> $t->id,
					'name'	=> $t->name,
					'email'	=> $t->email,
					'phone'	=> $t->phone,
				);
		}

		$this->send(array('data'=>$return));
	}

	/**
	 * Search the Database of a city with the given name. Returns all the volunteers who matches the name.
	 * Arguments:	$city_id - The ID of the city in which the search should be done.
	 * 				$name - The name that should be searched for. Part of the name is good enough.
	 * Returns: JSON data of all the results from that name.
	 * Example : http://makeadiff.in/madapp/index.php/api/?&key=am3omo32hom4lnv32vO
	 */
	function user_search_name() {
		$this->check_key();
		$city_id = $this->get_input('city_id');
		if(!$city_id) return $this->error("Invalid City ID");

		$name = $this->get_input('name');
		if(!$city_id) return $this->error("Provide name to search for.");

		$volunteers = $this->user_model->search_users(array('user_type'=>'volunteer', 'city_id'=>$city_id, 'name' => $name));
		if(!$volunteers) return $this->error("No one found by the name '$name'");
		$return = array();
		foreach ($volunteers as $t) {
			$return[] = array(
					'id'	=> $t->id,
					'name'	=> $t->name,
					'email'	=> $t->email,
					'phone'	=> $t->phone,
				);
		}

		$this->send(array('data'=>$return));
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

		$batch_id = $this->user_model->get_users_batch($user_id);

		$this->class_get_batch($batch_id);
	}

	function class_save_level() {
		$this->check_key();
		$user_id = $this->get_input('user_id');

		$class_id = $this->get_input('class_id');
		$lesson_id = $this->get_input('lesson_id');

		// Can be mupltiple
		$teachers = $this->get_input('teacher_id');
		$substitutes = $this->get_input('substitute_id');
		$status = $this->get_input('status');
		$zero_hour_attendance = $this->get_input('zero_hour_attendance');

		// Figure out things...
		$this->class_model->save_class_lesson($class_id, $lesson_id);
		foreach($teachers as $key => $teacher_id) {
			$this->class_model->save_class_teachers(0, array(
				'user_id'	=> $teacher_id,
				'class_id'	=> $class_id,
				'substitute_id'=>$substitutes[$key],
				'status'	=> $status[$key],
				'zero_hour_attendance'	=> $zero_hour_attendance[$key],
			));
		}
	}

	/**
	 * Returns the list of all the students who are part of the given class
	 * Arguments :	$class_id
	 * Returns : 	List of all the students in the class with attendance data.
	 * Example : http://makeadiff.in/madapp/index.php/api/?&key=am3omo32hom4lnv32vO
	 */
	function class_get_students() {
		$this->check_key();
		$class_id = $this->get_input('class_id');
		
		$class_info = $this->class_model->get_class($class_id);
		$level_id = $class_info['level_id'];
		
		$students = $this->level_model->get_kids_in_level($level_id);
		$attendence = $this->class_model->get_attendence($class_id);

		$this->show(array('students'=>$students, 'attendence'=>$attendence, 'class_info'=>$class_info));
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

	function class_save_batch() {
		$this->check_key();


	}

	/**
	 * Use this to cancel a class. Just pass the ID of the class to cancel.
	 * Arguments :	$class_id
	 * Returns : 	Success/Fail
	 * Example : http://makeadiff.in/madapp/index.php/api/class_cancel?class_id=129404&key=am3omo32hom4lnv32vO
	 */
	function class_cancel() {
		$this->check_key();
		$class_id = $this->get_input('class_id');

		$this->class_model->cancel_class($class_id);
		$this->send(array('success' => "Class cancelled."));
	}

	/**
	 * Use this to un-cancel a class thats already cancelled.
	 * Arguments :	$class_id
	 * Returns : 	
	 * Example : http://makeadiff.in/madapp/index.php/api/class_uncancel?class_id=129404&key=am3omo32hom4lnv32vO
	 */
	function class_uncancel() {
		$this->check_key();
		$class_id = $this->get_input('class_id');

		$this->class_model->uncancel_class($class_id);
		$this->send(array('success' => "Class un-cancelled."));
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
		return $this->send(array('error' => $text, 'status' => "0"));
	}

	function send($data) {
		if(!isset($data['status'])) $data['status'] = "1";

		print json_encode($data);
		return true;
	}
}