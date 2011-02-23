<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Controller  {
    function Cron() {
        parent::Controller();
	}
	
	// This is one of the most improtant functions. Makes all the classes for the next two weeks using the data in the Batch table.
	function schedule_classes() {
		$this->load->model('Batch_model','batch_model', TRUE);
		$this->load->model('Class_model','class_model', TRUE);
		$all_batches = $this->batch_model->get_all_batches();
		
		$debug = true;
		if($debug) $this->load->helper('misc_helper');
		
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
						if($debug) print "{$teacher->id} - $date<br />";
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
}

