<?php
class Classes extends Controller {
	private $message;
	
	function Classes() {
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
	
	/// Shows all the classes the current user is resposible for.
	function index($user_id=0) {
		$this->user_auth->check_permission('classes_index');
		
		if(!$user_id) $user_id = $this->user_details->id;
		$all_classes = $this->class_model->get_all($user_id);
		$users = $this->user_model->search_users(array('not_user_type'=>array('applicant','well_wisher'),'status'=>false, 'city_id'=>0));
 		$all_users = idNameFormat($users);
		
		$all_levels = array();
		if($all_classes) {
			$all_levels[$all_classes[0]->level_id] = $this->level_model->get_level_details($all_classes[0]->level_id);
		}
		
		$this->load->view('classes/index', array('all_classes' => $all_classes, 'all_levels'=>$all_levels, 'level_model'=>$this->level_model, 'all_users'=>$all_users));
	}
	
	
	/// This is the batch head view. Sees the entire options of a batch at once.
	function batch_view($batch_id=0, $from_date='', $to_date='') {
		$this->user_auth->check_permission('classes_batch_view');
		$this->load->helper('form');
		
		if(!$batch_id) $batch_id = $this->user_model->get_users_batch($this->user_details->id);
		if(!$batch_id) {
			$this->session->set_flashdata('error', "You don't have a default batch.");
			redirect('center/manageaddcenters');
		}
		
		$last_class = $this->class_model->get_last_class_in_batch($batch_id);
		if(!$last_class) {
			$this->session->set_flashdata('error', "This batch does not have any past batches.");
			$center_id = $this->batch_model->get_center_of_batch($batch_id);
			redirect('/batch/index/center/'.$center_id);
		}
		if(!$from_date) $from_date = date('Y-m-d', strtotime($last_class->class_on));
		
		$all_users = $this->user_model->search_users(array('user_type'=>'volunteer', 'status' => '1'));
		$batch = $this->batch_model->get_batch($batch_id);
		$center_id = $batch->center_id;
		$center_name = $this->center_model->get_center_name($center_id);
		$all_lessons = array();
		
		$data = $this->class_model->search_classes(array('batch_id'=>$batch_id, 'from_date'=>$from_date, 'to_date'=>$to_date));
		
		$all_user_names = array();
		foreach($all_users as $us) $all_user_names[$us->id] = $us->name;
		$all_user_names[0] = 'None';
		$all_user_names[-1]= 'Other City';
		
		$classes = array();
		foreach($data as $row) {
			$attendence = $this->class_model->get_attendence($row->id);
			$level_id = $row->level_id;
			
			// Each level must have only the units in the book given to that level.
			if(empty($all_lessons[$level_id])) {
				$level_info = $this->level_model->get_level($level_id);
				$all_lessons[$level_id] = idNameFormat($this->book_lesson_model->get_lessons_in_book($level_info->book_id));
				$all_lessons[$level_id][0] = 'None';
			}
			
			$present_count = 0;
			$total_kids_in_level = count($this->level_model->get_kids_in_level($level_id));
			foreach($attendence as $id=>$status) if($status == 1) $present_count++;
			$attendence_count = $present_count . '/' . $total_kids_in_level;
			
			if(!isset($classes[$row->id])) { // First time we are encounting such a class.
				$classes[$row->id] = array(
					'id'			=> $row->id,
					'level_id'		=> $row->level_id,
					'level_name'	=> $row->name,
					'lesson_id'		=> $row->lesson_id,
					'student_attendence'	=> $attendence_count,
					'teachers'		=> array(array(
						'id'		=> $row->user_id,
						'name'		=> isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->name : 'None',
						'status'	=> $row->status,
						'user_type'	=>isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->user_type : 'None',
						'substitute_id'=>$row->substitute_id,
						'substitute'=> ($row->substitute_id != 0 and isset($all_users[$row->substitute_id])) ? 
											$all_users[$row->substitute_id]->name : 'None'
					)),
				);
			} else { // We got another class with same id. Which means more than one teachers in the same class. Add the teacher to the class.
				$classes[$row->id]['teachers'][] = array(
					'id'	=> $row->user_id,
					'name'	=> isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->name : 'None',
					'status'=> $row->status,
					'user_type'	=>isset($all_users[$row->user_id]) ? $all_users[$row->user_id]->user_type : 'None',
					'substitute_id'=>$row->substitute_id,
					'substitute' => ($row->substitute_id != 0 and isset($all_users[$row->substitute_id])) ? 
											$all_users[$row->substitute_id]->name : 'None'
				);
			}
		}
		
		$this->load->view('classes/batch_view', 
			array('classes'=>$classes, 'center_name'=>$center_name, 'batch_id'=>$batch_id, 'batch_name'=>$batch->name, 'from_date'=>$from_date, 'to_date'=>$to_date,
				'all_lessons'=>$all_lessons, 'all_user_names'=>$all_user_names));
				//$this->load->view('layout/footer');
	}
	
	function batch_view_save() {
		$lessons = $this->input->post('lesson_id');
		$substitutes = $this->input->post('substitute_id');
		$status = $this->input->post('status');
		
		$this->load->helper('misc_helper');
		foreach($lessons as $class_id => $lesson_id) {
			$this->class_model->save_class_lesson($class_id, $lesson_id);
			foreach($substitutes[$class_id] as $teacher_id => $substitute_id) {
				$this->class_model->save_class_teachers(0, array(
					'user_id'	=> $teacher_id,
					'class_id'	=> $class_id,
					'substitute_id'=>$substitute_id,
					'status'	=> $status[$class_id][$teacher_id],
				));
			}
		}
 	
		$this->session->set_flashdata('success', 'Batch information saved.');
		redirect('classes/batch_view/'.$this->input->post('batch_id').'/'.$this->input->post('from_date'));
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
	
	/// Cancel a class - the id must be given as the argument. If the batch_id argument is provided, goes batch to that batch's view.
	function cancel_class($class_id, $batch_id=0, $date='') {
		$this->class_model->cancel_class($class_id);
		$this->session->set_flashdata('success', 'Class has been cancelled');
		redirect('classes/batch_view/'.$batch_id.'/'.$date);
	}
	
	/// Un-Cancel a class - If the user cancels a class by accident, do this.The id must be given as the argument.
	function uncancel_class($class_id, $batch_id=0, $date='') {
		$this->class_model->uncancel_class($class_id);
		$this->session->set_flashdata('success', 'Class cancellation reverted.');
		redirect('classes/batch_view/'.$batch_view.'/'.$date);
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
							$date_index = date('m-d',strtotime($class->class_on));
							if(!in_array($date, $days_with_classes)) {
								$days_with_classes[$date_index] = $date;
							}
							$data[$center->id]['class'][$level->id][$date_index] = $class;
						}
					}
				}
			}
			ksort($days_with_classes);
			$data[$center->id]['days_with_classes'] = $days_with_classes;
		}
		
		
		$this->load->view('classes/class_progress_report', array(
			'data'=>$data, 'all_lessons'=>$all_lessons,
			'all_centers'=>$all_centers, 'all_levels'=>$all_levels));
		
	}
	
	/// MADSheet in User mode.
	function madsheet() {
		$this->user_auth->check_permission('classes_madsheet');
		
		if($this->input->post('city_id') and $this->user_auth->check_permission('change_city')) {
			$city_id = $this->input->post('city_id');
			$this->session->set_userdata('city_id', $city_id);
			$this->center_model->city_id = $city_id;
			$this->user_model->city_id = $city_id;
		}
		
		$all_centers = $this->center_model->get_all();
		$all_levels = array();
		
		$users = $this->user_model->search_users(array('not_user_type'=>array('applicant','well_wisher'),'status'=>false, 'city_id'=>0));
 		$all_users = idNameFormat($users);
		$all_user_credits = idNameFormat($users, array('id','credit'));
		$all_lessons = idNameFormat($this->book_lesson_model->get_all_lessons());
		
		$data = array();
		foreach($all_centers as $center) {
			//if($center->id != 1) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
		
			$data[$center->id] = array(
				'center_id'	=> $center->id,
				'center_name'=>$center->name,
			);
			$batches = $this->batch_model->get_class_days($center->id);
			$all_levels[$center->id] = $this->level_model->get_all_levels_in_center($center->id);
			
			$data[$center->id]['batches'] = array();
			foreach($batches as $batch_id => $batch_name) {
				//if($batch_id != 1) continue; // :DEBUG: Use this to localize the issue
				
				$data[$center->id]['batches'][$batch_id] = array('name'=>$batch_name);
				$days_with_classes = array();
				
				// NOTE: Each batch has all the levels in the center. Think. Its how that works.
				foreach($all_levels[$center->id] as $level) {
					//if($level->id != 71) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.
					
					$all_classes = $this->class_model->get_classes_by_level_and_batch($level->id, $batch_id);
					$class_info = array();
																	
					// Get the list of teachers first.
					$teachers_info = array();
					$last_class_id = 0;
					foreach($all_classes as $class) {
						if(isset($teachers_info[$class->user_id])) continue;
						
						$teachers_info[$class->user_id] = array(
							'id'		=> $class->user_id,
							'name'		=> !empty($all_users[$class->user_id])? $all_users[$class->user_id] : "",
							'credit'	=> $all_user_credits[$class->user_id],
							'user_type'	=> isset($users[$class->user_id]) ? $users[$class->user_id]->user_type : 'None',
							'classes'	=> array()
						);
					}
					
					$teacher_classes = array();
					foreach($teachers_info as $teacher_id=>$teacher_name) {
						foreach($all_classes as $class) {
							$date = date('d M',strtotime($class->class_on));
							if(!in_array($date, $days_with_classes)) {
								$days_with_classes[date('m-d',strtotime($class->class_on))] = $date;
							}
							
							if($class->user_id == $teacher_id) {
								// Get the Teacher data.
								$teacher_data = array(
									'user_id'		=> $class->user_id,
									'substitute_id'	=> $class->substitute_id,
									'user_type'		=> isset($users[$class->user_id]) ? $users[$class->user_id]->user_type : 'None',
									'status'		=> $class->status,
								);
								$class->teacher = $teacher_data;
								$teachers_info[$teacher_id]['classes'][] = $class;
							}
						}
					}
					
					
					$data[$center->id]['batches'][$batch_id]['levels'][$level->id]['name'] = $level->name;
					$data[$center->id]['batches'][$batch_id]['levels'][$level->id]['users'] = $teachers_info;
				}
				
				// Make sure that the class dates are ordered correctly. 
				ksort($days_with_classes);
				$days_with_classes = array_values($days_with_classes);

				$data[$center->id]['batches'][$batch_id]['days_with_classes'] = $days_with_classes;
				$days_with_classes = array();
			}
		}
		
		$this->load->view('classes/madsheet', array(
			'data'=>$data, 'all_lessons'=>$all_lessons,
			'all_centers'=>$all_centers, 'all_users'=>$all_users,'all_levels'=>$all_levels));
	}
	
	/// MADSheet in Class Mode.
	function madsheet_class_mode() {
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
							if(!in_array($date, $days_with_classes)) $days_with_classes[date('m-d',strtotime($class->class_on))] = $date;
							
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
	
	function edit_class($class_id,$type=false) {
		// First see if the user takes this class. Then he has premission to edit it.
		if(!$this->class_model->is_class_teacher($class_id, $this->session->userdata('id'))) {
			$this->user_auth->check_permission('class_edit_class');
		}
		
		$this->load->helper('form');
	
		$class_details = $this->class_model->get_class($class_id);
		$level_details = $this->level_model->get_level($class_details['level_id']);
		$teachers = idNameFormat($this->user_model->get_users_in_city());
		$substitutes = $teachers;
		$substitutes[0] = 'No Substitute';
		$substitutes[-1] = 'Other City';
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
			'all_lessons'=>$all_lessons,'from'=>$type));
	}
	
	function edit_class_save($from=false)	 {
		$class_id=$_REQUEST['class_id'];
		if(!$this->class_model->is_class_teacher($class_id, $this->session->userdata('id'))) {
			$this->user_auth->check_permission('class_edit_class');
		}
		
		$teacher_ids = $this->input->post('user_id');
		$user_class_id = $this->input->post('user_class_id');
		 $substitute_ids = $this->input->post('substitute_id');
		// print_r($substitute_ids );
		$statuses = $this->input->post('status');
		$teacher_count = count($user_class_id);
		// There might be multiple teachers in a class.
		for($i = 0; $i<$teacher_count; $i++) {
			if(!$teacher_ids[$i]) continue;
			
			$this->class_model->save_class_teachers($user_class_id[$i], array(
				'user_id'	=>	$teacher_ids[$i],
				'substitute_id'=>$substitute_ids[$i],
				'status'	=> $statuses[$i],
			));
		}
		
		if($this->input->post('lesson_id'))
			$this->class_model->save_class_lesson($this->input->post('class_id'), $this->input->post('lesson_id'));
		
		$this->session->set_flashdata('success', 'Saved the class details');
		if($from =='batch'){ redirect('classes/batch_view/'.'11');} else { redirect('classes/index/');}
	}
	
	function confirm_class($class_id) {
		$this->class_model->confirm_class($class_id, $this->session->userdata('id'));
		echo '{"success": "Confirmed"}';
	}
	
	function add_manually($batch_id, $center_id) {
		$this->user_auth->check_permission('debug');
		$this->load->view('classes/add_manually', array('batch_id'=>$batch_id, 'center_id'=>$center_id));
	}
	
	function add_manually_save() {
		$this->user_auth->check_permission('debug');
		
		$batch_id = $this->input->post('batch_id');
		$batch = $this->batch_model->get_batch($batch_id);
		$class_date = $this->input->post('class_date') . ' ' . $batch->class_time;
		$user_class_id = array();
		$teachers = $this->batch_model->get_batch_teachers($batch->id);
		foreach($teachers as $teacher) {
			// Make sure its not already inserted.
			if(!$this->class_model->get_by_teacher_time($teacher->id, $class_date)) {
				$user_class_id[] = $this->class_model->save_class(array(
					'batch_id'	=> $batch->id,
					'level_id'	=> $teacher->level_id,
					'teacher_id'=> $teacher->id,
					'substitute_id'=>0,
					'class_on'	=> $class_date,
					'status'	=> 'projected'
				));
			}
		}
		
		$this->session->set_flashdata('success', 'Added ' . count($user_class_id) . ' classes. If anything goes wrong in the MADSheet, send these numbers to Binny: ' . implode(',', $user_class_id));
		redirect('batch/index/center/'.$this->input->post('center_id'));
	}
	
	function add_class_manually($level_id, $batch_id, $class_on, $user_id) {
		$this->user_auth->check_permission('debug');
		
		list($class_id, $user_class_id) = $this->class_model->add_class_manually($level_id, $batch_id, $class_on, $user_id);
	
		$this->session->set_flashdata('success', "Created the class. Class.id: $class_id, UserClass.id: $user_class_id. Send these two numbers to Binny if anything goes wrong.");
		redirect('classes/madsheet');
	}
		
	function other_city_teachers($flag) {
		$data['flag'] = $flag;
		$data['cities']=$this->city_model->getCities();
		$this->load->view('classes/other_city_teachers',$data);
	}
	
	function city_teachers($city_id, $flag) {
		$data['flag']=$flag;
		$data['users']=$this->user_model->getuser_details(array('city_id'=>$city_id))->result();
		
		$this->load->view('classes/city_teachers',$data);
	}
	
	function update_city_teachers($user_id, $flag) {
		$data['substitute_id']=$user_id;
		$data['userName']=$this->user_model->get_user($user_id)->name;
		$data['flag'] = $flag;
		$this->load->view('classes/show_username',$data);
	}
	
	function delete($class_id) {
		$this->class_model->delete($class_id);
		$this->session->set_flashdata('success', "Deleted the class with ID $class_id.");
		redirect('classes/madsheet');
	}
}



