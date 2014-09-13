<?php
class Parameter extends Controller {
	var $debug = true;
	var $sql_queries = array(
			'%total_vols_count%'	=> "SELECT COUNT(U.id) FROM User U 
					INNER JOIN UserGroup UG ON UG.user_id=U.id 
					INNER JOIN `Group` G ON UG.group_id=G.id
					WHERE U.user_type='volunteer'  AND U.city_id='%CITY_ID%' AND G.type='volunteer'",
			'%vols_who_left_this_cycle_count%' => "SELECT COUNT(U.id) FROM User U 
					INNER JOIN UserGroup UG ON UG.user_id=U.id 
					INNER JOIN `Group` G ON UG.group_id=G.id
					WHERE U.user_type='let_go' AND U.left_on > '%CYCLE_START_DATE%' AND U.left_on < '%CYCLE_END_DATE%'  AND U.city_id='%CITY_ID%'",
			'%classes_count%' => "SELECT COUNT(C.id) FROM Class C 
					INNER JOIN Level L ON C.level_id=L.id INNER JOIN Center Ctr ON Ctr.id=L.center_id 
					WHERE Ctr.city_id='%CITY_ID%' AND L.year='%YEAR%' AND C.class_on > '%CYCLE_START_DATE%' 
						AND C.class_on < '%CYCLE_END_DATE%'",
			'%volunteer_class_count%' => "SELECT COUNT(UC.id) FROM UserClass UC
					INNER JOIN Class C ON UC.class_id=C.id 
					INNER JOIN Level L ON C.level_id=L.id INNER JOIN Center Ctr ON Ctr.id=L.center_id 
					WHERE Ctr.city_id='%CITY_ID%' AND L.year='%YEAR%' AND C.class_on > '%CYCLE_START_DATE%' 
						AND C.class_on < '%CYCLE_END_DATE%'",

			'%expected_attendance_of_core_team_meet_total%' => "SELECT COUNT(UE.present) FROM Event E INNER JOIN UserEvent UE ON UE.event_id=E.id
					WHERE E.type='coreteam_meeting' AND E.city_id='%CITY_ID%' AND E.starts_on > '%CYCLE_START_DATE%' 
						AND E.starts_on < '%CYCLE_END_DATE%'",
			'%attendance_of_core_team_meet_total%' => "%expected_attendance_of_core_team_meet_total% AND UE.present='1'",

			
			'%expected_student_attendance_for_quarter%' => "SELECT COUNT(SC.id) FROM Class C 
					INNER JOIN Level L ON C.level_id=L.id 
					INNER JOIN Center Ctr ON Ctr.id=L.center_id 
					INNER JOIN StudentClass SC ON C.id=SC.class_id
					WHERE Ctr.city_id='%CITY_ID%' AND L.year='%YEAR%' AND C.class_on > '%CYCLE_START_DATE%' 
						AND C.class_on < '%CYCLE_END_DATE%'",
			'%student_attendance_for_quarter%' => "%expected_student_attendance_for_quarter% AND SC.present='1'",




			'%total_ed_vols_count%' => "%total_vols_count% AND G.vertical_id=3",
			'%ed_vols_with_positive_credit_count%' => '%total_ed_vols_count% AND U.credit>=0',
			'%ed_vols_with_negetive_credit_count%' => '%total_ed_vols_count% AND U.credit<0',
			'%ed_vols_who_left_this_cycle_count%' => '%vols_who_left_this_cycle_count% AND G.vertical_id=3',

			'%event_vols_who_left_this_cycle_count%' => '%total_vols_count% AND G.vertical_id=10',
			'%total_event_vols_count%' => "%total_vols_count% AND G.vertical_id=10",

			'%cr_vols_who_left_this_cycle_count%' => '%total_vols_count% AND G.vertical_id=13',
			'%total_cr_vols_count%' => "%total_vols_count% AND G.vertical_id=13",

			'%cfr_vols_who_left_this_cycle_count%' => '%total_vols_count% AND G.vertical_id=12',
			'%total_cfr_vols_count%' => "%total_vols_count% AND G.vertical_id=12",

			'%classes_cancelled_count%' => "%classes_count% AND C.status='cancelled'",
			'%total_classes_count%' => "%classes_count% AND C.status='happened'",

			'%volunteer_class_absent_count%' => "%volunteer_class_count% AND UC.status='absent'",
			'%volunteer_class_attended_count%' => "%volunteer_class_count% AND UC.status='attended'",
			'%volunteer_substitution_count%' => "%volunteer_class_count% AND UC.status='attended' AND UC.substitute_id!=0",
		);

	function Parameter() {
		parent::Controller();
		$this->load->model('Users_model','user_model');
		$this->load->model('city_model');
		$this->load->model('Review_Parameter_model','review_model');
		
		$this->load->helper('url');
		$this->load->helper('misc');

		// Replaces the query templates within the queries to its actual value.
		foreach($this->sql_queries as $qkey => $qquery) {
			foreach ($this->sql_queries as $tkey => $tquery) {
				$this->sql_queries[$tkey] = str_replace($qkey, $qquery, $tquery);
			}
		}
		
        $this->cycle = 1; // :TODO: Get current cycle - this is NOT valid
	}

	function review_all() {
		$all_users = $this->user_model->get_users_in_city(26); // Leadership City is 26

		//$this->review_parameter_user($user_id)
		foreach ($all_users as $user) {
			"<h3>Review for {$user->name}</h3>\n";
			$this->review_user($user->id);
		}
	}
	function review_user($user_id) {
		$this->review_milestones_user($user_id);
	}


	///////////////////////////////////////////// Core Metric/Parameters Canculation //////////////////////////////
	function review_parameter_user($user_id, $parameter_id) {
		$user_details = $this->db->query("SELECT * FROM User WHERE id=$user_id")->row_array();
		$all_parameters = $this->db->query("SELECT * FROM Review_Parameter WHERE id=$parameter_id")->result_array();

		foreach ($all_parameters as $parameter) {
			$this->calculate_parameter($parameter, $user_details);
		}
	}

	function calculate_parameter($parameter, $user_details) {
		$user_id = $user_details['id'];

		$this->replace_values = array(
				'%CYCLE_START_DATE%'=> '2014-01-01', // :DEBUG: :TODO: :HARDCODE:
				'%CYCLE_END_DATE%'	=> '2014-07-15', 
				'%YEAR%'			=> '2014',
				'%CITY_ID%' 		=> $user_details['city_id'],
			);


		if($parameter['sql']) {
			$value = $this->get_query_value($parameter['sql'], $this->replace_values);

		} elseif($parameter['formula']) {
			$this->info($parameter['formula']);
			$keys = array_keys($this->sql_queries);
			foreach ($keys as $i => $value) {
				$keys[$i] = '/'.str_replace('%','\%', $value) .'/';
			}
			$formula = preg_replace_callback($keys, array($this, 'calculate_formula_value'), $parameter['formula']);

			try {
				$value = @eval("return ($formula);");
			} catch(Exception $e) {
				throw new Exception('Error: ', 0, $e);
			}

			if($this->debug) echo "Formula: <em>$parameter[formula]</em><br />Result: <strong>$formula</strong><br />Value: <u>$value</u><br />";
		}


		$level = 0;

		// Decide which order we use to compare the level values. From top or bottom.
		if($parameter['start_compare'] == '1') {
			for($i=1; $i<5; $i++) {
				if(eval("return (( " . $value . $parameter['level_'.$i] . " ) ? true : false);")) {
					$level = $i;
					break;
				}
			}
		} elseif($parameter['start_compare'] == '5') {
			for($i=5; $i<0; $i++) {
				if(eval("return (( " . $value . $parameter['level_'.$i] . " ) ? true : false);")) {
					$level = $i;
					break;
				}
			}
		}

		$flags = array('nothing', 'black','red','orange','yellow','green');

		$this->review_model->save(array(
				'review_parameter_id'	=> $parameter['id'],
				'type'			=> 'parameter',
				'value'			=> $value,
				'level'			=> $level,
				'comment'		=> "Calculation:\n" . $parameter['formula'] . "\n" . $formula,
				'input_type'	=> 'automated',
				'review_period'	=> 'cycle',
				'cycle'			=> $this->cycle,
				'updated_on'	=> date("Y-m-d H:i:s"),
				'user_id'		=> $user_id
			));
		print "Saved $parameter[name]: $value<br />";
	}

	// Finds the keys that should be replaced in the formula and replace it with the query - then execute it and return the value.
	function calculate_formula_value($match)  {
		$value = $this->get_query_value($this->sql_queries[$match[0]], $this->replace_values);
		return $value;
	}

	// Replace all the small elements in query like '%CITY_ID%' and return the value.
	function get_query_value($sql, $replace_values) {
		$sql = str_replace(array_keys($replace_values), array_values($replace_values), $sql);
		$this->info($sql);
		$data = $this->db->query($sql)->result_array();

		// Convert data to single value.
		$value = $this->get_single_value($data);
		if(!$value) $value = $this->get_first_value($data);
		return $value;
	}

	function get_single_value($data) {
		if(count($data) == 1) {
			$new_data = $data[0];
			if(count($new_data) == 1) {
				$last_data = array_values($new_data);
				return $last_data[0];
			}
		}

		return false;
	}

	function get_first_value($data) {
		$new_data = $data[0];
		if(count($new_data)) {
			$last_data = array_values($new_data);
			return $last_data[0];
		}

		return false;
	}


	/////////////////////////////////////// Milestone Calculations ///////////////////////////////

	function review_milestones_user($user_id) {
		$all_milestones = $this->review_model->get_all_milestones($user_id, $this->cycle);

		foreach ($all_milestones as $milestone) {
			$this->calculate_review_level($milestone);
		}


	}

	function calculate_review_level($milestone) {
		$days_taken = -20;
		if($milestone->status) {
			$due_on = new DateTime($milestone->due_on);
			$done_on = new DateTime($milestone->done_on);
			$interval = $due_on->diff($done_on);
			$days_taken = intval($interval->format('%R%a'));
		}
		$level = 5;
		if($days_taken <= -7) $level = 1;
		elseif($days_taken <= -2) $level = 2;
		elseif($days_taken < 2) $level = 3;
		elseif($days_taken >= 2 and $days_taken < 7) $level = 4;
		elseif($days_taken >= 7) $level = 5;

		$this->review_model->save(array(
			'review_parameter_id'	=> $milestone->id,
			'type'			=> 'milestone',
			'value'			=> $days_taken,
			'level'			=> $level,
			'input_type'	=> 'automated',
			'review_period'	=> 'cycle',
			'cycle'			=> $this->cycle,
			'updated_on'	=> date("Y-m-d H:i:s"),
			'user_id'		=> $milestone->user_id
		));
		print "Saved '{$milestone->name}': $days_taken : $level<br />";

		return $level;
	}

	/////////////////////////////////////// Stakeholder Calculations ////////////////////////////
	function stakeholder_calculate_parameters() {
		/*
		$rules = 'SELECT * FROM Review_SS_Parameter'

		foreach($rules as $rule) {
			$person = 'SELECT * FROM User WHERE vertical=$rule['vertical']'

			$value = $this->stakeholder_calculate_value($rule[formula], $vertical, $person['city_id'], $person['region_id'])

		}
		*/
	}

	function stakeholder_calulate_value($ss_parameter_id, $user_id) {
		$parameter = $this->db->query("SELECT * FROM Review_SS_Parameter WHERE id=$ss_parameter_id")->row();
		$user = $this->user_model->get_info($user_id);

		$joins = array();
		// Join the tables only if the conditions calls for it.
		if(strpos($parameter->conditions, 'vertical_id')) $joins[] = " INNER JOIN `Group` G ON G.id=UG.group_id INNER JOIN UserGroup UG ON UG.user_id=U.id ";
		if(strpos($parameter->conditions, 'center_id')) $joins[] = "INNER JOIN Batch B ON B.id=UG.batch_id INNER JOIN UserBatch UB ON UB.user_id=U.id " ;

		$replaces = array(
			'%user_city_id%'	=> $user->city_id,
			'%user_center_id%'	=> isset($user->centers[0]) ? $user->centers[0] : 0,
		);

		$conditions = str_replace(array_keys($replaces), array_values($replaces), $parameter->conditions);

		$data = $this->db->query("SELECT UA.question_id, UA.answer FROM SS_UserAnswer UA INNER JOIN User U ON U.id=UA.user_id " . implode(" ", $joins) . " WHERE {$conditions}")->result();
		$answers = array();

		foreach ($data as $ans) {
			// If not defined, define the defaults
			if(!isset($answers[$ans->question_id])) $answers[$ans->question_id] = array(1=>0,3=>0,5=>0);

			$answers[$ans->question_id][$ans->answer]++;
		}

		// Save values to Database
		foreach ($answers as $question_id => $values) {
			// Find level by aggregating the total and averaging.
			// If there are 5 answers - 1 x Level 1, 2 x Level 3 and 2 x Level 5, we aggregate it - (1 x 1) + (2 x 3) + (2 x 5) = 17
			//	Then we divide by total count : 17/5 = 3.4. Rounds to 3. Thats the level.
			$aggregate = 0;
			$total_answer_count = 0;

			foreach(array(1,3,5) as $answer_value) {
				$aggregate += $answer_value * $values[$answer_value];
				$total_answer_count += $values[$answer_value];
			}
			$level = round($aggregate / $total_answer_count);

			foreach ($values as $answer_value => $answer_count) {
				$this->review_model->save(array(
					'review_parameter_id'	=> $question_id,
					'type'			=> 'survey',
					'value'			=> $answer_count,
					'level'			=> $level,
					'input_type'	=> 'automated',
					'review_period'	=> 'cycle',
					'comment'		=> "Level 1: $values[1], Level 3: $values[3], Level 5: $values[5]",
					'cycle'			=> $this->cycle,
					'updated_on'	=> date("Y-m-d H:i:s"),
					'user_id'		=> $user_id
				));
			}
		}
	}


	/////////////////////////////////////// Debug Stuff ////////////////////////////
	function info($data) {
		if(!$this->debug) continue;

		print "<pre>".$data."</pre>";
	}


}

