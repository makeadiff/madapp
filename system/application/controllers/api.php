<?php
/*
	DO NOT use this for new apps. Just use the standard API.
 */
class Api extends Controller {
	private $send_data = true;
	private $_input_data = false;

	public $key = 'or4W3@KOERUme#3';

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

	// Check current year according to madapp
	function year() {
		print get_year();
	}

	/// Returns the user details with the given id/email/phone. 
	/// GET /users
	function check_user_exists() {
		$this->check_key();

		$user_id = $this->input('user_id');
		$phone = $this->input('phone');
		$email = $this->input('email');

		$user = $this->user_model->find_user(array(
				'user_id'	=> $user_id,
				'phone'		=> $phone,
				'email'		=> $email
			));

		if($user) {
			$user->groups = $this->user_model->get_user_groups_of_user($user->id);
		}

		$this->send(array('user' => $user));
		return $user;
	}

	/// Add a user as a teacher.
	/// POST /users
	function user_add() {
		$name = $this->input('name');
		$phone = $this->input('phone');
		$email = $this->input('email');
		$city_id = $this->input('city_id');
		$groups = explode(",", $this->input('groups'));

		$user_id = $this->user_model->adduser(array(
			'name'		=> $name,
			'email'		=> $email,
			'phone'		=> $phone,
			'password'=> 'pass',
			'address'	=> '',
			'city'		=> $city_id,
			'sex'			=> 'f',
			'type'		=> 'volunteer',
			));
		$this->user_model->adduser_to_group($user_id, $groups);
		$this->send(array('user_id' => $user_id, 'name' => $name, 'email' => $email, 'phone' => $phone));
		return $user_id;
	}

	/// Returns the details of the user who's ID is provided as a get parameter. 
	/// GET /users/{user_id}
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

	// Returns the things needed for the connection/home page. So far, class history, class counts, unmarked classes.
	// :TODO: GET /users/{user_id}/class_info | unupdated_classes
	function user_class_info() {
		$this->check_key();

		$user_id = $this->input("user_id");
		$class_info = $this->user_model->get_user_class_history($user_id);

		// Remove all the projected and cancelled classes from the list.
		foreach ($class_info['all_classes'] as $i => $class) {
			if($class->status == 'projected' || $class->status == 'cancelled') unset($class_info['all_classes'][$i]);
			
		}
		$class_info['all_classes'] = array_values($class_info['all_classes']);
		// unset($class_info['all_classes']);
		$class_info['student_data_not_updated'] = $this->class_model->get_classes_where_student_data_is_not_updated($user_id);

		$this->send($class_info);
		return $class_info;
	}
	
	// :TODO: GET /users/{user_id}/batch_info
	function user_batch_info() {
		$this->check_key();

		$user_id = $this->input("user_id");
		$batch_id = reset($this->batch_model->get_batches_connected_to_user($user_id)); // Gets just one batch connected to this user.
		$volunteer_data_not_updated = $this->class_model->get_classes_where_teacher_data_is_not_updated($user_id);
		$student_data_not_updated = $this->class_model->get_classes_where_student_data_is_not_updated_in_batch($batch_id);

		$all_teachers_in_batch = array_values(array_unique(colFormat($this->batch_model->get_teachers_in_batch($batch_id))));
		$teachers_with_negative_credits = array();
		if($all_teachers_in_batch) {
			$teachers_with_negative_credits = $this->db->query("SELECT id,name,credit FROM User WHERE credit < 0 AND id IN (" 
																		. implode(",", $all_teachers_in_batch) . ")")->result();
		}
		// Subsitution Card
		$total_classes = oneFormat($this->db->query("SELECT COUNT(C.id) FROM Class C
			INNER JOIN UserClass UC ON UC.class_id=C.id
			WHERE C.status='happened' AND C.batch_id=$batch_id")->row());

		$substituted_classes = oneFormat($this->db->query("SELECT COUNT(C.id) FROM Class C
			INNER JOIN UserClass UC ON UC.class_id=C.id
			WHERE UC.substitute_id!=0 AND C.status='happened' AND C.batch_id=$batch_id")->row());

		$substituted_in_last_5_classes = idNameFormat($this->db->query("SELECT C.class_on AS id, COUNT(C.id) AS name FROM Class C
			INNER JOIN UserClass UC ON UC.class_id=C.id
			WHERE UC.substitute_id!=0 AND C.status='happened' AND C.batch_id=$batch_id
			GROUP BY C.class_on
			ORDER BY C.class_on DESC
			LIMIT 0,5")->result());

		$total_in_last_5_classes = idNameFormat($this->db->query("SELECT C.class_on AS id, COUNT(C.id) AS name FROM Class C
			INNER JOIN UserClass UC ON UC.class_id=C.id
			WHERE C.status='happened' AND C.batch_id=$batch_id
			GROUP BY C.class_on
			ORDER BY C.class_on DESC
			LIMIT 0,5")->result());

		$last_5_classes = array();
		foreach ($substituted_in_last_5_classes as $key => $value) {
			@$last_5_classes[] = array(
				'date'			=> $key,
				'total'			=> $total_in_last_5_classes[$key],
				'substitution'	=> $substituted_in_last_5_classes[$key], 
				'percentage'	=> intval(($substituted_in_last_5_classes[$key] / $total_in_last_5_classes[$key]) * 100)
			);
		}
		$substitution_percentage = 0;
		if($total_classes) $substitution_percentage = intval(($substituted_classes / $total_classes) * 100);
		$substitution_info = array(
					'substitution_percentage'	=> $substitution_percentage,
					'last_5_classes'					=> $last_5_classes
			);
		
		$batch_info = array(
				'batch_id'										=> $batch_id,
				'volunteer_data_not_updated'	=> $volunteer_data_not_updated,
				'student_data_not_updated'		=> $student_data_not_updated,
				'teachers_with_negative_credits'=> $teachers_with_negative_credits,
				'substitution_info'						=> $substitution_info
			);
		$this->send($batch_info);
		return $batch_info;
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
	 * POST /users/login
	 */
	function user_login() {
		// $this->check_key();
		
		$data = array(
			'username' => $this->input('email'),
			'password' => $this->input('password'),
			'auth_token' => $this->input('auth_token'),
		);
		if(!$data['username'] or (!$data['password'] and !$data['auth_token'])) {
			return $this->error("Username or password not provided.");
		}

		$status = $this->user_model->login($data);
		if(!$status) {
			return $this->error("Invalid Username or password.");
		}

		$this->user_info($status['id']);
	}

	function user_info($user_id) {
		if(!$user_id) $user_id = $this->input('user_id');
		if(!$user_id) return '';
		$user = $this->user_model->user_info($user_id);

		$ed_project_id = 1;
		$fp_project_id = 2;
		$aftercare_project_id = 6;
		$tr_asv_project_id = 4;
		$tr_wingman_project_id = 5;

		$project_id = $ed_project_id;
		$fp_teacher_group_id = 376;
		$fp_mentor_group_id = 386;
		$es_mentor_group_id = 8;
		$aftercare_mentor_group_id = 378; // Aftercare Fellow
		$aftercare_teacher_group_id = 377; // OR 365
		$tr_asv_mentor_group_id = 272; // TR Fellow
		$tr_asv_group_id = 349;
		$tr_wingman_group_id = $tr_wingman_mentor_group_id = 348; // Yeah, both are the same - can't use fellow, because already used. :TODO:
		if(isset($user['groups'][$fp_teacher_group_id])) $project_id = $fp_project_id;
		if(isset($user['groups'][$fp_mentor_group_id])) $project_id = $fp_project_id;

		if(isset($user['groups'][$tr_wingman_group_id])) $project_id = $tr_wingman_project_id;
		if(isset($user['groups'][$tr_asv_group_id])) $project_id = $tr_asv_project_id;
		if(isset($user['groups'][$tr_asv_mentor_group_id])) $project_id = $tr_asv_project_id;

		if(isset($user['groups'][$aftercare_teacher_group_id])) $project_id = $aftercare_project_id;
		if(isset($user['groups'][$aftercare_mentor_group_id])) $project_id = $aftercare_project_id;

		$connections = $this->user_model->get_class_connections($user['id']);
		// dump($connections);exit;
		$mentor = "0";
		if($connections['mentor_at'] or isset($user['groups'][$es_mentor_group_id])) $mentor = "1";

		$this->send(array(
			'user_id'	=> $user['id'],
			'key'		=> $this->key,
			'name'		=> $user['name'],
			'email'		=> $user['email'],
			'city_id'	=> $user['city_id'],
			'credit'	=> $user['credit'],
			'mentor'	=> $mentor,
			'connections'=>$connections,
			'groups'	=> $user['groups'],
			'positions' => $user['positions'],
			'project_id'=> $project_id
		));
	}

	/// Convert this user to a teacher - user_type becomes 'volunteer', add to the Teacher User Group
	/// POST /users/{user_id}/groups/{group_id}
	function user_convert_to_teacher() {
		$this->check_key();
		$user_id = $this->input('user_id');
		$city_id = $this->input('city_id');
		$user = $this->user_model->user_details($user_id);
		if(!$user) $this->error("User($user_id) not found.");

		// Make this user a volunteer
		$data = array(
				'rootId'	=> $user_id,
				'type'		=> 'volunteer'
			);
		if($city_id) $data['city_id'] = $city_id;
		$this->user_model->updateuser($data);
		// Add to teacher user group
		$this->user_model->adduser_to_group($user_id, array(9));

		$this->send(array('user_id' => $user_id, 'name' => $user->name, 'email' => $user->email, 'phone' => $user->phone));
		return true;
	}

	/**
	 * Returns the class details of the last class of the given user.
	 * Arguments :	$user_id
	 * Returns : 	Class Details.
	 * Example : http://makeadiff.in/madapp/index.php/api/?&key=am3omo32hom4lnv32vO
	 * :TODO: GET /users/{user_id}/last_class
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
	 * :TODO: GET /classes
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

	/// Figures out the next or previous class from the given batch, level, and class date.
	/// :TODO: GET /classes
/*
{
  classSearch(batch_id: 2652, from_date:"2019-10-27", direction:"-", level_id:7794, limit: "day") 
  {
    id
    class_on
    batch {
      id
    }
    level {
      id
      name
      center {
        id
        name
      }
    }
    class_type
    class_satisfaction
    cancel_option
    cancel_reason
    status
    teachers {
      id
      name
      credit
      pivot {
        status
        substitute_id
        zero_hour_attendance
      }
    }
    students {
      id
      name
      pivot {
        participation
        check_for_understanding
      }
    }
  }
}
 */
	function browse_class($batch_id = 0, $level_id = 0, $from_date = '', $direction = '+') {
		$this->check_key();

		if(!$batch_id) $batch_id = $this->input('batch_id');
		if(!$level_id) $level_id = $this->input('level_id');
		if(!$from_date) $from_date = $this->input('class_on');
		if(!$direction or $this->input('direction')) $direction = $this->input('direction');

		if(!$from_date and $direction != 'l') { // If from_date is empty and $direction is '=' it should return the latest class.
			$class_from = $this->input('class_from');
			$direction = $this->input('direction');

			// Change the date to the date of the next class in the said direction.
			$next_class = $this->class_model->get_next_class($batch_id, $level_id, $class_from, $direction);
			if(!$next_class) {
				return $this->error("No classes found beyond this point.");
			}
			$from_date = date("Y-m-d", strtotime($next_class->class_on));

		} elseif($direction == 'l') { // Get latest class
			$next_class = $this->class_model->get_next_class($batch_id, $level_id, date("Y-m-d"), $direction);
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
	 * * :TODO: GET /classes/{class_id}
	 * GraphQL call...
	 *  {
		  class(id: 429626) 
		  {
		    id
		    class_on
   		    class_type
		    class_satisfaction
		    cancel_option
		    cancel_reason
		    status

		    batch {
		      id
		    }
		    level {
		      id
		      name
		      center {
		        id
		        name
		      }
		    }
		    teachers {
		      id
		      name
		      credit
		      pivot {
		        status
		        substitute_id
		        zero_hour_attendance
		      }
		    }
		    students {
		      id
		      name
		      pivot {
		        participation
		        check_for_understanding
		      }
		    }
		    substitutes {
		      id
		      name
		    }
		  }
		}
	 */
	function open_class($class_id = 0, $class_info = false, $user_id=0) {
		$this->check_key();
		if(!$class_id) $class_id = $this->input('class_id');
		if(!$user_id) $user_id = $this->input('user_id');

		$class_details = $this->class_model->get_class($class_id);
		if(!$class_info) {
			$class_info = $this->class_model->get_class_by_id($class_id);
			$class_details['center_id'] = $class_info->center_id;
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
			// $check_for_understanding = false;
			// if(isset($student_data['check_for_understanding'][$id]) and $student_data['check_for_understanding'][$id] == '1') $check_for_understanding = true;

			$class_details['students'][$id] = array(
				'name'			=> $name,
				'id'			=> $id,
				'participation' => (!isset($student_data['participation'][$id]) ? 0 : $student_data['participation'][$id]),
				'check_for_understanding'	=> (!isset($student_data['check_for_understanding'][$id]) ? 0 : $student_data['check_for_understanding'][$id]),
			);
		}

		$this->send($class_details);
	}

	/*
	 * Input: user_id, class_id, students - [{student_id, participation}, ]
	 * :TODO: POST /classes/{class_id}/student_attendance | students
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
		$this->class_model->save_class_satisfaction($class_id, $class_satisfaction, $user_id);

		$this->send(array('status' => "Class saved."));
	}

	/**
	 * Returns the last batch of the given user. 
	 * Arguments :	$user_id - ID of the user who's batch must be found.
	 * Returns : 	the last batch that happened for the given user
	 * Example : http://makeadiff.in/madapp/index.php/api/class_get_last_batch?user_id=1&key=am3omo32hom4lnv32vO
	 * :TODO: GET /users/{user_id}/last_batch
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
	/// :TODO: GET /batches/{batch_id}
	function open_batch($batch_id='', $from_date='', $project_id='') {
		$this->check_key();

		// $from_date = '2015-01-11';
		if(!$batch_id) $batch_id = $this->input('batch_id');
		if(!$from_date) $from_date = $this->input('class_on');
		if(!$project_id) $project_id = $this->input('project_id');

		if(!$from_date) {
			$class_from = $this->input('class_from');
			$direction = $this->input('direction');

			// Change the date to the date of the next class in the said direction.
			$next_class = $this->class_model->get_next_class($batch_id, 0, $class_from, $direction);
			if($next_class) $from_date = date("Y-m-d", strtotime($next_class->class_on));
			else $from_date = date("Y-m-d");
		}

		$batch = $this->batch_model->get_batch($batch_id);
		if(!$project_id) $project_id = $batch->project_id;
		
		$center_id = $batch->center_id;
		$center = $this->center_model->get_info($center_id)[0];
		$center_name = $center->name;
		$city_id = $center->city_id;

		$teacher_group_id = getTeacherGroupId($project_id);

		$data = $this->class_model->search_classes(array('batch_id'=>$batch_id, 'from_date'=>$from_date));
		$all_users = $this->user_model->search_users(array('user_type'=>'volunteer', 'status' => '1', 'city_id' => $city_id)); //  'user_group'=>$teacher_group_id,

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
						'substitute'=> ($row->substitute_id != 0 and isset($all_users[$row->substitute_id])) ? $all_users[$row->substitute_id]->name : 'None',
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
					'substitute'	=> ($row->substitute_id != 0 and isset($all_users[$row->substitute_id])) ? $all_users[$row->substitute_id]->name : 'None',
					'zero_hour_attendance'	=> ($row->zero_hour_attendance) ? true : false
				);
			}
		}
		$class_on = '';
		$class_date = '';
		if(isset($data[0]->class_on)) {
			$class_on = date('Y-m-d', strtotime($data[0]->class_on));
			$class_date = date('j M', strtotime($data[0]->class_on));
		}

		// Make the batch name smaller
		$batch_name = str_replace(
			array('Sunday','Monday','Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', ':00', ' 0'), 
			array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat', '', ' '), $batch->name);

		$this->send(array(
				'classes'		=> $classes, 
				'center_name'	=> $center_name,
				'center_id'		=> $center_id,
				'project_id'	=> $project_id,
				'batch_id'		=> $batch_id, 
				'batch_name'	=> $batch_name,
				'class_on' 		=> $class_on,
				'class_date'	=> $class_date,
			));
	}

	/**
	 * Get the enter Mentor view data in one call - just specify which Batch ID should be shown
	 * Arguments:	$batch_id
	 * Returns 	: 	REALLY complicated JSON. Just call it and parse it to see what comes :-P
	 * Example	: 	http://makeadiff.in/madapp/index.php/api/class_get_batch?&key=am3omo32hom4lnv32vO
	 * :TODO: GET /batches/{batch_id}
	 *
	 * 
{
  classSearch(batch_id:2652, direction: "-", limit: "day")
    {
      id
      class_on
      status
      cancel_option
      cancel_reason
      class_type

      batch {
        id
        batch_name
      }
      level {
        id
        level_name
        grade
      }
      teachers {
        id
        name
        pivot {
          substitute_id
          status
          zero_hour_attendance
        }
      }
      substitutes {
        id
        name
      }
    }
  batch(id:2652)
  {
    id
    batch_name
    center {
      id
      name
    }
  }
}
	 */	
	function class_get_batch($batch_id = 0, $class_on = false) {
		$this->check_key();
		// Lifted off classes.php:batch_view
		if(!$batch_id) $batch_id = $this->input('batch_id');
		if(!$class_on) $class_on = $this->input('class_on');

		if(!$batch_id) return $this->error("User doesn't have a batch");

		if(!$class_on) {
			$last_class = $this->class_model->get_last_class_in_batch($batch_id);
			if(!$last_class) return $this->send(array('error' => "This batch does not have any classes in the past."));

			$from_date = date('Y-m-d', strtotime($last_class->class_on));
		} else {
			$from_date = date('Y-m-d', strtotime($class_on));
		}
		
		$this->open_batch($batch_id, $from_date);
	}


	/// Returns all level in the given batch
	/// GET /batches/{batch_id}/levels
	function all_levels_in_batch($batch_id = 0) {
		$this->check_key();
		if(!$batch_id) $batch_id = $this->input('batch_id');
		if(!$batch_id) return $this->error("User doesn't have a batch");
		$levels = $this->batch_model->get_levels_in_batch($batch_id);

		$this->send(array('levels' => $levels));
	}


	/// Save extra classes using the given batch, class date and a collection of level_ids that the user selected using the app.
	/// :TODO: POST /classes
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
	 * GET /users OR /cites/{city_id}/teachers
	 */
	function user_get_teachers() {
		$this->check_key();
		$city_id = $this->input('city_id');
		if(!$city_id) return $this->error("Invalid City ID");
		$project_id = $this->input('project_id');
		if(!$project_id) $project_id = 1;

		if($project_id == 2) { // Foundation
			$groups = array(
				'teacher'	=> 376,
				'mentor'	=> 375,
				'trained'	=> 387
			);
		} else { // Ed Support
			$groups = array(
				'teacher'	=> 9,
				'mentor'	=> 8,
				'trained'	=> 368,
			);
		}

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
		teacher_id[0]=43880
		substitute_id[0]=0
		status[0]='attended'
		zero_hour_attendance[0]=1
		teacher_id[1]=35382
		substitute_id[1]=1
		status[1]='absent'
		zero_hour_attendance[1]=0

	* :TODO: POST /classes
	*/
	function class_save() {
		$this->check_key();

		$class_data = json_decode($this->input('class_data'));
		$user_id = $this->input('user_id');

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
					'user_id'		=> $teacher_id,
					'class_id'		=> $class_id,
					'substitute_id'	=> $substitute_id,
					'status'		=> $status,
					'zero_hour_attendance'	=> $zero_hour_attendance,
				), $user_id);
			}
		}

		$this->send(array('success' => "Class Attendance Updated", 'status'=>'1'));
	}
	
	///	GET /levels/{level_id}/students
	function get_students() {
		$this->check_key();

		$level_id = $this->input('level_id');
		// $user_id = $this->input('user_id');
		$students = $this->level_model->get_kids_in_level($level_id);

		$this->send(array('students' => $students));
		return $students;
	}

	////////////////////////////////////////// Reports ////////////////////////////////

	function teacher_report_aggregate() {
		$level_id = $this->input('level_id');
		$result = array();
		$data = array();
		$this->send_data = false;
		$data['student_attendance'] = $this->teacher_report_student_attendance();
		$data['child_participation'] = $this->teacher_report_child_participation();
		$data['check_for_understanding'] = $this->teacher_report_check_for_understanding();

		foreach($data as $key => $report) {
			if(!isset($result[$key])) $result[$key] = 0; // Initialize.

			foreach($report as $student) {
				if($student['six']['rating'] == 'red') {
					$result[$key]++;
				}
			}
		}

		$this->send_data = true;
		$this->send(array('reports' => $result, 'report_name' => 'teacher_report_aggregate'));
	}

	function _getStudentData($report_type, $report_name, $reduce_function) {
		$level_id = $this->input('level_id');
		$batch_id = $this->input('batch_id');

		$students = $this->level_model->get_kids_in_level($level_id);

		$report = array();
		foreach ($students as $student_id => $student_name) {
			$data = array();
			$data['all'] = $this->kids_model->get_class_details($student_id, $batch_id, $level_id, 0);
			$data['six'] = array_slice($data['all'], 0, 6);

			$return = array();
			foreach ($data as $key => $value) {
				$return[$key] = array(
					'total' => count($data[$key]),
					'sum'	=> array_reduce($data[$key], $reduce_function),
				);
			}
			$return = $this->_rateReportsArray($return, $report_type, $report_name);

			$report[] = array(
				'id'			=> $student_id,
				'name'			=> $student_name,
				'all'			=> $return['all'],
				'six'			=> $return['six']
			);
		}

		$this->send(array('report' => $report, 'report_name' => $report_name));
		return $report;
	}

	/// Returns the absenteeism report for all students who are in the given level
	function teacher_report_student_attendance() {
		return $this->_getStudentData('teacher', 'student_attendance', function($carry, $item) {
						return $carry + $item['present'];
					}, 0);
	}


	/// Get all the students in the given level - and see how their check for understanding has been for the last 6 classes - and all the classes.
	function teacher_report_check_for_understanding() {
		return $this->_getStudentData('teacher', 'check_for_understanding', function($carry, $item) {
						return $carry + $item['check_for_understanding'];
					}, 0);
	}

	/// Get all the students in the given level - and see how their participation has been for the last 6 classes - and all the classes.
	function teacher_report_child_participation() {
		return $this->_getStudentData('teacher', 'child_participation', function($carry, $item) {
						$add = 0;
						if($item['participation'] >=3) $add = 1;
						return $carry + $add;
					}, 0);
	}

	// Get aggregate for all mentor reports. 
	function mentor_report_aggregate() {
		$batch_id = $this->input('batch_id');
		$data = array();
		$this->send_data = false;
		$result = array();
		$data['zero_hour_attendance'] = $this->mentor_report_zero_hour_attendance();
		$data['class_satisfaction'] = $this->mentor_class_satisfaction();
		$data['child_participation'] = $this->mentor_child_participation();
		$data['check_for_understanding'] = $this->mentor_child_check_for_understanding();

		foreach ($data as $report_key => $report_data) {
			if(!isset($result[$report_key])) $result[$report_key] = 0; // Initialize.

			foreach($report_data as $level) {
				if(isset($level['teachers'])) {
					foreach ($level['teachers'] as $teacher_id => $teacher_data) {
						if(isset($teacher_data['six']) and $teacher_data['six']['rating'] == 'red') {
							$result[$report_key]++;
						}
					}
				}
			}
		}

		$this->send_data = true;
		$this->send(array('reports' => $result, 'report_name' => 'mentor_report_aggregate'));
	}

	function _getBatchData($report_name, $reduce_function) {
		$batch_id = $this->input('batch_id');

		$teachers = $this->batch_model->get_batch_teachers($batch_id);
		$all_levels = idNameFormat($this->batch_model->get_levels_in_batch($batch_id));

		$report = array();
		$max_class_count = 0;
		foreach ($teachers as $teach) {
			$teacher_id = $teach->id;
			$data = array();

			// Teacher Reports.
			if($report_name == 'zero_hour_attendance' or $report_name == 'class_satisfaction') {
				$data['all'] = $this->class_model->get_teacher_class_details($teacher_id, $batch_id, 0, 0);

			// Student reports
			} else {
				$data['all'] = $this->class_model->get_student_class_details($teacher_id, $batch_id, 0, 0);
			}
			// if(stripos($teach->name, 'Bhumi') !== false) dump($teach->name, $data['all']);

			$data['six'] = array_slice($data['all'], 0, 6);

			if($max_class_count < count($data['all'])) $max_class_count = count($data['all']);

			if(!isset($report[$teach->level_id])) {
				$report[$teach->level_id] = array(
					'id' 	=> $teach->level_id,
					'name'	=> isset($all_levels[$teach->level_id]) ? $all_levels[$teach->level_id] : ''
				);
			}
			foreach ($data as $key => $value) {
				$return[$key] = array(
					'total' => count($data[$key]),
					'sum'	=> array_reduce($data[$key], $reduce_function),
				);
			}
			$return = $this->_rateReportsArray($return, 'mentor', $report_name);

			$report[$teach->level_id]['teachers'][] = array(
				'id'			=> $teacher_id,
				'name'			=> $teach->name,
				'level'			=> isset($all_levels[$teach->level_id]) ? $all_levels[$teach->level_id] : '',
				'all'			=> $return['all'],
				'six'			=> $return['six']
			);
		}

		$this->send(array('report' => $report, 'levels' => $all_levels, 'max_class_count' => $max_class_count));
		return $report;
	}

	/// Get Zero Hour Attendance for everyone in the given batch
	function mentor_report_zero_hour_attendance() {
		return $this->_getBatchData('zero_hour_attendance', function($carry, $item) {
						return $carry + $item['zero_hour_attendance'];
					}, 0);
	}

	/// Get Class Satisfaction for everyone in the given batch
	function mentor_class_satisfaction() {
		return $this->_getBatchData('class_satisfaction', function($carry, $item) {
						$add = 0;
						if($item['class_satisfaction'] >= 3) $add = 1;
						return $carry + $add;
					}, 0);
	}

	function mentor_child_participation() {
		return $this->_getBatchData('child_participation', function($carry, $item) {
						$add = 0;
						if($item['participation'] >= 3) $add = 1;
						return $carry + $add;
					}, 0);
	}

	function mentor_child_check_for_understanding() {
		return $this->_getBatchData('check_for_understanding', function($carry, $item) {
						return $carry + $item['check_for_understanding'];
					}, 0);
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

	/// GET /cities/{city_id}/center
	function get_centers_in_city() {
		$city_id = $this->input('city_id');

		$centers = $this->center_model->get_all($city_id);
		$this->send(array('centers' => $centers));
	}

	// :TODO: GET /centers/{center_id}
	function get_batches_and_levels_in_center() {
		$center_id = $this->input('center_id');
		$project_id = $this->input('project_id');
		
		$batches = $this->batch_model->get_batches_in_center($center_id, $project_id);
		$levels = $this->level_model->get_all_level_names_in_center($center_id, $project_id);
		$connection = $this->batch_model->get_batch_level_connections($center_id, $project_id);

		$this->send(array('batches' => $batches, 'levels' => $levels, 'connection' => $connection));
	}


	///////////////////////////// Impact Survey Calls /////////////////////////
	function active_is_event() {
		$level_id = $this->input('level_id');
		$user_id = $this->input('teacher_id');
		$this->load->model("Impact_survey_model");
		$is_event = $this->Impact_survey_model->get_active_event($level_id, $user_id);

		$this->send(array('is_event' => $is_event));
		return $is_event;
	}
	// Creating some static calls for the time being.
	function is_questions() {
		$vertical_id = $this->input('vertical_id');
		if(!$vertical_id) $vertical_id = 9;

		$this->load->model('impact_survey_model');

		$questions = $this->impact_survey_model->get_questions($vertical_id);

		$this->send(array('questions' => $questions));
	}

	function is_existing_responses() {
		$is_event_id = $this->input('is_event_id');
		$student_ids = $this->input('student_ids');
		$user_id = $this->input('teacher_id');

		$this->load->model('impact_survey_model');

		$all_responses = array();
		foreach ($student_ids as $student_id) {
			$responses = $this->impact_survey_model->get_response($is_event_id, $student_id, $user_id);

			$student_responses = array();
			foreach ($responses as $res) {
				$student_responses[$res->question_id] = intval($res->response);
			}
			$all_responses[$student_id] = $student_responses;
		}

		$this->send(array('response' => $all_responses));
	}

	function is_save() {
		$this->load->model('impact_survey_model');
		$is_event_id = $this->input('is_event_id');
		$question_id = $this->input('question_id');
		$student_id = $this->input('student_id');
		$user_id = $this->input('teacher_id');

		$data = array(
			'is_event_id' => $is_event_id,
			'question_id' => $question_id,
			'response' => $this->input('response'),
			'student_id' => $student_id,
			'user_id' => $user_id
		);

		$user_response_id = $this->impact_survey_model->save_response($data);

		// Find the last survey event
		$last_is_event_id = $this->impact_survey_model->previous_event_id($is_event_id);

		// Find this user's response for that survey for the same student. 
		$last_responses = $this->impact_survey_model->get_response($last_is_event_id, $student_id, $user_id);
		
		// Return the data to client - so that it can be shown.
		$previous_response_to_this_question = 0;
		foreach ($last_responses as $response) {
			if($question_id == $response->question_id) {
				$previous_response_to_this_question = $response->response;
				break;
			}
		}

		$this->send(array(
						'last_response' 	=> $previous_response_to_this_question,
						'user_response_id'	=> $user_response_id,
						'data'				=> $data,
					));
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

	function _rateReportsArray($data, $report_type, $report_name) {
		foreach($data as $i => $value) {
			if(!$data[$i]['sum']) $data[$i]['sum'] = 0;
			if($data[$i]['total'] == 0 and $data[$i]['sum'] == 0) $data[$i]['percentage'] = 0;
			else $data[$i]['percentage'] = intval($data[$i]['sum'] / $data[$i]['total'] * 100);

			$data[$i]['rating'] = 'red';
			if($data[$i]['percentage'] >= $this->report_level_config[$report_type][$report_name][0]) $data[$i]['rating'] = 'green';
			else if($data[$i]['percentage'] >= $this->report_level_config[$report_type][$report_name][1]) $data[$i]['rating'] = 'yellow';
		}

		return $data;
	}

	///////////////////////////////////////// Internal ////////////////////////////////
	function input($name) {
		$return = '';

		$return = $this->input->post($name);
		if(!$return) $this->input->get($name);
		if(!$return and isset($_REQUEST[$name])) $return = $_REQUEST[$name];

		if(!$return) { // Sometimes when data is passed as post, and went content type is application/json it can't be handled by _POST or _GET. Hence...
		// For more details : https://stackoverflow.com/questions/15485354/angular-http-post-to-php-and-undefined
			if(empty($this->_input_data)) {
				$post_data = file_get_contents("php://input");
				$this->_input_data = json_decode($post_data, true);
			}
			$return = $this->_input_data[$name];
		}

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
