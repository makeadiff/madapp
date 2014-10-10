<?php
class Parameter extends Controller {
	var $debug = true;
	var $sql_queries = array(
			/// Volunteer Counting and attendece
			'%total_vols_count%'	=> "SELECT COUNT(U.id) FROM User U 
					INNER JOIN UserGroup UG ON UG.user_id=U.id 
					INNER JOIN `Group` G ON UG.group_id=G.id
					%CITY_CONNECTION_WITH_USER%
					WHERE U.user_type='volunteer' AND G.type='volunteer' %CITY_CONDITION_USING_USER%",
			'%vols_who_left_this_cycle_count%' => "SELECT COUNT(U.id) FROM User U 
					INNER JOIN UserGroup UG ON UG.user_id=U.id 
					INNER JOIN `Group` G ON UG.group_id=G.id
					%CITY_CONNECTION_WITH_USER%
					WHERE U.user_type='let_go' AND U.left_on > '%CYCLE_START_DATE%' AND U.left_on < '%CYCLE_END_DATE%'
						%CITY_CONDITION_USING_USER%",
			'%cr_vols_who_left_this_cycle_count%' => '%vols_who_left_this_cycle_count% AND G.vertical_id=13',
			'%total_cr_vols_count%' => "%total_vols_count% AND G.vertical_id=13",

			'%cfr_vols_who_left_this_cycle_count%' => '%vols_who_left_this_cycle_count% AND G.vertical_id=12',
			'%total_cfr_vols_count%' => "%total_vols_count% AND G.vertical_id=12",

			'%total_ed_vols_count%' => "%total_vols_count% AND G.vertical_id=3",
			'%ed_vols_who_left_this_cycle_count%' => '%vols_who_left_this_cycle_count% AND G.vertical_id=3',

			'%ed_vols_with_positive_credit_count%' => '%total_ed_vols_count% AND U.credit>=0',
			'%ed_vols_with_negetive_credit_count%' => '%total_ed_vols_count% AND U.credit<0',

			'%total_vol_requierement_count%' => "SELECT SUM(U.requirement_count) FROM HR_Volunteer_Request U
						WHERE added_on > '%CYCLE_START_DATE%' AND added_on < '%CYCLE_END_DATE%' %CITY_CONDITION_USING_USER%",

			/// Class info
			'%classes_count%' => "SELECT COUNT(C.id) FROM Class C 
					INNER JOIN Level L ON C.level_id=L.id INNER JOIN Center Ctr ON Ctr.id=L.center_id
					%CITY_CONNECTION_WITH_CENTER%
					WHERE L.year='%YEAR%' AND C.class_on > '%CYCLE_START_DATE%' 
						AND C.class_on < '%CYCLE_END_DATE%' %CITY_CONDITION_USING_CENTER%",
			'%volunteer_class_count%' => "SELECT COUNT(UC.id) FROM UserClass UC
					INNER JOIN Class C ON UC.class_id=C.id 
					INNER JOIN Level L ON C.level_id=L.id 
					INNER JOIN Center Ctr ON Ctr.id=L.center_id 
					%CITY_CONNECTION_WITH_CENTER%
					WHERE L.year='%YEAR%' AND C.class_on > '%CYCLE_START_DATE%' 
						AND C.class_on < '%CYCLE_END_DATE%' %CITY_CONDITION_USING_CENTER%",

			'%classes_cancelled_count%' => "%classes_count% AND C.status='cancelled'",
			'%total_classes_count%' => "%classes_count% AND C.status='happened'",

			'%volunteer_class_absent_count%' => "%volunteer_class_count% AND UC.status='absent'",
			'%volunteer_class_attended_count%' => "%volunteer_class_count% AND UC.status='attended'",
			'%volunteer_substitution_count%' => "%volunteer_class_count% AND UC.status='attended' AND UC.substitute_id!=0",

			/// Events
			'%expected_attendance_of_core_team_meet_total%' => "SELECT COUNT(UE.present) FROM Event E INNER JOIN UserEvent UE ON UE.event_id=E.id
					WHERE E.type='coreteam_meeting' AND E.city_id='%CITY_ID%' AND E.starts_on > '%CYCLE_START_DATE%' 
						AND E.starts_on < '%CYCLE_END_DATE%'",
			'%attendance_of_core_team_meet_total%' => "%expected_attendance_of_core_team_meet_total% AND UE.present='1'",
			'%event_vols_who_left_this_cycle_count%' => '%total_vols_count% AND G.vertical_id=10',
			'%total_event_vols_count%' => "%total_vols_count% AND G.vertical_id=10",


			/// Student Attendence
			'%expected_student_attendance_for_quarter%' => "SELECT COUNT(SC.id) FROM Class C 
					INNER JOIN Level L ON C.level_id=L.id 
					INNER JOIN Center Ctr ON Ctr.id=L.center_id 
					INNER JOIN StudentClass SC ON C.id=SC.class_id
					WHERE Ctr.city_id='%CITY_ID%' AND L.year='%YEAR%' AND C.class_on > '%CYCLE_START_DATE%' 
						AND C.class_on < '%CYCLE_END_DATE%'",
			'%student_attendance_for_quarter%' => "%expected_student_attendance_for_quarter% AND SC.present='1'",

			/// Fundraising
			'%amount_raised_in_this_cycle%' => "SELECT SUM(actual_amount) FROM Target_Data T 
					INNER JOIN User U ON U.id=T.user_id 
					INNER JOIN City ON City.id=U.city_id 
					INNER JOIN UserGroup UG ON UG.user_id=U.id 
					INNER JOIN `Group` G ON UG.group_id=G.id
					WHERE T.cycle=%CYCLE% %CITY_CONDITION_USING_USER%",
			'%target_for_this_cycle%' => "SELECT SUM(target_amount) FROM Target_Data T 
					INNER JOIN User U ON U.id=T.user_id 
					INNER JOIN City ON City.id=U.city_id 
					INNER JOIN UserGroup UG ON UG.user_id=U.id 
					INNER JOIN `Group` G ON UG.group_id=G.id
					WHERE T.cycle=%CYCLE% %CITY_CONDITION_USING_USER%",
			'%amount_raised_in_this_cycle_by_events%'	=> "%amount_raised_in_this_cycle% AND G.vertical_id=10",
			'%amount_raised_in_this_cycle_by_cfr%'	=> "%amount_raised_in_this_cycle% AND G.vertical_id=12",
			'%amount_raised_in_this_cycle_by_cr%'	=> "%amount_raised_in_this_cycle% AND G.vertical_id=13",
			'%target_for_this_cycle_for_events%'	=> "%target_for_this_cycle% AND G.vertical_id=10",
			'%target_for_this_cycle_for_cfr%'	=> "%target_for_this_cycle% AND G.vertical_id=12",
			'%target_for_this_cycle_for_cr%'	=> "%target_for_this_cycle% AND G.vertical_id=13",

			/// Intern Credits
			'%discover_interns_count%' => "%total_vols_count% AND G.vertical_id=6",
			'%pr_interns_count%' => "%total_vols_count% AND G.vertical_id=7",
			'%events_interns_count%' => "%total_vols_count% AND G.vertical_id=10",
			'%cfr_interns_count%' => "%total_vols_count% AND G.vertical_id=12",
			'%cr_interns_count%' => "%total_vols_count% AND G.vertical_id=13",

			'%discover_interns_with_positive_credit_count%' => "%discover_interns_count% AND U.admin_credit > 0",
			'%pr_interns_with_positive_credit_count%' => "%pr_interns_count% AND U.admin_credit > 0",
			'%events_interns_with_positive_credit_count%' => "%events_interns_count% AND U.admin_credit > 0",
			'%cfr_interns_with_positive_credit_count%' => "%cfr_interns_count% AND U.admin_credit > 0",
			'%cr_interns_with_positive_credit_count%' => "%cr_interns_count% AND U.admin_credit > 0",


		);

	function Parameter() {
		parent::Controller();
		$this->load->model('Users_model','user_model');
		$this->load->model('city_model');
		$this->load->model('Review_Parameter_model','review_model');
		
		$this->load->helper('url');
		$this->load->helper('misc');
		$this->year = $this->user_model->year;

		// Replaces the query templates within the queries to its actual value.
		foreach($this->sql_queries as $qkey => $qquery) {
			foreach ($this->sql_queries as $tkey => $tquery) {
				$this->sql_queries[$tkey] = str_replace($qkey, $qquery, $tquery);
			}
		}
		
        $this->cycle = 1; // :TODO: Get current cycle - this is NOT valid

   		// Cache all the parameters.
		$this->parameters = array();
		$all_parameters = $this->review_model->get_all_review_parameters();
		foreach ($all_parameters as $param) {
			if(!isset($this->parameters[$param->vertical_id])) {
				$this->parameters[$param->vertical_id] = array();
			}
			$this->parameters[$param->vertical_id][] = $param;
		}

		// Cache Stakeholder Survey Review Paramters
		$this->ss_parameters = $this->review_model->get_all_ss_parameters();

	}

	function review_all($city_id = 0) {
		$all_users = $this->user_model->get_fellows_or_above($city_id);

		foreach ($all_users as $user) {
			$this->review_user($user->id);
		}
	}

	function review_user($user) {
		list($user_id, $user) = $this->get_user_format($user);
		$this->info("<hr /><h2>Review for {$user->name}({$user->id})</h2>\n");

		$this->review_milestone_parameters($user);
		$this->review_core_parameters($user);
		$this->review_ss_parameters($user);
	}


	///////////////////////////////////////////// Core Metric/Parameters Calculation //////////////////////////////
	function review_core_parameters($user_id) {
		$this->info("<h4>Calculating Core Metrics...</h4>");
		list($user_id, $user_details) = $this->get_user_format($user_id);

		if(!isset($this->parameters[$user_details->vertical_id])) return;

		$all_parameters = $this->parameters[$user_details->vertical_id];

		foreach ($all_parameters as $parameter) {
		 	$this->calculate_parameter($parameter, $user_details);
		}
	}

	function calculate_parameter($parameter, $user_details) {
		$this->info("Calculating <strong>'{$parameter->name}'</strong><br \>\n");
		$user_id = $user_details->id;

		$this->replace_values = array(
				'%CYCLE_START_DATE%'=> '2014-07-01', // :DEBUG: :TODO: :HARDCODE:
				'%CYCLE_END_DATE%'	=> '2014-09-15', 
				'%YEAR%'			=> $this->year,
				'%CYCLE%'			=> $this->cycle,
				'%CITY_ID%' 		=> $user_details->city_id,
				'%CITY_CONNECTION_WITH_CENTER%'	=> '',
				'%CITY_CONNECTION_WITH_USER%'	=> '',
				'%CITY_CONDITION_USING_CENTER%'	=> '',
				'%CITY_CONDITION_USING_USER%'	=> '',
		);

		// Based on wether the user is Fellow, strat or National, change the data that we fetch by city, region and national.
		if($user_details->group_type == 'fellow') {
			$this->replace_values['%CITY_CONNECTION_WITH_CENTER%']	= ''; // Don't bother connecting to city. We don't need Region details.
			$this->replace_values['%CITY_CONNECTION_WITH_USER%']	= '';
			$this->replace_values['%CITY_CONDITION_USING_CENTER%']	= ' AND Ctr.city_id='.$user_details->city_id;
			$this->replace_values['%CITY_CONDITION_USING_USER%']	= ' AND U.city_id='.$user_details->city_id;
		
		// Get based on region - not city.
		} elseif($user_details->group_type == 'strat') {
			$this->replace_values['%CITY_CONNECTION_WITH_CENTER%']	= 'INNER JOIN City ON City.id=Ctr.city_id';
			$this->replace_values['%CITY_CONNECTION_WITH_USER%']	= 'INNER JOIN City ON City.id=U.city_id';
			$this->replace_values['%CITY_CONDITION_USING_USER%']	= ' AND City.region_id='.$user_details->region_id; 
			$this->replace_values['%CITY_CONDITION_USING_CENTER%']	= ' AND City.region_id='.$user_details->region_id;
		}


		if($parameter->sql) {
			$value = $this->get_query_value($parameter->sql, $this->replace_values);

		} elseif($parameter->formula) {
			$keys = array_keys($this->sql_queries);
			foreach ($keys as $i => $value) {
				$keys[$i] = '/'.str_replace('%','\%', $value) .'/';
			}
			$formula = preg_replace_callback($keys, array($this, 'calculate_formula_value'), $parameter->formula);

			try {
				$value = @eval("return ($formula);");
			} catch(Exception $e) {
				throw new Exception('Error: ', 0, $e);
			}
		}

		$value = round($value, 2);

		$this->info("Formula: <em>{$parameter->formula}</em><br />Result: <strong>$formula</strong><br />Value: <u>$value</u><br />");

		$level = 0;
		$levels = array(0,
			$parameter->level_1,
			$parameter->level_2,
			$parameter->level_3,
			$parameter->level_4,
			$parameter->level_5);


		// Decide which order we use to compare the level values. From top or bottom.
		if($parameter->start_compare == '1') {
			for($i=1; $i<=5; $i++) {
				$calc = $this->sanitize($value . $levels[$i]);

				if(eval("return (( " . $calc . " ) ? true : false);")) {
					$level = $i;
					break;
				}
				
			}

		} elseif($parameter->start_compare == '5') {
			for($i=5; $i<=0; $i++) {
				$calc = $this->sanitize($value . $levels[$i]);

				if(eval("return (( " . $calc . " ) ? true : false);")) {
					$level = $i;
					break;
				}
			}
		}

		$flags = array('nothing', 'black','red','orange','yellow','green');

		$this->review_model->save(array(
				'review_parameter_id'	=> $parameter->id,
				'type'			=> 'parameter',
				'value'			=> $value,
				'level'			=> $level,
				'comment'		=> "Calculation:\n" . $parameter->formula . "\n" . $formula,
				'input_type'	=> 'automated',
				'review_period'	=> 'cycle',
				'cycle'			=> $this->cycle,
				'updated_on'	=> date("Y-m-d H:i:s"),
				'user_id'		=> $user_id
			));
		$this->info("Saved '<strong>{$parameter->name}</strong>': $value - Level: $level<br /><br />");
	}

	// Finds the keys that should be replaced in the formula and replace it with the query - then execute it and return the value.
	function calculate_formula_value($match)  {
		$value = $this->get_query_value($this->sql_queries[$match[0]], $this->replace_values);
		return $value;
	}

	// Replace all the small elements in query like '%CITY_ID%' and return the value.
	function get_query_value($sql, $replace_values) {
		$sql = str_replace(array_keys($replace_values), array_values($replace_values), $sql);

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
	function review_milestone_parameters($user_id) {
		$this->info("<h4>Calculating Milestones...</h4>");

		list($user_id, $user) = $this->get_user_format($user_id);

		$all_milestones = $this->review_model->get_all_milestones($user->id, $this->cycle);

		foreach ($all_milestones as $milestone) {
			$this->calculate_milestone_level($milestone);
		}
	}


	function calculate_milestone_level($milestone) {
		$this->info("Calculating <strong>'{$milestone->name}'</strong><br \>\n");

		$days_taken = -20;
		$due_on = new DateTime($milestone->due_on);
		if($milestone->status) {
			$done_on = new DateTime($milestone->done_on);
			$interval = $done_on->diff($due_on);
			$days_taken = intval($interval->format('%R%a'));
		
		} else {
			// If the due date is in the past and still work is not done, level it.
			$today = new DateTime();
			$interval = $today->diff($due_on);
			$days_taken = intval($interval->format('%R%a'));
			if($days_taken > 0) $days_taken = -20; // If due date has not arrived yet, don't level.
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
		$this->info("Saved '{$milestone->name}': Days taken: $days_taken | Level: $level<br /><br />");

		return $level;
	}

	/////////////////////////////////////// Stakeholder Calculations ////////////////////////////
	function review_ss_parameters($user_id) {
		$this->info("<h4>Calculating Survey Parameters...</h4>");

		list($user_id, $user) = $this->get_user_format($user_id);

		foreach($this->ss_parameters as $rule) {
			if($rule->vertical_id != $user->vertical_id) continue;
			if($rule->volunteer_type != $user->group_type) continue; // If the rule if for a strat, don't let others thru.

			$this->ss_calulate($user, $rule);
		}
	}

	function ss_calulate($user, $parameter) {
		$this->info("Calculating {$parameter->name}<br />");
		$user_id = $user->id;

		$joins = array();
		// Join the tables only if the conditions calls for it.
		if((strpos($parameter->conditions, 'vertical_id') !== false)
			or (strpos($parameter->conditions, 'G.type') !== false)) $joins[] = "INNER JOIN UserGroup UG ON UG.user_id=U.id INNER JOIN `Group` G ON G.id=UG.group_id";
		if(strpos($parameter->conditions, 'center_id') !== false) $joins[] = "INNER JOIN UserBatch UB ON UB.user_id=U.id INNER JOIN Batch B ON B.id=UB.batch_id" ;
		if(strpos($parameter->conditions, 'region_id') !== false) $joins[] = "INNER JOIN City C ON C.id=U.city_id " ;

		$replaces = array(
			'%user_city_id%'	=> $user->city_id,
			'%user_region_id%'	=> $user->region_id,
			'%user_center_id%'	=> isset($user->centers[0]) ? $user->centers[0] : 0,
		);

		$conditions = str_replace(array_keys($replaces), array_values($replaces), $parameter->conditions);

		$where = '';
		if(trim($conditions)) $where = " WHERE {$conditions}";

		$data = $this->db->query("SELECT UA.question_id, UA.answer FROM SS_UserAnswer UA 
			INNER JOIN User U ON U.id=UA.user_id " . implode(" ", $joins) . $where)->result();
		$answers = array();

		// This code is replicated in review.php:aggregate() too. 
		
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
			$level = round($aggregate / $total_answer_count, 2);

			$this->review_model->save(array(
				'review_parameter_id'	=> $question_id,
				'type'			=> 'survey',
				'value'			=> $total_answer_count,
				'level'			=> $level,
				'name'			=> $parameter->name,
				'input_type'	=> 'automated',
				'review_period'	=> 'cycle',
				'comment'		=> "Level 1: $values[1], Level 3: $values[3], Level 5: $values[5]",
				'cycle'			=> $this->cycle,
				'updated_on'	=> date("Y-m-d H:i:s"),
				'user_id'		=> $user_id
			));
			$this->info("Q $question_id) Level: $level. Data: Level 1: $values[1], Level 3: $values[3], Level 5: $values[5]<br />");
		}
		$this->info("<br />");
	}


	/////////////////////////////////////// Debug Stuff ////////////////////////////
	function info($data) {
		if(!$this->debug) continue;

		print $data;
	}

	function get_user_format($user_id) {
		// User info can be passed as a ID or as an Array. Do appropriate things to it.
		if(is_numeric($user_id)) {
			$user_details = $this->user_model->get_info($user_id);
		
		} else { // Its an array!
			$user_details = $user_id;
			$user_id = $user_details->id;

			// Is a strat? Get region too.
			if($user_details->group_type == 'strat') {
				$city_info = $this->city_model->getCity($user_details->city_id);
				$user_details->region_id = $city_info['region_id'];
			}
		}

		return array($user_id, $user_details);
	}

	// Encase numbers within () so that the values come out right
	function sanitize($expression) {
		$exp = preg_replace(array('/([\d\.]+)/'),array("($1)"), $expression);

		return $exp;
	}


}

