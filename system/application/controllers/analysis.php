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
		
		list($data, $all_lessons, $all_centers, $all_levels) = $this->class_model->get_class_progress();
		
		$this->load->view('analysis/class_progress_report', array(
			'data'=>$data, 'all_lessons'=>$all_lessons,
			'all_centers'=>$all_centers, 'all_levels'=>$all_levels));
		
	}
	
	function kids_attendance()
	{
		$all_centers = $this->center_model->get_all();
		$data = array();
		$datas = array();
		$attendance = array();
		$totalAttendance=array();
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
		
		foreach($events as $e) {
			$event_attendance_count[$e->id] = array('total'=>0, 'present'=>0);
		}
		
		//Users have to be initiazied seperately as not all users will be there in events
		foreach($users as $user_id=>$name) $user_attendance_count[$user_id] = array('total'=>0, 'present'=>0);
		foreach($user_attendance as $event_id => $attendance) {
			foreach($attendance as $user_id => $present) {
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
	
	
	function monthly_review($vertical='all') {
		$this->user_auth->check_permission('monthly_review');
		$data = array();
		$this->load->model('kids_model');
		$this->load->model('review_model');
		
		$all_cities = array();
		if($vertical != 'all') {
			$all_cities_data = $this->city_model->get_all();
			foreach($all_cities_data as $city_details) $all_cities[$city_details->id] = $city_details->name;
		} else {
			$all_cities = array($this->session->userdata('city_id') => '');
		}
		
		foreach($all_cities as $city_id => $city_name) {
			$data[$city_id]['center_count'] = count($this->center_model->get_all($city_id));
			$data[$city_id]['student_count']= count($this->kids_model->getkids_details($city_id)->result());
			$data[$city_id]['volunteer_count']= count($this->user_model->search_users(array('city_id'=>$city_id,'user_type'=>'volunteer')));
			$data[$city_id]['teacher_count']= count($this->user_model->search_users(array('city_id'=>$city_id,'user_group'=>9, 'user_type'=>'volunteer'))); // 9 = Teacher
					
			$core_team_groups = array(2,4,5,11,12,15,19);
			$vps = $this->users_model->search_users(array('city_id'=>$city_id,'user_group'=> $core_team_groups, 'user_type'=>'volunteer', 'get_user_groups'=>true)); //18(Library), 10(CR) and 20(FOM) Excluded
			$attendance_matrix = array();
			foreach($core_team_groups as $vertical_id) {
				$people = array();
				foreach($vps as $vp) {
					if(in_array($vertical_id, array_keys($vp->groups))) {
						$people[] = $vp;
					}
				}
				
				$attendance_matrix[$vertical_id] = $people;
			}
			
			$data[$city_id]['attendance_matrix'] = $attendance_matrix;
			
			$month_names = array('2012-04', '2012-05', '2012-06', '2012-07', '2012-08', '2012-09', '2012-10', '2012-11', '2012-12', '2013-01', '2013-02', '2013-03', ); get_month_list();
			foreach($month_names as $year_month) {
					$data[$city_id]['review'][$year_month] = idNameFormat($this->review_model->get_monthly_review($year_month, $this->session->userdata('city_id')), array('name'));
			}
			$data[$city_id]['review']['user_auth'] = $this->user_auth;
		}
		
		
		$this->load->view('analysis/monthly_review', array('data' => $data, 'all_cities' => $all_cities, 'vertical' => $vertical, 'months'=>$month_names));
	}
	
	function save_review_data($name, $year_month, $value, $flag = 'green') {
		$this->load->model('review_model');
		
		$this->review_model->save($name, $value, $year_month, $flag, $this->session->userdata('city_id'));
		echo "Saved";
	}
	
	function monthly_review_get_comment($year_month, $name) {
		$this->load->model('review_model');
		
		$comment = $this->review_model->get_comment($this->session->userdata('city_id'), $year_month, $name);
		echo $comment;
	}
	
	function monthly_review_set_comment($year_month, $name) {
		$this->load->model('review_model');
		
		$comment = $this->input->post('comment');
		$this->review_model->set_comment($this->session->userdata('city_id'), $year_month, $name, $comment);
		echo "Done";
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
					$sum = 0;
					foreach($data['all_exams'] as $exam) {
						$data['attendance']= $this->class_model->get__student_attendence($kids->id);
						
						$data['marks']= $this->class_model->get__student_marks ($exam->id,$kids->id);
						foreach($data['marks'] as $m) $sum += $m->mark;
						$this->load->view('analysis/exam_report/report_marks',$data);
					}
					
					$data['sum'] = $sum;
					$this->load->view('analysis/exam_report/report_close_tr',$data);
					$data['levelname'] = '&nbsp;';
				}
			}
			
			$this->load->view('analysis/exam_report/report_center_footer');
		}
			
		$this->load->view('analysis/exam_report/report_footer');
		
	}
}



