<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Controller  {
    function Cron() {
        parent::Controller();
        header("Content-type: text/plain");
        
        $this->load->model('Class_model','class_model', TRUE);
	}
	
	// This is one of the most improtant functions. Makes all the classes for the next two weeks using the data in the Batch table.
	function schedule_classes($debug=0) {
		$this->load->model('Batch_model','batch_model', TRUE);
		$this->batch_model->year = 2013; // Current Year. :HARDCODE:
		$this->batch_model->project_id = 1;
		$all_batches = $this->batch_model->get_all_batches(true);
		
		if($debug) {
			print "Debug Mode\n---------------------------\n";
			print "Batches: " . count($all_batches);
		}
		
		// Wee have to add all the classes for the next two weeks.
		for($week = 0; $week < 2; $week++) {
			foreach($all_batches as $batch) {
				//if($batch->id != 368) continue; //:DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
				$teachers = $this->batch_model->get_batch_teachers($batch->id);
				
				list($hour, $min, $secs) = explode(":", $batch->class_time);
				
				// This is how we find the next sunday, monday(whatever is in the $batch->day).
				$date_interval = intval($batch->day) - date('w');
				if($date_interval <= 0) $date_interval += 7;
				$day = date('d') + $date_interval;
				
				$day = $day + ($week * 7); // We have to do this for two weeks. So in the first iteration, this will be 0 and in next it will be 7.
							
				$time = mktime($hour, $min, $secs, date('m'), $day, date("Y"));
				$date = date("Y-m-d H:i:s", $time);
				
				if($debug) dump($teachers, $date, $batch);
				
				foreach($teachers as $teacher) {
					//if($teacher->id != 496) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
					
					// Make sure its not already inserted.
					if(!$this->class_model->get_by_teacher_time($teacher->id, $date)) {
						print "Class by {$teacher->id} at $date\n";
						
						$class_data = array(
							'batch_id'	=> $batch->id,
							'level_id'	=> $teacher->level_id,
							'teacher_id'=> $teacher->id,
							'substitute_id'=>0,
							'class_on'	=> $date,
							'status'	=> 'projected'
						);
						$this->class_model->save_class($class_data);
					}
				}
				
				if($debug) print "++++++++++++++++++++++++++++++++++++++++++++++\n";
			}
		}
	}
	
	/// Copies all the existing credits over to the Archive table and reset credits to 3.
	function archive_credits() {
		$this->load->model('Users_model', 'users_model');
		$last_year = 2012;
		
		$users = $this->users_model->db->query("SELECT User.id,credit FROM User INNER JOIN UserGroup ON User.id=UserGroup.user_id 
				WHERE UserGroup.group_id=9 AND user_type='volunteer'")->result(); // 9 is Teacher Group
		
		$data = array();
		$count = 0;
		foreach($users as $u) {
			$data[] = "{$u->id}, 'user_english_credit', '{$u->credit}', '$last_year', NOW()";
			$this->users_model->db->query("UPDATE User SET credit=3 WHERE id={$u->id}");
			$count++;
		}
		$this->users_model->db->query("INSERT INTO Archive(user_id, name, value, year, added_on) VALUES (" . implode("),(", $data) . ")");
		print "Saved engish credits of $count people.\n";
		
		// Interns
		$admin_users = $this->users_model->db->query("SELECT User.id,admin_credit FROM User INNER JOIN UserGroup ON User.id=UserGroup.user_id 
				WHERE UserGroup.group_id=14 AND user_type='volunteer'")->result(); // 14 is Admin Group
		
		$data = array();
		$count = 0;
		foreach($admin_users as $u) {
			$data[] = "{$u->id}, 'user_admin_credit', '{$u->admin_credit}', '$last_year', NOW()";
			$this->users_model->db->query("UPDATE User SET admin_credit=0 WHERE id={$u->id}");
			$count++;
		}
		$this->users_model->db->query("INSERT INTO Archive(user_id, name, value, year, added_on) VALUES (" . implode("),(", $data) . ")");
		print "Saved admin credits of $count people.\n";
	}
	
	/// Send SMSs to people who haven't confirmed their classes.
	function send_unconfirmed_class_sms() {
		$this->load->model('Center_model','center_model', TRUE);
		$this->load->model('Batch_model','batch_model', TRUE);
		$this->load->library('sms');
		
		$all_centers = idNameFormat($this->center_model->get_all_centers());
		$people = $this->class_model->get_unconfirmed_classes(2);
		
		$unconfirmed_people = array();
		
		foreach($people as $person) {
			$name = short_name($person->name);
			$class_timestamp = strtotime($person->class_on);

			// The class is 2 days away(at least, more than 1 day away).
			if(($class_timestamp - time()) > 60 * 60 * 24) {
				$this->sms->send('91'.$person->phone, "$name, you have a class at {$all_centers[$person->center_id]} on " . date('dS M, h:i A', $class_timestamp) 
					. ". Visit http://makeadiff.in/madapp/ to confirm your class or assign a substitute if you are unable to take the class.");
					//  ^ Reply 'confirm' to confirm this class. 
			
			// The class is happening tomorrow.
			} else {
				// Send a SMS to the batch head saying that there was a person who did not confirm their class. First, collect their names.
				if(!isset($unconfirmed_people[$person->batch_id])) $unconfirmed_people[$person->batch_id] = array();
				$unconfirmed_people[$person->batch_id][] = $name;
				
				$this->sms->send('91'.$person->phone, "$name, this is the final call to confirm your attendance for the class at {$all_centers[$person->center_id]} on " . date('dS M, h:i A', $class_timestamp) 
					. ". Visit http://makeadiff.in/madapp/ to confirm your class or assign a substitute if you are unable to take the class.");
			}
		}
		
		// Send the batch head a list of people who didn't confirm for the class.
		foreach($unconfirmed_people as $batch_id => $name_list) {
			$batch_head = $this->batch_model->get_batch_head($batch_id);
			$this->sms->send('91'.$batch_head->phone, short_name($batch_head->name) . ", the following people have not yet confirmed their class: " . implode(', ', $name_list)
					. ". Please take the necessary steps to make sure that the classes happen.");
		}
	}
	
	/// Sometimes, the credits go bad. In such cases, rebuild the credits using the credit history.
	function recalculate_credits($city_id=0) {
		$this->load->model('users_model');
		
		$conditions = array('user_type'=>'volunteer', 'status' => '1', 'user_group'=>9, 'project_id'=>1,'city_id'=>false);
		if($city_id) $conditions['city_id'] = $city_id;
		$all_users = $this->users_model->search_users($conditions);
		
		print "Recalculating credits of " . count($all_users) . " users.\n";
		foreach($all_users as $user) {
			print $user->id . ") " . $user->name;
			$this->users_model->year = 2013; // Current year. :HARDCODE:
			$this->users_model->recalculate_user_credit($user->id, true, true);
			print "\n";
		}
	}
	
	/// Sometimes, the classes linger in the database even after the user has been removed from the batch. This function clears that.
	function delete_orphan_classes() {
		$this->load->model('Batch_model','batch_model');
		$this->load->model('Center_model','center_model');
		$this->load->model('Level_model','level_model');
		$this->load->model('City_model','city_model');
		
		
		$all_cities = $this->city_model->get_all();
		foreach($all_cities as $city) {
			$all_centers = $this->center_model->get_all($city->id);
			
			foreach($all_centers as $center) {
				$batches = $this->batch_model->get_class_days($center->id);
				$all_levels = $this->level_model->get_all_levels_in_center($center->id);
				
				foreach($batches as $batch_id => $batch_name) {
					foreach($all_levels as $level) {
						// Get people in this level.
						$actual_teachers = $this->batch_model->get_teachers_in_batch_and_level($batch_id, $level->id);
						
						// Get people shown in madsheet in this level - from the class and userclass table.
						$shown_classes = $this->class_model->get_classes_by_level_and_batch($level->id, $batch_id);
						foreach($shown_classes as $class) {
							// If the class is in the future and the teacher is not in the batch...
							if($class->class_on > date('Y-m-d H:i:s') and 
									!in_array($class->user_id, $actual_teachers)) {
								$this->class_model->delete_future_classes($class->user_id, $batch_id, $level->id);// delete the people missing from the level.
								$actual_teachers[] = $class->user_id; // So that we won't have to delete this user all over again. The parent if condition will prevent that.
								print $class->user_id . " is missing - deleted his/her future classes.\n";
							}
							
						}
					}
				}
			}
		}
	}
	
	/// This will calculate the Stats necessary for the monthly review
	function monthly_review_stats_collection($year_month='', $city_id = false) {
		if(!$year_month) $year_month = date('Y-m', strtotime('last month'));
		list($year, $month) = explode("-", $year_month);
		if($month < 4) $year--;
		
		$this->load->model('report_model');
		$this->load->model('city_model');
		$this->load->model('users_model');
		$this->load->model('event_model');
		$this->load->model('kids_model');
		$this->load->model('review_model');
		$this->event_model->year = $year;
		$this->class_model->year = $year;

		$project_id = 1;
		if(!$city_id) {
			$cities = $this->city_model->get_all();
		} else {
			$array = array( 'id' => $city_id );
			$cities = array((object) $array);
		}
		
		foreach($cities as $city) {
			//if($city->id != 10) continue; // :DEBUG:
			
			print "Collecting data for city: {$city->id}...\n";
			
			$this->event_model->city_id = $city->id;
			$categories = array();
			$flags = array();
			
			$categories = array(
				'class_count'							=> 0,
				'fellows_count'							=> 0,
				// Ops
				'absent_without_substitute_count'		=> 0,
				'absent_without_substitute_percentage'	=> 0,
				'negative_credit_volunteer_count'		=> 0,
				'negative_credit_volunteer_percentage'	=> 0,
				'substitute_percentage'					=> 0,
				'attended_kids_percentage'				=> 0,
				'madapp_updated_ops'					=> -1,
				'madapp_volunteer_attendance_marked'	=> 1,
				'madapp_student_attendance_marked'		=> 1,
				'madapp_class_progress_marked'			=> 1,
				'classes_cancelled_count'				=> 0,
				'classes_cancelled_percentage'			=> 0,
				'center_authorities_visited'			=> -1,
				'center_authority_not_visited_2_months'	=> -1,
				// EPH
				'periodic_assessment_updation_status'	=> -1,
				'pass_percentage'						=> 0,
				'class_progress'						=> 0,
				'class_progress_percentage'				=> 0,
				'substitute_count'						=> 0,
				'volunteers_missing_teacher_training_1'=> 0,
				'volunteers_missing_curriculum_training'=> 0,
				'volunteers_missing_teacher_training_2'=> 0,
				// HR
				'volunteer_requirement_count'			=> 0,
				'volunteer_requirement_percentage'		=> 0,
				'attirition_count'						=> 0,
				'attirition_percentage'					=> 0,
				'months_since_avm'						=> 0,
				'cc_attendance_percentage'				=> 0,
				'madapp_updated_hr'						=> -1,
				'exit_interviews_conducted'				=> -1,
				'volunteers_missing_process_training'	=> 0,
				// PR
				'months_since_ping'						=> -1,
				'blog_post_count'						=> -1,
				'months_since_pr_initiative'			=> -1,
				'activity_on_city_page'					=> -1,
				'fb_plan_submission'					=> -1,
				'strategic_tie_ups'						=> -1,
				'attendance_in_concall'					=> -1,
				// CR
				'monthly_target'						=> -1,
				'money_raised'							=> -1,
				'donor_update_sent'						=> -1,
				// Finance
				'accounts_updated_status'				=> -1,
				'pending_receipt_count'					=> -1,
				'non80g_donor_register_update'			=> -1,
				'80g_donor_register_update'				=> -1,
				// Placements
				'inactive_child_group_count'			=> -1,
				'new_kit_count'							=> -1,
				'inactive_intern_count'					=> -1,
				'late_kit_count'						=> -1,
				'monthly_calendar_status'				=> -1,
				'participating_kids_percentage'			=> -1,
				// President
				'core_team_meeting_status'				=> 0,
				'red_flag_count'						=> 0,
				'number_of_fellows_pimped'				=> -1,
				'bills_documents_submitted'				=> -1,
				'madapp_updated_president'				=> -1,
			);
			
			$flags = array();
			$notes = array();
			foreach($categories as $name => $value) {
				$flags[$name] = 'green';
				$notes[$name] = '';
			}

			$student_count= count($this->kids_model->getkids_details($city->id)->result());
			$all_teachers = $this->users_model->search_users(array('user_group'=>9, 'city_id'=>$city->id, 'project_id'=>$project_id, 'user_type'=>'volunteer')); // 9 = Teacher
			$teacher_count = count($all_teachers);
			
			// Fellow Count
			$core_team_groups = array(2,4,5,11,12,15,19, 18,10,20);
			$all_vps = $this->users_model->search_users(array('city_id'=>$city->id, 'user_group'=> $core_team_groups, 'user_type'=>'volunteer', 'get_user_groups'=>true));
			$categories['fellows_count'] = count($all_vps);
			
			$info = $this->class_model->get_classes_in_month($year_month, $city->id, $project_id);
			foreach($info as $c) {
				if($c->status == 'absent' or $c->status == 'attended') $categories['class_count']++;
				if($c->status == 'absent' and $c->substitute_id == 0) $categories['absent_without_substitute_count']++;
				if($c->status == 'attended' and $c->substitute_id) $categories['substitute_count']++;
				if($c->status == 'projected' or $c->status == 'confirmed') {
					$categories['madapp_volunteer_attendance_marked'] = 0;
					if(!$notes['madapp_volunteer_attendance_marked']) $notes['madapp_volunteer_attendance_marked'] = "Missing attendence on...\n";
					$notes['madapp_volunteer_attendance_marked'] .= $c->class_on . "({$c->class_id})\n";
				}
				if($c->lesson_id == 0 and ($c->status == 'absent' or $c->status == 'attended')) {
					$categories['madapp_class_progress_marked'] = 0;
					if(!$notes['madapp_class_progress_marked']) $notes['madapp_class_progress_marked'] = "Missing Progress on...\n";
					$notes['madapp_class_progress_marked'] .= $c->class_on . "({$c->class_id})\n";
				}
				
				if($c->status == 'attended') {
					$kids_attendence = $this->class_model->get__kids_attendance($c->class_id);
					if(!$kids_attendence) {
						$categories['madapp_student_attendance_marked'] = 0;
						if(!$notes['madapp_student_attendance_marked']) $notes['madapp_student_attendance_marked'] = "Missing Students attendence on...\n";
						$notes['madapp_student_attendance_marked'] .= $c->class_on . "({$c->class_id})\n";
					}
				}
 			}
 			
			if(!$categories['class_count']) continue;
			
			if(!$categories['madapp_volunteer_attendance_marked']) $flags['madapp_volunteer_attendance_marked'] = 'red';
			if(!$categories['madapp_student_attendance_marked']) $flags['madapp_student_attendance_marked'] = 'red';
			if(!$categories['madapp_class_progress_marked']) $flags['madapp_class_progress_marked'] = 'red';
 			
 			$categories['classes_cancelled_count'] = $this->class_model->get_cancelled_class_count($year_month, $city->id, $project_id);

			if($categories['class_count']) {
				$categories['substitute_percentage'] = ceil($categories['substitute_count'] / $categories['class_count'] * 100);
				if($categories['substitute_percentage'] > 15) $flags['substitute_percentage'] = 'red';
				
				$categories['classes_cancelled_percentage'] = ceil($categories['classes_cancelled_count'] / ($categories['class_count'] + $categories['classes_cancelled_count']) * 100);
				if($categories['classes_cancelled_percentage'] > 5) $flags['classes_cancelled_percentage'] = 'red';
			
				$categories['absent_without_substitute_percentage'] = ceil($categories['absent_without_substitute_count'] / $categories['class_count'] * 100);
				if($categories['absent_without_substitute_percentage'] > 10) $flags['absent_without_substitute_percentage'] = 'red'; // If more than 10% is absent without substitute, its a red flag.
			}
			
			
// 			periodic_assessment_updation_status
// 			
// 			SELECT Exam_Event.exam_on, Exam_Mark.mark, Exam_Subject.total_mark 
// 				FROM Exam INNER JOIN Exam_Event ON Exam.id=Exam_Event.exam_id
// 				INNER JOIN Exam_Mark ON Exam_Event.id=Exam_Mark.exam_event_id
// 				INNER JOIN Exam_Subject ON Exam_Subject.exam_id=Exam.id
// 			WHERE Exam.name LIKE 'Assessment%' AND DATE_FORMAT(Exam_Event.exam_on, '%Y-%m')='$year_month'
			
			// class_progress
			$late_class_count = 0;
			list($cp_data, $cp_all_lessons, $cp_all_centers, $cp_all_levels) = $this->class_model->get_class_progress($city->id, false, $project_id);
			foreach($cp_data as $center_id => $center_info) {
				if(empty($center_info)) continue;
				foreach($cp_all_levels[$center_id] as $level_info) { 
					$last_lesson_id = 0;
					$repeat_count = 0;
					foreach($center_info['days_with_classes'] as $date_index => $day) {
						if(!isset($center_info['class'][$level_info->id][$date_index])) continue;
						$lesson_id = $center_info['class'][$level_info->id][$date_index]->lesson_id;
						if($lesson_id != $last_lesson_id and $lesson_id) {
							$last_lesson_id = $lesson_id;
							$repeat_count = 0;
						} else {
							$repeat_count++;
						}
						
						
						//print $center_info['center_name'] . ")\t\t" . $level_info->name . ": " .$date_index . "\t\t$lesson_id\n";
						
						$index_month = reset(explode("-", $date_index));
						$class_month = end(explode("-", $year_month));
						if($repeat_count > 2 and $lesson_id and $index_month == $class_month) {
							$late_class_count++;
							
							//print "++\n";
						}
					}
				}
			}
			
			$categories['class_progress_percentage'] = ceil( $late_class_count / $categories['class_count'] * 100);
			if($categories['class_progress_percentage'] > 10) $flags['class_progress_percentage'] = 'red';
			
			$attendance = $this->class_model->get_attendance_in_month($year_month, $city->id, $project_id);
			if($attendance) {
				$total_classes = count($attendance);
				$attended = 0;
				foreach($attendance as $a) {
					if($a->present and ($a->status == 'attended' or $a->status == 'absent')) $attended++;
				}
				$categories['attended_kids_percentage'] = ceil($attended / $total_classes * 100);
			}
			if($categories['attended_kids_percentage'] < 80) $flags['attended_kids_percentage'] = 'red'; // If less than 80% of the kids attended the class, red flag.
			
			$categories['negative_credit_volunteer_count'] = count($this->report_model->get_users_with_low_credits(0, '<', $city->id, $project_id));
			
			$requirements = $this->report_model->get_volunteer_requirements($city->id);
			foreach($requirements as $r) {
				$categories['volunteer_requirement_count'] += $r->requirement;
			}
			
			$categories['attirition_count'] = count($this->users_model->search_users(array('left_on'=>$year_month, 'city_id'=>$city->id, 'project_id'=>$project_id)));
			
			if($teacher_count) {
				$categories['negative_credit_volunteer_percentage'] = ceil($categories['negative_credit_volunteer_count'] / $teacher_count * 100);
				if($categories['negative_credit_volunteer_percentage'] > 15) $flags['negative_credit_volunteer_percentage'] = 'red';

				$categories['volunteer_requirement_percentage'] = ceil($categories['volunteer_requirement_count'] / $teacher_count * 100);
				if($categories['volunteer_requirement_percentage'] > 10) $flags['volunteer_requirement_percentage'] = 'red';
				
				$categories['attirition_percentage'] = ceil($categories['attirition_count'] / $teacher_count * 100);
				if($categories['attirition_percentage'] > 2) $flags['attirition_percentage'] = 'red';
			}	
			
			$categories['months_since_avm'] = $this->event_model->months_since_event('avm', $year_month, $city->id);
			if($categories['months_since_avm'] > 1) $flags['months_since_avm'] = 'red';
			$categories['core_team_meeting_status'] = ($this->event_model->months_since_event('coreteam_meeting', $year_month, $city->id)) ? 0 : 1;
			if(!$categories['core_team_meeting_status']) $flags['core_team_meeting_stauts'] = 'red';
                        
			//Count of volunteers to attend trainings
			$trainings = array(
				'volunteers_missing_teacher_training_1'	=> 'teacher',
				'volunteers_missing_teacher_training_2'	=> 'teacher2',
				'volunteers_missing_curriculum_training'=> 'curriculum',
				'volunteers_missing_process_training'	=> 'process',
			);
			$all_teachers_ids = array_keys($all_teachers);
			foreach($trainings as $category_name => $event_type) {
				$volunters_who_atteneded = $this->event_model->get_volunteers_at_event('', $city->id, '', $event_type);
				
				if($volunters_who_atteneded !== false) {
					$people_who_missed = array_diff($all_teachers_ids, $volunters_who_atteneded);
					
					$categories[$category_name] = count($people_who_missed);
					if($categories[$category_name] > 10 ) $flags[$category_name] = 'red';
				} else { // Event Not created.
					$categories[$category_name] = '-1';
					$flags[$category_name] = 'red';
				}
			}
			
			// Conut of people who atteneded the last CC
			$cc_missing_count = $this->event_model->get_count_of_missing_volunteers_at_event($year_month, $city->id, '', 'avm');
			if($cc_missing_count !== false) {
				$cc_expected_count = $this->event_model->get_count_of_expected_volunteers_at_event($year_month, $city->id, '', 'avm');
				
				if($cc_expected_count) {
					$cc_attended_count = $cc_expected_count - $cc_missing_count;
					$categories['cc_attendance_percentage'] = ceil( $cc_attended_count / $cc_expected_count * 100);
					if($categories['cc_attendance_percentage'] < 70) $flags['cc_attendance_percentage'] = 'red';
				} else {
					$categories['cc_attendance_percentage'] = -1;
					$flags['cc_attendance_percentage'] = 'red';
				}
			} else {
				$categories['cc_attendance_percentage'] = -1;
				$flags['cc_attendance_percentage'] = 'red';
			}
			
			// VPs attending events
			$core_team_groups = array(2,4,5,11,12,15,19);
			$vps = $this->users_model->search_users(array('city_id'=>$city->id, 'user_group'=> $core_team_groups, 'user_type'=>'volunteer', 'get_user_groups'=>true)); //18(Library), 10(CR) and 20(FOM) Excluded

			$events = array();
			$events['avm'] = reset($this->event_model->get_all('avm', array('from'=>$year_month."-01", 'to'=>$year_month."-31")));
			$events['review_meeting'] = reset($this->event_model->get_all('monthly_review', array('from'=>$year_month."-01", 'to'=>$year_month."-31")));
			$events['core_team_meeting']  = reset($this->event_model->get_all('coreteam_meeting', array('from'=>$year_month."-01", 'to'=>$year_month."-31")));
		
			foreach($events as $event_name => $ev) {
				foreach($vps as $vp) {
					$core_team_position = reset(array_intersect($core_team_groups, array_keys($vp->groups)));
					$name = 'core_team_'.$event_name.'_attendance_'.$core_team_position.'_'.$vp->id;
					
					//dump($ev->id, $vp->id);
					if($ev) {
						$attendance = $this->event_model->getEventUser($ev->id, $vp->id);
						if($attendance) {
							$categories[$name] = $attendance->present;
							if($categories[$name]) $flags[$name] = 'green';
							else $flags[$name] = 'red';
						}
						else {
							$categories[$name] = -1;
							$flags[$name] = 'none';
						}
					} else {
						$categories[$name] = -1;
						$flags[$name] = 'none';
					}
				}
			}
			
			
			// Count number of red flags. PS: This is recalculated at display. So this is not too important.
			foreach($flags as $name=>$color) if($color == 'red') $categories['red_flag_count']++;
			if($categories['red_flag_count'] >= 4) $flag['red_flag_count'] = 'red';
			
 			// Save status to DB...
			foreach($categories as $name => $value) {
				if(!isset($notes[$name])) $notes[$name] = '';
				//print "$name: $value({$notes[$name]}) : {$city->id}\n";
				$this->review_model->save($name, $value, $year_month.'-01', $flags[$name], $city->id, $notes[$name]);
 			}
		}
	}
               
}

