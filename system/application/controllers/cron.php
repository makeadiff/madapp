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
		
		// Wee have to add all the classes for the next two weeks.
		for($week = 0; $week < 2; $week++) {
			foreach($all_batches as $batch) {
				$teachers = $this->batch_model->get_batch_teachers($batch->id);
				list($hour, $min, $secs) = explode(":", $batch->class_time);
				
				$day = date('d') + date('w') - intval($batch->day)	// This is how we find the next sunday, monday(whatever is in the $batch->day).
							+ ($week * 7); // We have to do this for two weeks. So in the first iteration, this will be 0 and in next it will be 7.
							
				$time = mktime($hour, $min, $secs, date('m'), $day, date("Y")));
				$date = date("Y-m-d H:i:s", $time);
				
				dump($teachers, $date);
				
				foreach($teachers as $teacher) {
					// Make sure its not already inserted.
					if(!$this->class_model->get_by_teacher_time($teacher->id, $date)) { 
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
			}
		}
	}
}

