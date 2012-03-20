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
		$all_batches = $this->batch_model->get_all_batches();
		
		if($debug) {
			print "Debug Mode\n---------------------------\n";
			print "Batches: " . count($all_batches);
		}
		
		
		// Wee have to add all the classes for the next two weeks.
		for($week = 0; $week < 2; $week++) {
			foreach($all_batches as $batch) {
				//if($batch->id != 24) continue; //:DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
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
						$this->class_model->save_class(array(
							'batch_id'	=> $batch->id,
							'level_id'	=> $teacher->level_id,
							'teacher_id'=> $teacher->id,
							'substitute_id'=>0,
							'class_on'	=> $date,
							'status'	=> 'projected'
						));
					}
				}
				
				if($debug) print "++++++++++++++++++++++++++++++++++++++++++++++\n";
			}
		}
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
	function monthly_review_stats_collection($year_month='') {
		if(!$year_month) $year_month = date('Y-m', strtotime('last month'));
		
		$this->load->model('report_model');
		$this->load->model('users_model');
		$this->load->model('event_model');
		$this->load->model('kids_model');
		$this->load->model('review_model');
		
		$categories = array(
			'class_count'							=> 0,
			// Ops
			'absent_without_substitute_count'		=> 0,
			'absent_without_substitute_percentage'	=> 0,
			'negative_credit_volunteer_count'		=> 0,
			'negative_credit_volunteer_percentage'	=> 0,
			'madapp_updation_status'				=> -1,
			'attended_kids_percentage'				=> 0,
			// EPH
			'periodic_assessment_updation_status'	=> -1,
			'class_progress'						=> 0,
			// HR
			'volunteer_requirement_count'			=> 0,
			'volunteer_requirement_percentage'		=> 0,
			'attirition_count'						=> 0,
			'attirition_percentage'					=> 0,
			'months_since_avm'						=> 0,
			// PR
			'months_since_ping'						=> -1,
			'blog_post_count'						=> -1,
			'months_since_pr_initiative'			=> -1,
			// CR
			'monthly_target'						=> -1,
			'money_raised'							=> -1,
			'donor_update_sent'						=> -1,
			// Finance
			'accounts_updated_status'				=> -1,
			'pending_receipt_count'					=> -1,
			// President
			'core_team_meeting_stauts'				=> 0,
			'red_flag_count'						=> 0,
		);
		
		$flags = array();
		foreach($categories as $name => $value) $flags[$name] = 'green';

		$student_count= count($this->kids_model->getkids_details()->result());
		$teacher_count = count($this->users_model->search_users(array('user_group'=>9))); // 9 = Teacher
		
		$info = $this->class_model->get_classes_in_month($year_month);
			
		foreach($info as $c) {
			if($c->status == 'absent' or $c->status == 'attended') $categories['class_count']++;;
			if($c->status == 'absent' and $c->substitute_id == 0) $categories['absent_without_substitute_count']++;
		}
		$categories['absent_without_substitute_percentage'] = ceil($categories['absent_without_substitute_count'] / $categories['class_count'] * 100);
		if($categories['absent_without_substitute_percentage'] > 10) $flags['absent_without_substitute_percentage'] = 'red'; // If more than 10% is absent without substitute, its a red flag.
		
		
		$attendance = $this->class_model->get_attendance_in_month($year_month);
		if($attendance) {
			$total_kids = count($attendance);
			$attended = 0;
			foreach($attendance as $a) {
				if($a->present) $attended++;
			}
			$categories['attended_kids_percentage'] = ceil($attended / $total_kids * 100);
			if($categories['attended_kids_percentage'] < 90) $flags['attended_kids_percentage'] = 'red'; // If less than 90% of the kids attended the class, red flag.
		}
		
		$categories['negative_credit_volunteer_count'] = count($this->report_model->get_users_with_low_credits());
		$categories['negative_credit_volunteer_percentage'] = ceil($categories['negative_credit_volunteer_count'] / $teacher_count * 100);
		if($categories['negative_credit_volunteer_percentage'] > 10) $flags['negative_credit_volunteer_percentage'] = 'red';
		
		$requirements = $this->report_model->get_volunteer_requirements();
		foreach($requirements as $r) {
			$categories['volunteer_requirement_count'] += $r->requirement;
		}
		$categories['volunteer_requirement_percentage'] = ceil($categories['volunteer_requirement_count'] / $teacher_count * 100);
		if($categories['volunteer_requirement_percentage'] > 10) $flags['volunteer_requirement_percentage'] = 'red';
		
		$categories['attirition_count'] = count($this->users_model->search_users(array('left_on'=>$year_month)));
		$categories['attirition_percentage'] = ceil($categories['attirition_count'] / $teacher_count * 100);
		if($categories['attirition_percentage'] > 10) $flags['attirition_percentage'] = 'red';
		
		
		$categories['months_since_avm'] = $this->event_model->months_since_event('avm', $year_month);
		$categories['core_team_meeting_stauts'] = ($this->event_model->months_since_event('coreteam_meeting', $year_month)) ? 0 : 1;
		if(!$categories['core_team_meeting_stauts']) $flags['core_team_meeting_stauts'] = 'red';
		
		// Count number of red flags.
		foreach($flags as $name=>$color) if($color == 'red') $categories['red_flag_count']++;
		if($categories['red_flag_count'] > 4) $flag['red_flag_count'] = 'red';
			
		// Save status to DB...
		foreach($categories as $name => $value) {
			$this->review_model->save($name, $value, $year_month.'-01', $flags[$name]);
			
		}
	}
	
}

