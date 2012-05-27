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
							if($month <= 3) $month = $month + 12; // So that january comes after december.
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
					if($month <= 3) $month = $month + 12; // So that january comes after december.
					$key = $month . '-'.date('d',strtotime($class->class_on));
					if(!in_array($date, $days_with_classes)) {
						$days_with_classes[$key] = $date;
					}
					$data[$center->id]['class'][$level->id][$key] = $class; 
					$attendance[$class->id]  = $this->class_model->get__kids_attendance ($class->id);
				}	
			}
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
		
		//Users have to be initiazied seperately as not all users will be there in events
		foreach($users as $user_id=>$name) $user_attendance_count[$user_id] = array('total'=>0, 'present'=>0);
		foreach($user_attendance as $event_id => $attendance) {
			foreach($attendance as $user_id => $present) {
				// for events...
				if(!isset($event_attendance_count[$event_id])) {
					$event_attendance_count[$event_id] = array('total'=>0, 'present'=>0);
				}
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
	
	
	function monthly_review() {
		$this->user_auth->check_permission('monthly_review');
		$data = array();
		$this->load->model('kids_model');
		$this->load->model('review_model');
		
		$data['center_count'] = count($this->center_model->get_all());
		$data['student_count']= count($this->kids_model->getkids_details()->result());
		$data['teacher_count']= count($this->user_model->search_users(array('user_group'=>9))); // 9 = Teacher
		
		$data['months'] = array('2011-04', '2011-05', '2011-06', '2011-07', '2011-08', '2011-09', '2011-10', '2011-11', '2011-12', '2012-01', '2012-02', '2012-03', ); get_month_list();
		foreach($data['months'] as $year_month) {
			$data['review'][$year_month] = idNameFormat($this->review_model->get_monthly_review($year_month, $this->session->userdata('city_id')), array('name'));
		}
		
		
		$this->load->view('analysis/monthly_review', $data);
	}
	
	
	/// NOT IN USE. DELETE.
	function exam_report()
	{
		$all_centers = $this->center_model->get_exam_centers();
		$data = array();
		$datas = array();
		$marks = array();
		$totalAttendance=array();
		foreach($all_centers as $center) {
			//if($center->id != 34) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
			$data[$center->id] = array(
				'center_id'	=> $center->id,
				'center_name'=>$center->name,
			);
			//$allLevels=$this->level_model->get_only_alllevels_in_center($center->id);
			$all_levels[$center->id] = $this->level_model->get_only_levels_in_center($center->id);
			//$all_levels[$center->id] = $this->level_model->get_all_levels_in_center($center->id);
			
			$days_with_classes = array();
			foreach($all_levels[$center->id] as $level) {
				//if($center->id != 34) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
				$datas[$level->id] = array(
					'level_id'	=> $level->id,
					'level_name'=>$level->name,
				);
				$all_kids[$level->id] = $this->level_model->get_all_kidsname_in_level($level->id);
				$all_exams = $this->class_model->get_examname_by_level_and_center($center->id);
				
				$totalAttendance=0;
				foreach($all_kids[$level->id] as $students){
					//print_r($students);
					foreach($all_exams as $exam) {
						$exam_name=$exam->name;
						$month =$exam->id;
						$key = $month . '-'.date('d',strtotime($exam->exam_on));
						if(!in_array($exam_name, $days_with_classes)) {
							$days_with_classes[$key] = $exam_name;
						}
						$data[$center->id]['class'][$level->id][$key] = $exam; 
						//echo $students->id;
						$marks[$exam->id][$students->id]  = $this->class_model->get__student_marks ($exam->id,$students->id);
						
					} 
					
				}
			}
			
			ksort($days_with_classes);
			$data[$center->id]['days_with_classes'] = $days_with_classes;
		}
		
		$this->load->view('analysis/exam_report', array(
				'data'=>$data, 'all_centers'=>$all_centers, 'all_levels'=>$all_levels,'all_kids'=>$all_kids,'attendance'=>$marks));
			
	}
	
	
	function exam_report_test() {
		$all_centers = $this->center_model->get_exam_centers();
		$data = array();
		$datas = array();
		$marks = array();
		$totalAttendance=array();
		$this->load->view('analysis/exam_report/report_header');
		
		
		foreach($all_centers as $center) {
			$data['name']=$center->name;
			$data['all_exams'] = $this->class_model->get_examname_by_level_and_center($center->id);
			
			$this->load->view('analysis/exam_report/report_center',$data);
			
			$all_levels= $this->level_model->get_only_levels_in_center($center->id);
			foreach($all_levels as $level) {
				$data['levelname']=$level->name;
				$all_kids = $this->level_model->get_all_kidsname_in_level($level->id);
				
				foreach($all_kids as $kids){
					$data['kidsname'] = $kids->name;
					$this->load->view('analysis/exam_report/report_kidsname',$data);
					foreach($data['all_exams'] as $exam) {
						$data['attendance']= $this->class_model->get__student_attendence($kids->id);
						
						$data['marks']= $this->class_model->get__student_marks ($exam->id,$kids->id);
						$this->load->view('analysis/exam_report/report_marks',$data);
					}
					
					$this->load->view('analysis/exam_report/report_close_tr',$data);
					$data['levelname'] = '&nbsp;';
				}
			}
			
			$this->load->view('analysis/exam_report/report_center_footer');
		}
			
		$this->load->view('analysis/exam_report/report_footer');
		
	}
}



