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
					. ". Reply 'confirm' to confirm this class. Visit http://makedaff.in/madapp/ to assign a substitute if you are unable to take the class.");
			
			// The class is happening tomorrow.
			} else {
				// Send a SMS to the batch head saying that there was a person who did not confirm their class. First, collect their names.
				if(!isset($unconfirmed_people[$person->batch_id])) $unconfirmed_people[$person->batch_id] = array();
				$unconfirmed_people[$person->batch_id][] = $name;
				
				$this->sms->send('91'.$person->phone, "$name, this is the final call to confirm your attendance for the class at {$all_centers[$person->center_id]} on " . date('dS M, h:i A', $class_timestamp) 
					. ". Reply 'confirm' to confirm this class. Visit http://makedaff.in/madapp/ to assign a substitute if you are unable to take the class.");
			}
		}
		
		// Send the batch head a list of people who didn't confirm for the class.
		foreach($unconfirmed_people as $batch_id => $name_list) {
			$batch_head = $this->batch_model->get_batch_head($batch_id);
			$this->sms->send('91'.$batch_head->phone, short_name($batch_head->name) . ", the following people have not yet confirmed their class: " . implode(', ', $name_list)
					. ". Please take the necessary steps to make sure that the classes happen.");
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
}

