<?php
class Api extends Controller {
	private $send_data = true;

	public $key = 'am3omo32hom4lnv32vO';

	public 	$report_level_config = array(
		'teacher'	=> array(
				'student_attendance'		=> array('80', '60', '0'),
				'check_for_understanding'	=> array('80', '60', '0'),
				'child_participation'		=> array('80', '60', '0'),
			),
		'mentor'	=> array(
				'student_attendance'		=> array('80', '60', '0'),
				'check_for_understanding'	=> array('80', '60', '0'),
				'child_participation'		=> array('80', '60', '0'),
				'teacher_satisfaction'		=> array('80', '60', '0'),
				'zero_hour_attendance'		=> array('80', '60', '0'),
				'class_satisfaction'		=> array('80', '60', '0'),
			),
		'center'	=> array(
				'student_attendance'		=> array('80', '60', '0'),
				'check_for_understanding'	=> array('80', '60', '0'),
				'child_participation'		=> array('80', '60', '0'),
				'teacher_satisfaction'		=> array('80', '60', '0'),
				'volunteer_substitutions'	=> array('80', '60', '0'),
			)
	);

	function Api() {
		parent::Controller();

		$this->load->model('users_model', 'user_model');
		$this->load->model('class_model');
		$this->load->model('batch_model');
		$this->load->model('level_model');
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->user_model->year = get_year();
		$this->class_model->year = get_year();
		$this->level_model->year = get_year();
		$this->batch_model->year = get_year();
		$this->kids_model->year = get_year();
		$this->user_model->project_id = 1;
		$this->class_model->project_id = 1;
		$this->level_model->project_id = 1;
		$this->batch_model->project_id = 1;
		$this->kids_model->project_id = 1;

		header("Content-type: application/json");
		header('Access-Control-Allow-Origin: *');
	}

	/// Returns the details of the user who's ID is provided as a get parameter. 
	function user_details() {
		$this->check_key();

		$user_id = $this->input('user_id');
		$user = $this->user_model->user_details($user_id);
		if(!$user) $this->error("User($user_id) not found.");

		$fields_to_return = array('id', 'name', 'email', 'sex', 'phone', 'joined_on', 'address', 'birthday', 'city_id', 'user_type', 'credit', 'groups_name', 'batch');
		$user_data = array();

		foreach ($fields_to_return as $key) {
			$user_data[$key] = $user->$key;
		}

		$this->send(array('user' => $user_data));
		return $user_data;
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
		// $this->check_key();
		
		$data = array(
			'username' => $this->input('email'),
			'password' => $this->input('password')
		);
		if(!$data['username'] or !$data['password']) {
			return $this->error("Username or password not provided.");
		}

		$status = $this->user_model->login($data);
		if(!$status) {
			return $this->error("Invalid Username or password.");
		}

		$connections = $this->user_model->get_class_connections($status['id']);

		$mentor = "0";
		if($connections['mentor_at']) $mentor = "1";

		$this->send(array(
			'user_id'	=> $status['id'],
			'key'		=> $this->key,
			'name'		=> $status['name'],
			'city_id'	=> $status['city_id'],
			'mentor'	=> $mentor,
			'connections'=>$connections,
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

		$user_id = $this->input('user_id');
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

		$user_id = $this->input('user_id');
		$class_on= $this->input('class_on');
		$level_id= $this->input('level_id');
		$batch_id= $this->input('batch_id');

		$class_info = $this->class_model->get_class_on($user_id, $class_on, $level_id, $batch_id);

		if(!$class_info) return $this->error("Can't find any classes matching that criteria.");
		$this->open_class($class_info->id, $class_info, $user_id);
	}

	function browse_class($batch_id = 0, $level_id = 0, $from_date = '', $direction = '+') {
		$this->check_key();

		if(!$batch_id) $batch_id = $this->input('batch_id');
		if(!$level_id) $level_id = $this->input('level_id');
		if(!$from_date) $from_date = $this->input('class_on');

		if(!$from_date) {
			$class_from = $this->input('class_from');
			$direction = $this->input('direction');

			// Change the date to the date of the next class in the said direction.
			$next_class = $this->class_model->get_next_class($batch_id, $level_id, $class_from, $direction);
			if(!$next_class) {
				return $this->error("No classes found beyond this point.");
			}
			$from_date = date("Y-m-d", strtotime($next_class->class_on));
		}

		$class_info = $this->class_model->get_class_by_batch_level_and_date($batch_id, $level_id, $from_date);
		$this->open_class($class_info->id);
	}

	/**
	 * Returns the details of the class with the given ID.
	 */
	function open_class($class_id = 0, $class_info = false, $user_id=0) {
		$this->check_key();
		if(!$class_id) $class_id = $this->input('class_id');
		if(!$user_id) $user_id = $this->input('user_id');

		$class_details = $this->class_model->get_class($class_id);
		if(!$class_info) {
			$class_info = $this->class_model->get_class_by_id($class_id);
			$class_details['center_name'] = $class_info->name;
		}
		
		$class_details['class_time'] = date('d M, Y, h:i A', strtotime($class_details['class_on']));
		
		$level_info = $this->level_model->get_level($class_details['level_id']);
		$class_details['level_name'] = $level_info->name;

		foreach($class_details['teachers'] as $index => $teacher) {
			$user = $this->user_model->get_user($teacher['user_id']);
			$class_details['teachers'][$index]['name'] = $user->name;
			$class_details['teachers'][$index]['credit'] = $user->credit;
			if($user_id == $teacher['user_id']) {
				$class_details['teachers'][$index]['current_user'] = true;
			}
		}

		$students = $this->level_model->get_kids_in_level($class_info->level_id);

		$student_data = $this->class_model->get_attendence_and_understanding($class_id);
		$class_details['students'] = array();

		foreach ($students as $id => $name) {
			$check_for_understanding = false;
			if(isset($student_data['check_for_understanding'][$id]) and $student_data['check_for_understanding'][$id] == '1') $check_for_understanding = true;

			$class_details['students'][$id] = array(
				'name'			=> $name,
				'id'			=> $id,
				'participation' => (!isset($student_data['participation'][$id]) ? 3 : $student_data['participation'][$id]),
				'check_for_understanding'	=> $check_for_understanding
			);
		}

		$this->send($class_details);
	}

	/*
	 * Input: user_id, class_id, students - [{student_id, participation}, ]
	 */
	function class_save_student_participation() {
		$this->check_key();

		$user_id = $this->input('user_id');
		if(!$user_id) $this->error("User ID is empty");

		$class_id = $this->input('class_id');
		$students = json_decode($this->input('students'), true);
		$class_satisfaction= $this->input('class_satisfaction');

		$all_students = array();
		$participation = array();
		$check_for_understanding = array();

		foreach($students as $student_id => $student) {
			$all_students[$student_id] = $student_id;
			$participation[$student_id] = $student['participation'];
			$check_for_understanding[$student_id] = $student['check_for_understanding'];
		}

		$this->class_model->save_attendence($class_id, $all_students, $participation, $check_for_understanding);
		$this->class_model->save_class_satisfaction($class_id, $class_satisfaction);

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

		$user_id = $this->input('user_id');
		if(!$user_id) $this->error("User ID is empty");

		//$batch_id = $this->user_model->get_users_batch($user_id);
		$batch_id = $this->user_model->get_mentoring_batch($user_id);

		$this->class_get_batch($batch_id);
	}

	/// Open a specific Class based on the Batch ID and the date that class has happened
	function open_batch($batch_id='', $from_date='') {
		$this->check_key();

		// $from_date = '2015-01-11';
		if(!$batch_id) $batch_id = $this->input('batch_id');
		if(!$from_date) $from_date = $this->input('class_on');

		if(!$from_date) {
			$class_from = $this->input('class_from');
			$direction = $this->input('direction');

			// Change the date to the date of the next class in the said direction.
			$next_class = $this->class_model->get_next_class($batch_id, 0, $class_from, $direction);
			if($next_class) $from_date = date("Y-m-d", strtotime($next_class->class_on));
			else $from_date = date("Y-m-d");
		}

		$batch = $this->batch_model->get_batch($batch_id);
		$center_id = $batch->center_id;
		$center = $this->center_model->edit_center($center_id)->row();
		$center_name = $center->name;
		$city_id = $center->city_id;

		$groups = array(
				'teacher'	=> 9,
				'trained'	=> 368,
			);

		$data = $this->class_model->search_classes(array('batch_id'=>$batch_id, 'from_date'=>$from_date));
		$all_users = $this->user_model->search_users(array('user_type'=>'volunteer', 'status' => '1', 'user_group'=>$groups['teacher'], 'city_id' => $city_id));

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
					'cancel_option'	=> $row->cancel_option,
					'cancel_reason'	=> $row->cancel_reason,
					'class_type'	=> $row->class_type,
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
	function class_get_batch($batch_id = 0, $class_on = false) {
		$this->check_key();
		// Lifted off classes.php:batch_view
		if(!$batch_id) $batch_id = $this->input('batch_id');
		if(!$class_on) $class_on = $this->input('class_on');

		if(!$batch_id) return $this->error("User doesn't have a batch");

		if(!$class_on) {
			$last_class = $this->class_model->get_last_class_in_batch($batch_id);
			if(!$last_class) return $this->send(array('error' => "This batch does not have any past batches"));

			$from_date = date('Y-m-d', strtotime($last_class->class_on));
		} else {
			$from_date = date('Y-m-d', strtotime($class_on));
		}
		
		$this->open_batch($batch_id, $from_date);
	}


	/// Returns all level in the given batch
	function all_levels_in_batch($batch_id = 0) {
		$this->check_key();
		if(!$batch_id) $batch_id = $this->input('batch_id');
		if(!$batch_id) return $this->error("User doesn't have a batch");
		$levels = $this->batch_model->get_levels_in_batch($batch_id);

		$this->send(array('levels' => $levels));
	}


	/// Save extra classes using the given batch, class date and a collection of level_ids that the user selected using the app.
	function save_extra_class($batch_id = 0, $class_on = '', $levels = array()) {
		$this->check_key();
		if(!$batch_id) $batch_id = $this->input('batch_id');
		if(!$batch_id) return $this->error("Specify 'batch_id' as an argument for this call.");

		if(!$class_on) $class_on = $this->input('class_on');
		if(!$class_on) return $this->error("Specify 'class_on' as argument");

		if(!$levels) $levels = json_decode($this->input('levels'));
		if(!$levels) return $this->error("Provide an array of levels as argument");

		$class_on = date('Y-m-d 15:00:00', strtotime($class_on));
		$class_ids = array();

		if($levels) {
			foreach ($levels as $level_id) {
				$teachers = $this->batch_model->get_teachers_in_batch_and_level($batch_id, $level_id);
				foreach($teachers as $teacher_id) {
					// Make sure its not already inserted.
					if(!$this->class_model->get_by_teacher_date($teacher_id, $class_on, $batch_id, $level_id)) {
						list($class_id, $user_class_id) = $this->class_model->add_class_manually($level_id, $batch_id, $class_on, $teacher_id, 'extra');
						$class_ids[] = $class_id;
					}
				}
				// $this->batch_model->db->query("INSERT INTO Class(batch_id,level_id,project_id,class_on,class_type,status) 
				// 	VALUES($batch_id, $level_id, '1', '$class_on', 'extra', 'projected')");
				// $this->batch_model->db->insert_id();
			}

			$this->send(array('classes' => $class_ids));
		} else {
			$this->error("Couldn't create the classes");
		}
	}

	/**
	 * Returns a list of all the teachers in the given city. This can be used to populate the Substitute dropdown.
	 * Arguments: $city_id - The ID of the city of which teachers you want.
	 * Returns : A list of all the teachers in the city
	 * http://makeadiff.in/madapp/index.php/api/user_get_teachers?city_id=10&key=am3omo32hom4lnv32vO
	 */
	function user_get_teachers() {
		$this->check_key();
		$city_id = $this->input('city_id');
		if(!$city_id) return $this->error("Invalid City ID");

		$groups = array(
				'teacher'	=> 9,
				'trained'	=> 368,
			);

		$teachers = $this->user_model->search_users(array('user_type'=>'volunteer', 'user_group'=>array_values($groups), 'city_id'=>$city_id));
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

		$class_data = json_decode($this->input('class_data'));

		foreach ($class_data as $class_info) {
			$class_id = $class_info->id;
			if(!$class_info->class_status) {
				$this->class_model->cancel_class($class_id, $class_info->cancel_option, $class_info->cancel_reason);
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

	////////////////////////////////////////// Reports ////////////////////////////////

	function teacher_report_aggregate() {
		$level_id = $this->input('level_id');
		$result = array(
				'student_attendance' 		=> 0,
				'check_for_understanding'	=> 0,
				'child_participation'		=> 0
			);
		$data = array();
		$this->send_data = false;
		$data['student_attendance'] = $this->teacher_report_student_attendance();
		$data['child_participation'] = $this->teacher_report_child_participation();
		$data['check_for_understanding'] = $this->teacher_report_check_for_understanding();

		foreach ($result as $key => $value) {
			foreach($data[$key] as $student) {
				if($student['six'][0]->rating == 'red') {
					$result[$key]++;
				}
			}
		}

		$this->send_data = true;
		$this->send(array('reports' => $result, 'report_name' => 'teacher_report_aggregate'));
	}

	/// Returns the absenteeism report for all students who are in the given level
	function teacher_report_student_attendance() {
		$level_id = $this->input('level_id');

		$students = $this->level_model->get_kids_in_level($level_id);

		$attendence = array();
		foreach ($students as $student_id => $student_name) {
			$all = $this->kids_model->get_attendance($student_id, 0);
			for($i = 0; $i < count($all); $i++) {
				if(!$all[$i]->sum) $all[$i]->sum = 0;
				$all[$i]->sum = $all[$i]->total - $all[$i]->sum; // Because we need the absence.
			}
			$all = $this->_rateReports($all, 'teacher', 'student_attendance');

			$attendence[$student_id] = array(
				'id'			=> $student_id,
				'name'			=> $student_name,
				'all'			=> $all,
				'six'			=> array_slice($all, 0, 6)
			);
		}

		$this->send(array('report' => $attendence, 'report_name' => 'student_attendance'));
		return $attendence;
	}


	/// Get all the students in the given level - and see how their check for understanding has been for the last 6 classes - and all the classes.
	function teacher_report_check_for_understanding() {
		$level_id = $this->input('level_id');

		$students = $this->level_model->get_kids_in_level($level_id);

		$check_for_understanding = array();
		foreach ($students as $student_id => $student_name) {
			$all = $this->kids_model->get_understanding($student_id, 0);
			$all = $this->_rateReports($all, 'teacher', 'check_for_understanding');

			$check_for_understanding[$student_id] = array(
				'id'			=> $student_id,
				'name'			=> $student_name,
				'all'			=> $all,
				'six'			=> array_slice($all, 0, 6)
			);
		}

		$this->send(array('report' => $check_for_understanding, 'report_name' => 'check_for_understanding'));
		return $check_for_understanding;
	}

	/// Get all the students in the given level - and see how their participation has been for the last 6 classes - and all the classes.
	function teacher_report_child_participation() {
		$level_id = $this->input('level_id');

		$students = $this->level_model->get_kids_in_level($level_id);

		$child_participation = array();
		foreach ($students as $student_id => $student_name) {
			$all = $this->kids_model->get_participation($student_id, 0);
			$all = $this->_rateReports($all, 'teacher', 'child_participation');

			$child_participation[$student_id] = array(
				'id'			=> $student_id,
				'name'			=> $student_name,
				'all'			=> $all,
				'six'			=> array_slice($all, 0, 6)
			);
		}

		$this->send(array('report' => $child_participation, 'report_name' => 'child_participation'));
		return $child_participation;
	}

	// Get aggregate for all mentor reports. 
	function mentor_report_aggregate() {
		$batch_id = $this->input('batch_id');
		$result = array(
				'zero_hour_attendance' 		=> 0,
				'class_satisfaction'		=> 0,
				'child_participation'		=> 0,
				'check_for_understanding' 	=> 0,
			);
		$data = array();
		$this->send_data = false;
		$data['zero_hour_attendance'] = $this->mentor_report_zero_hour_attendance();
		$data['class_satisfaction'] = $this->mentor_class_satisfaction();
		$data['child_participation'] = $this->mentor_child_participation();
		$data['check_for_understanding'] = $this->mentor_child_cfu();

		foreach ($data as $report_key => $report_data) {
			foreach($report_data as $level) {
				if(isset($level['teachers'])) {
					foreach ($level['teachers'] as $teacher_id => $teacher_data) {
						if(isset($teacher_data['six'][0]) and $teacher_data['six'][0]->rating == 'red') {
							$result[$report_key]++;
						}
					}
				}
			}
		}

		$this->send_data = true;
		$this->send(array('reports' => $result, 'report_name' => 'mentor_report_aggregate'));
	}


	/// Get Zero Hour Attendance for everyone in the given batch
	function mentor_report_zero_hour_attendance() {
		$batch_id = $this->input('batch_id');

		$teachers = $this->batch_model->get_batch_teachers($batch_id);
		$all_levels = idNameFormat($this->batch_model->get_levels_in_batch($batch_id));
		// print json_encode($teachers); exit;

		$zero_hour_attendance = array();
		foreach ($teachers as $teach) {
			$teacher_id = $teach->id;
			$all = $this->class_model->get_zero_hour_attendance($teacher_id, 0);
			$all = $this->_rateReports($all, 'mentor', 'zero_hour_attendance');

			if(!isset($zero_hour_attendance[$teach->level_id])) {
				$zero_hour_attendance[$teach->level_id] = array(
					'id' 	=> $teach->level_id,
					'name'	=> $all_levels[$teach->level_id]
				);
			}

			$zero_hour_attendance[$teach->level_id]['teachers'][$teacher_id] = array(
				'id'			=> $teacher_id,
				'name'			=> $teach->name,
				'level'			=> $all_levels[$teach->level_id],
				'all'			=> $all,
				'six'			=> array_slice($all, 0, 6)
			);
		}

		$this->send(array('report' => $zero_hour_attendance, 'levels' => $all_levels));
		return $zero_hour_attendance;
	}

	/// Get Class Satisfaction for everyone in the given batch
	function mentor_class_satisfaction() {
		$batch_id = $this->input('batch_id');

		$teachers = $this->batch_model->get_batch_teachers($batch_id);
		$all_levels = idNameFormat($this->batch_model->get_levels_in_batch($batch_id));
		// print json_encode($teachers); exit;

		$class_satisfaction = array();
		foreach ($teachers as $teach) {
			$teacher_id = $teach->id;
			$all = $this->class_model->get_class_satisfaction($teacher_id, 0);
			$all = $this->_rateReports($all, 'mentor', 'class_satisfaction');

			if(!isset($class_satisfaction[$teach->level_id])) {
				$class_satisfaction[$teach->level_id] = array(
					'id' 	=> $teach->level_id,
					'name'	=> $all_levels[$teach->level_id]
				);
			}

			$class_satisfaction[$teach->level_id]['teachers'][$teacher_id] = array(
				'id'			=> $teacher_id,
				'name'			=> $teach->name,
				'level'			=> $all_levels[$teach->level_id],
				'all'			=> $all,
				'six'			=> array_slice($all, 0, 6)
			);
		}

		$this->send(array('report' => $class_satisfaction, 'levels' => $all_levels));
		return $class_satisfaction;
	}

	function mentor_child_participation() {
		$batch_id = $this->input('batch_id');

		$all_levels = idNameFormat($this->batch_model->get_levels_in_batch($batch_id));

		$class_participation = array();
		foreach ($all_levels as $level_id => $level) {
			$students = $this->level_model->get_kids_in_level($level_id);

			foreach ($students as $student_id => $student_name) {
				$all = $this->kids_model->get_participation($student_id, 0);
				$all = $this->_rateReports($all, 'mentor', 'child_participation');

				if(!isset($child_participation[$level_id])) {
					$child_participation[$level_id] = array(
						'id' 	=> $level_id,
						'name'	=> $all_levels[$level_id]
					);
				}

				$child_participation[$level_id]['students'][$student_id] = array(
					'id'			=> $student_id,
					'name'			=> $student_name,
					'all'			=> $all,
					'six'			=> array_slice($all, 0, 6)
				);
			}
		}

		$this->send(array('report' => $child_participation, 'levels' => $all_levels));
		return $child_participation;
	}

	function mentor_child_cfu() {
		$batch_id = $this->input('batch_id');

		$all_levels = idNameFormat($this->batch_model->get_levels_in_batch($batch_id));

		$class_participation = array();
		foreach ($all_levels as $level_id => $level) {
			$students = $this->level_model->get_kids_in_level($level_id);

			foreach ($students as $student_id => $student_name) {
				$all = $this->kids_model->get_understanding($student_id, 0);
				$all = $this->_rateReports($all, 'mentor', 'child_participation');

				if(!isset($child_participation[$level_id])) {
					$child_participation[$level_id] = array(
						'id' 	=> $level_id,
						'name'	=> $all_levels[$level_id]
					);
				}

				$child_participation[$level_id]['students'][$student_id] = array(
					'id'			=> $student_id,
					'name'			=> $student_name,
					'all'			=> $all,
					'six'			=> array_slice($all, 0, 6)
				);
			}
		}

		$this->send(array('report' => $child_participation, 'levels' => $all_levels));
		return $child_participation;
	}


	/// Center reports start now...


	function center_child_participation() {
		$center_id = $this->input('center_id');

		$all_levels = idNameFormat($this->level_model->get_all_level_names_in_center($center_id));

		$class_participation = array();
		foreach ($all_levels as $level_id => $level) {
			$students = $this->level_model->get_kids_in_level($level_id);

			foreach ($students as $student_id => $student_name) {
				$all = $this->kids_model->get_participation($student_id, 0);
				$all = $this->_rateReports($all, 'center', 'child_participation');

				if(!isset($child_participation[$level_id])) {
					$child_participation[$level_id] = array(
						'id' 	=> $level_id,
						'name'	=> $all_levels[$level_id]
					);
				}

				$child_participation[$level_id]['students'][$student_id] = array(
					'id'			=> $student_id,
					'name'			=> $student_name,
					'all'			=> $all,
					'six'			=> array_slice($all, 0, 6)
				);
			}
		}

		$this->send(array('report' => $child_participation, 'levels' => $all_levels)); 
	}

	function center_child_cfu() {
		$center_id = $this->input('center_id');

		$all_levels = idNameFormat($this->level_model->get_all_level_names_in_center($center_id));

		$check_for_understanding = array();
		foreach ($all_levels as $level_id => $level) {
			$students = $this->level_model->get_kids_in_level($level_id);

			foreach ($students as $student_id => $student_name) {
				$all = $this->kids_model->get_participation($student_id, 0);
				$all = $this->_rateReports($all, 'center', 'check_for_understanding');

				if(!isset($check_for_understanding[$level_id])) {
					$check_for_understanding[$level_id] = array(
						'id' 	=> $level_id,
						'name'	=> $all_levels[$level_id]
					);
				}

				$check_for_understanding[$level_id]['students'][$student_id] = array(
					'id'			=> $student_id,
					'name'			=> $student_name,
					'all'			=> $all,
					'six'			=> array_slice($all, 0, 6)
				);
			}
		}

		$this->send(array('report' => $check_for_understanding, 'levels' => $all_levels)); 
	}

	function center_volunteer_subsitutions() {
		$center_id = $this->input('center_id');

		$all_levels = idNameFormat($this->level_model->get_all_level_names_in_center($center_id));
		$all_teachers = idNameFormat($this->user_model->search_users(array('center' => $center_id)));

		$substitutions = array();
		foreach ($all_levels as $level_id => $level) {
			$batches_in_level = $this->batch_model->get_batches_in_level($level_id);

			foreach ($batches_in_level as $batch) {
				$batch_id = $batch->id;

				$teachers = $this->batch_model->get_teachers_in_batch_and_level($batch_id, $level_id);
				foreach ($teachers as $teacher_id) {
					$all = $this->class_model->get_volunteer_substitutions($teacher_id, 0);
					$all = $this->_rateReports($all, 'center', 'volunteer_substitutions');

					if(!isset($substitutions[$level_id])) {
						$substitutions[$level_id] = array(
							'id' 	=> $level_id,
							'name'	=> $all_levels[$level_id]
						);
					}

					$substitutions[$level_id]['teachers'][$teacher_id] = array(
						'id'			=> $teacher_id,
						'name'			=> $all_teachers[$teacher_id],
						'level'			=> $all_levels[$level_id],
						'all'			=> $all,
						'six'			=> array_slice($all, 0, 6)
					);
				}
			}
		}

		$this->send(array('report' => $substitutions, 'levels' => $all_levels)); 
	}

	function _rateReports($data, $report_type, $report_name) {
		for($i = 0; $i < count($data); $i++) {
			if(!$data[$i]->sum) $data[$i]->sum = 0;
			if($data[$i]->total == 0 and $data[$i]->sum == 0) $data[$i]->percentage = 0;
			else $data[$i]->percentage = intval($data[$i]->sum / $data[$i]->total * 100);

			$data[$i]->rating = 'red';
			if($data[$i]->percentage >= $this->report_level_config[$report_type][$report_name][0]) $data[$i]->rating = 'green';
			else if($data[$i]->percentage >= $this->report_level_config[$report_type][$report_name][1]) $data[$i]->rating = 'yellow';
		}

		return $data;
	}
	///////////////////////////////////////// Internal ////////////////////////////////
	function input($name) {
		$return = '';

		$return = $this->input->post($name);
		if(!$return) $this->input->get($name);
		if(!$return and isset($_REQUEST[$name])) $return = $_REQUEST[$name];

		return $return;
	}

	function check_key() {
		$key = $this->input('key');
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

		if($this->send_data) print json_encode($data);
		return true;
	}
}