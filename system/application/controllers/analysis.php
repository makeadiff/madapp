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
	
	function kids_attendance() {
		$all_centers = $this->center_model->get_all();
		$data = array();
		$datas = array();
		$attendance = array();

		foreach($all_centers as $center) {
			//if($center->id != 65) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
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

				foreach($all_classes as $class) {
					$date = date('d M',strtotime($class->class_on));
					$month = date('m',strtotime($class->class_on));
					if($month <= 3) $month = $month + 12; // So that january comes after december.

					$key = $month . '-'.date('d',strtotime($class->class_on));
					if(!in_array($date, $days_with_classes)) {
						$days_with_classes[$key] = $date;
					}
					$data[$center->id]['class'][$level->id][$key] = $class;
					$student_attendance = $this->class_model->get_student_attendance($class->id);
					// dump($student_attendance); var_export($student_attendance, 1); // 
					$attendance[$class->id] = $student_attendance->present;
				}	
			}

			// dump($attendance);
			//print_r($class->status);
			ksort($days_with_classes);
			$data[$center->id]['days_with_classes'] = $days_with_classes;
		}
		
		$this->load->view('analysis/kids_attendance', array(
				'data'=>$data, 'all_centers'=>$all_centers, 'all_levels'=>$all_levels,'all_kids'=>$all_kids,'attendance'=>$attendance));
	}
	
	
	function event_attendance() {
		$this->load->model('event_model');
		$event_type = $this->input->post('event_type');
		
		$events = $this->event_model->get_all($event_type);
		$users = idNameFormat($this->user_model->get_users_in_city());
		$user_attendance = $this->event_model->get_all_event_user_attendance($event_type);
		
		// Get total attendance count...
		$event_attendance_count = array();
		$user_attendance_count = array();
		
		foreach($events as $e) {
			$event_attendance_count[$e->id] = array('total'=>0, 'present'=>0);
		}
		
		//Users have to be initiazied seperately as not all users will be there in events
		foreach($users as $user_id=>$name) $user_attendance_count[$user_id] = array('total'=>0, 'present'=>0);
		foreach($user_attendance as $event_id => $attendance) {
			foreach($attendance as $user_id => $present) {
				if(!isset($event_attendance_count[$event_id])) continue; 

				$event_attendance_count[$event_id]['total']++;
				if($present) $event_attendance_count[$event_id]['present']++;
				
				// and for users.
				if(isset($user_attendance_count[$user_id])) {
					$user_attendance_count[$user_id]['total']++;
					if($present) $user_attendance_count[$user_id]['present']++;
				}
			}
		}
		
		$this->load->view('analysis/event_attendance', array(
			'events'	=> $events,
			'users'		=> $users,
			'event_type'=> $event_type,
			'user_attendance'		=> $user_attendance,
			'user_attendance_count' => $user_attendance_count,
			'event_attendance_count'=> $event_attendance_count,
		));
	}
	
}
