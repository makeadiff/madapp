<?php
class Classes extends Controller {
	private $message;
	
	function Classes() {
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
		
		$this->load->model('Users_model','user_model');
		$this->load->model('Class_model','class_model');
		$this->load->model('Level_model','level_model');
		$this->load->model('Center_model','center_model');
		$this->load->model('Batch_model','batch_model');
		$this->load->model('Book_Lesson_model','book_lesson_model');
		
		$this->load->helper('url');
		$this->load->helper('misc');
		
		$this->load->library('session');
        $this->load->library('user_auth');
        $this->user_details = $this->user_auth->getUser();
		if(!$this->user_details) {
			redirect('auth/login');
		}
		
	}
	
	/// Shows all the classes the current user is resposible for.
	function index() {
		$this->user_auth->check_permission('classes_index');
		$all_classes = $this->class_model->get_all($this->user_details->id);
		
		$all_levels = array();
		if($all_classes) {
			$all_levels[$all_classes[0]->level_id] = $this->level_model->get_level_details($all_classes[0]->level_id);
		}
		
		$this->load->view('classes/index', array('all_classes' => $all_classes, 'all_levels'=>$all_levels, 'level_model'=>$this->level_model));
	}
	
	function batch_view($batch_id=0) {
		$this->user_auth->check_permission('classes_batch_view');
		
		if(!$batch_id) $batch_id = $this->user_model->get_users_batch($this->user_details->id);
		
		$all_users = $this->user_model->search_users(array('user_type'=>'volunteer'));
		$all_classes = $this->class_model->get_all_by_batch($batch_id);
		
		$all_levels = array();
		$center_id = $this->batch_model->get_batch($batch_id)->center_id;
		
		foreach($all_classes as $class_info) {
			if(isset($all_levels[$class_info->level_id])) continue;
			
			$all_levels[$class_info->level_id] = $this->level_model->get_level_details($class_info->level_id);
		}
		
		$this->load->view('classes/index', array('all_classes' => $all_classes, 'all_levels'=>$all_levels, 'level_model'=>$this->level_model, 'all_users'=>$all_users));
	}
	
	function mark_attendence($class_id) {
		$this->user_auth->check_permission('classes_mark_attendence');
		$this->load->helper('form');
		
		$class_info = $this->class_model->get_class($class_id);
		$level_id = $class_info['level_id'];
		
		$students = $this->level_model->get_kids_in_level($level_id);
		$attendence = $this->class_model->get_attendence($class_id);
				
		$this->load->view('classes/attendence', array('students'=>$students, 'attendence'=>$attendence, 'class_info'=>$class_info));
	}
	
	function mark_attendence_save() {
		$this->user_auth->check_permission('classes_mark_attendence');
		
		$attendence = $this->input->post('attendence');
		$class_id = $this->input->post('class_id');
		$class_info = $this->class_model->get_class($class_id);
		$level_id = $class_info['level_id'];
		
		$students = $this->level_model->get_kids_in_level($level_id);
		$this->class_model->save_attendence($class_id, $students, $attendence);
		
		$this->session->set_flashdata('success', 'Saved the attendence for this class');
		redirect('classes/mark_attendence/'.$class_id);
	}
	
	function madsheet_old_mode() {
		$this->user_auth->check_permission('classes_madsheet');
		
		$all_centers = $this->center_model->get_all();
		$all_levels = array();
		
		$all_users = $this->user_model->search_users(array('user_type'=>'volunteer'));
		
		$class_days = array();
		foreach($all_centers as $center) {
			$batches = $this->batch_model->get_class_days($center->id);
			$all_levels[$center->id] = $this->level_model->get_all_levels_in_center($center->id);
			
			foreach($batches as $batch_id => $batch_name) {
				$class_days[$center->id]['batchs'][$batch_id]['name'] = $batch_name;
				
				// NOTE: Each batch has all the levels in the center. Think. Its how that works.
				foreach($all_levels[$center->id] as $level) {
					$class_days[$center->id]['batchs'][$batch_id][$level->id]['users'] = $this->batch_model->get_teachers_in_batch_and_level($batch_id, $level->id);
					
					$all_classes = $this->class_model->get_by_level($level->id);
					$days_with_classes = array();
					foreach($all_classes as $class_date) {
						$date = date('d M',strtotime($class_date->class_on));
						if(!in_array($date, $days_with_classes)) $days_with_classes[] = $date;
					}
					
					$class_days[$center->id]['batchs'][$batch_id]['days_with_classes'] = $days_with_classes;
					
					$class_days[$center->id]['batchs'][$batch_id]['levels'][$level->id] = $all_classes;
				}
				
			}
			
		}
		
		//dump($class_days);
		$this->load->view('classes/madsheet', array('class_days'=>$class_days, 'all_centers'=>$all_centers, 'all_users'=>$all_users,'all_levels'=>$all_levels));
	}
	
	
	function madsheet() {
		$this->user_auth->check_permission('classes_madsheet');
		
		$all_centers = $this->center_model->get_all();
		$all_levels = array();
		
		$all_users = idNameFormat($this->user_model->search_users(array('user_type'=>'volunteer')));
		$all_lessons = idNameFormat($this->book_lesson_model->get_all_lessons());
		
		$data = array();
		foreach($all_centers as $center) {
			$data[$center->id] = array(
				'center_id'	=> $center->id,
				'center_name'=>$center->name,
			);
			$batches = $this->batch_model->get_class_days($center->id);
			$all_levels[$center->id] = $this->level_model->get_all_levels_in_center($center->id);
			
			$data[$center->id]['batches'] = array();
			foreach($batches as $batch_id => $batch_name) {
				$data[$center->id]['batches'][$batch_id] = array('name'=>$batch_name);
				
				// NOTE: Each batch has all the levels in the center. Think. Its how that works.
				foreach($all_levels[$center->id] as $level) {
					$all_classes = $this->class_model->get_classes_by_level_and_batch($level->id, $batch_id);
					$days_with_classes = array();
					
					$last_class_id = 0;
					$total_classes = count($all_classes); // Don't put this inside the for condition - as we'll unset stuff in between.
					
					$class_data = array();
					for($i=0; $i<$total_classes; $i++) {
						$class = $all_classes[$i];
						
						// Get the Teacher data.
						$teacher_data = array(
							'user_id'		=> $class->user_id,
							'substitute_id'	=> $class->substitute_id,
							'status'		=> $class->status,
						);
						
												
						// First record of the class happening. Get all the data about the class.
						if($last_class_id != $class->id) {
							$class_data[$class->id] = $class;
							// To get all the dates the classes happened on. We need this to make the header.
							$date = date('d M',strtotime($class->class_on));
							if(!in_array($date, $days_with_classes)) $days_with_classes[] = $date;
							
							$class_data[$class->id]->teachers = array($teacher_data);
						
						// If multiple guys took a class, get that class together.
						} elseif($last_class_id == $class->id) {
							// ... add one more entry to the list.
							array_push($class_data[$last_class_id]->teachers, $teacher_data);
						}
						
						$last_class_id = $class->id;
					}
					
					$data[$center->id]['batches'][$batch_id]['levels'][$level->id] = $class_data;
				}
				
				$data[$center->id]['batches'][$batch_id]['days_with_classes'] = $days_with_classes;
				$days_with_classes = array();
			}
		}
		
		$this->load->view('classes/madsheet_class_mode', array(
			'data'=>$data, 'all_lessons'=>$all_lessons,
			'all_centers'=>$all_centers, 'all_users'=>$all_users,'all_levels'=>$all_levels));
	}
	
	function edit_class($class_id) {
		$this->user_auth->check_permission('class_edit_class');
		
		$this->load->helper('form');
	
		$class_details = $this->class_model->get_class($class_id);
		$level_details = $this->level_model->get_level($class_details['level_id']);
		$teachers = idNameFormat($this->user_model->get_users_in_city());
		$substitutes = $teachers;
		$substitutes[0] = 'No Substitute';
		$all_lessons = idNameFormat($this->book_lesson_model->get_lessons_in_book($level_details->book_id));
		
		$statuses = array(
			'projected'	=> 'Projected',
			'confirmed'	=> 'Confirmed', 
			'attended'	=> 'Attended', 
			'absent'	=> 'Absent',
			'cancelled'	=> 'Cancelled',
		);
		
		$this->load->view('classes/form', 
			array('class_details'=>$class_details, 'teachers'=>$teachers,'substitutes'=>$substitutes,
			'statuses'=>$statuses, 'message'=>$this->message,
			'all_lessons'=>$all_lessons));
	}
	
	function edit_class_save() {
		$this->user_auth->check_permission('class_edit_class');
		
		$teacher_ids = $this->input->post('user_id');
		$user_class_id = $this->input->post('user_class_id');
		$substitute_ids = $this->input->post('substitute_id');
		$statuses = $this->input->post('status');
		
		$teacher_count = count($user_class_id);
		// There might be multiple teachers in a class.
		for($i = 0; $i<$teacher_count; $i++) {
			$this->class_model->save_class_teachers($user_class_id[$i], array(
				//'user_id'	=>	$teacher_ids[$i],
				'substitute_id'=>$substitute_ids[$i],
				'status'	=> $statuses[$i],
			));
		}
		$this->class_model->save_class_lesson($this->input->post('class_id'), $this->input->post('lesson_id'));
		
		$this->session->set_flashdata('success', 'Saved the class details');
		redirect('classes/edit_class/'.$this->input->post('class_id'));
	}

}