<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Controller  {
    function Cron() {
        parent::Controller();
        
        $this->load->model('Class_model','class_model', TRUE);
	}
	
	// This is one of the most improtant functions. Makes all the classes for the next two weeks using the data in the Batch table.
	function schedule_classes($debug=0) {
		$this->load->model('Batch_model','batch_model', TRUE);
		$all_batches = $this->batch_model->get_all_batches();
		
		if($debug) {
			print "Debug Mode<hr /><br />";
			$this->load->helper('misc_helper');
			print "Batches: " . count($all_batches);
		}
		
		
		// Wee have to add all the classes for the next two weeks.
		for($week = 0; $week < 2; $week++) {
			foreach($all_batches as $batch) {
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
					// Make sure its not already inserted.
					if(!$this->class_model->get_by_teacher_time($teacher->id, $date)) {
						print "Class by {$teacher->id} at $date<br />";
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
				
				if($debug) print "++++++++++++++++++++++++++++++++++++++++++++++<br />";
			}
		}
	}
	
	/// Send SMSs to people who haven't confirmed their classes.
	function send_unconfirmed_class_sms() {
		$this->load->model('Center_model','center_model', TRUE);
		$this->load->helper('misc_helper');
		$this->load->library('sms');
		
		$all_centers = idNameFormat($this->center_model->get_all_centers());
		$people = $this->class_model->get_unconfirmed_classes(2);
		
		foreach($people as $person) {
			$name = short_name($person->name);
			$class_timestamp = strtotime($person->class_on);
			
			// The class is 2 days away(at least, more than 1 day away).
			if((time() - $class_timestamp) > 60 * 60 * 24) {
				$this->sms->send($person->phone, "$name, you have a class at {$all_centers[$person->center_id]} on " . date('dS M, h:i A', $class_timestamp) 
					. ". Reply 'confirm' to confirm this class. Visit http://makedaff.in/madapp/ to assign a substitute if you are unable to take the class.");
			
			// The class is happening tomorrow.
			} else {
				// :TODO: Send a SMS to the batch head saying that there was a person who did not confirm their class.
				$this->sms->send($person->phone, "$name, this is the final call to confirm your attendance for the class at {$all_centers[$person->center_id]} on " . date('dS M, h:i A', $class_timestamp) 
					. ". Reply 'confirm' to confirm this class. Visit http://makedaff.in/madapp/ to assign a substitute if you are unable to take the class.");
			}
 			print "<br />";
		}
			
	}
}

