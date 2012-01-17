<?php
class Analysis extends Controller {
	private $message;
	
	function Analysis() {
		parent::Controller();
		$this->load->model('Users_model','user_model');
		$this->load->model('Class_model','class_model');
		$this->load->model('Level_model','level_model');
		$this->load->model('Center_model','center_model');
		$this->load->model('city_model');
		$this->load->model('Batch_model','batch_model');
		$this->load->model('Book_Lesson_model','book_lesson_model');
		
		$this->load->helper('url');
		$this->load->helper('misc');
		
		$this->load->library('session');
        $this->load->library('user_auth');
        
        $logged_user_id = $this->user_auth->logged_in();
		if(!$logged_user_id) {
			redirect('auth/login');
		}
        $this->user_details = $this->user_auth->getUser();
	}
	
	function class_progress_report() {
		$this->user_auth->check_permission('classes_progress_report');
		
		$all_centers = $this->center_model->get_all();
		$all_levels = array();
		$all_lessons = idNameFormat($this->book_lesson_model->get_all_lessons());
		$all_lessons[0] = 'None';
		$data = array();
		foreach($all_centers as $center) {
			//if($center->id != 34) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
		
			$data[$center->id] = array(
				'center_id'	=> $center->id,
				'center_name'=>$center->name,
			);
			$batches = $this->batch_model->get_class_days($center->id);
			$all_levels[$center->id] = $this->level_model->get_all_levels_in_center($center->id);
			$data[$center->id]['batches'] = array();
			$days_with_classes = array();
	
			// NOTE: Each batch has all the levels in the center. Think. Its how that works.
			foreach($all_levels[$center->id] as $level) {
				$data[$center->id]['class_progress'][$level->id] = $this->class_model->get_last_unit_taught($level->id);
				
				foreach($batches as $batch_id => $batch_name) {
					//if($batch_id != 1) continue; // :DEBUG: Use this to localize the issue
					$data[$center->id]['batches'][$batch_id] = array('name'=>$batch_name);

					//if($level->id != 71) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
					$all_classes = $this->class_model->get_classes_by_level_and_batch($level->id, $batch_id);
					$last_class_id = 0;
					foreach($all_classes as $class) {
						if($class->status != 'cancelled') {
							$date = date('d M',strtotime($class->class_on));
							$month = date('m',strtotime($class->class_on));
							if($month < 3) $month = $month + 12; // So that january comes after december.
							$key = $month . '-'.date('d',strtotime($class->class_on));
							if(!in_array($date, $days_with_classes)) {
								$days_with_classes[$key] = $date;
							}
							$data[$center->id]['class'][$level->id][$key] = $class;
						}
					}
				}
			}
			ksort($days_with_classes);
			$data[$center->id]['days_with_classes'] = $days_with_classes;
		}
		$this->load->view('analysis/class_progress_report', array(
			'data'=>$data, 'all_lessons'=>$all_lessons,
			'all_centers'=>$all_centers, 'all_levels'=>$all_levels));
		
	}
	 /**
    *
    * Function to
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function kids_attendance()
	{
		$all_centers = $this->center_model->get_all();
		$data = array();
		$datas = array();
		$attendance = array();
		$totalAttendance=array();
			foreach($all_centers as $center) {
				//if($center->id != 34) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
				$data[$center->id] = array(
					'center_id'	=> $center->id,
					'center_name'=>$center->name,
				);
				$all_levels[$center->id] = $this->level_model->get_all_levels_in_center($center->id);
		$days_with_classes = array();
		foreach($all_levels[$center->id] as $level) {
				//if($center->id != 34) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
				$datas[$level->id] = array(
					'level_id'	=> $level->id,
					'level_name'=>$level->name,
				);
				$all_kids[$level->id] = $this->level_model->get_all_kids_in_level($level->id);
				$all_classes = $this->class_model->get_classes_by_level_and_center($level->id);
				$totalAttendance=0;
				foreach($all_classes as $class) {
				
							$date = date('d M',strtotime($class->class_on));
							$month = date('m',strtotime($class->class_on));
							if($month < 3) $month = $month + 12; // So that january comes after december.
							$key = $month . '-'.date('d',strtotime($class->class_on));
							if(!in_array($date, $days_with_classes)) {
								$days_with_classes[$key] = $date;
							}
							$data[$center->id]['class'][$level->id][$key] = $class;
							$attendance[$class->id]  = $this->class_model->get__kids_attendance ($class->id);
					}
				}
				ksort($days_with_classes);
			$data[$center->id]['days_with_classes'] = $days_with_classes;
				}
			
		$this->load->view('analysis/kids_attendance', array(
				'data'=>$data, 'all_centers'=>$all_centers, 'all_levels'=>$all_levels,'all_kids'=>$all_kids,'attendance'=>$attendance));
	}
	
}



