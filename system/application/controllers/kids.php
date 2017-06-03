<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Kids extends Controller  {
    function Kids() {
        parent::Controller();
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) redirect('auth/login');
		
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->helper('file');
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('level_model');
		$this->load->library('upload');
    }
	
	/// Show all kids - or if a Center ID is given, that centers kids
	function index($center_id = 0) {
		$this->user_auth->check_permission('kids_index');
		if($this->input->get_post('center_id')) $center_id = $this->input->get_post('center_id', true);

		set_city_year($this); // This page can get a change city request.
	
		$data = array(
			'details' 		=> $this->kids_model->get_all(0, $center_id),
			'center_list'	=> $this->center_model->get_all(),
			'page'			=> 'index',
			'center_id'		=> $center_id,
		);

		$this->load->view('layout/header',array('title'=>'Manage Kids'));
		$this->load->view('kids/index',$data);
		$this->load->view('layout/footer');
	}
	function manageaddkids($center_id = 0) { $this->index($center_id); } // Alias for backward compatibilty :DEPRICIATED: :ALIAS:

	/// Show the deleted kids of the current city.
	function show_deleted($city_id = 0) {
		$this->user_auth->check_permission('kids_show_deleted');

		$data = array(
			'page' 			=> 'show_deleted', 
			'details' 		=> $this->kids_model->get_deleted_kids($city_id),
			'center_list'	=> $this->center_model->get_all()
		);
		
		$this->load->view('layout/header',array('title'=>'Deleted Kids'));
		$this->load->view('kids/show_deleted', $data);
		$this->load->view('layout/footer');
	}

	/**
    *
    * Function to popupaddKids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupaddKids() {	
		$this->user_auth->check_permission('kids_add');
		
		$data['center']= $this->center_model->get_all();
		$this->load->view('kids/popups/addkids_popup',$data);
	}

	/**
    *
    * Function to popupEdit_kids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupEdit_kids()
	{
		$this->user_auth->check_permission('kids_edit');
		
		$uid = $this->uri->segment(3);
		$data['center']= $this->center_model->get_all();
		$data['kids_details']= $this->kids_model->get_kids_details($uid);
		$this->load->view('kids/popups/kids_edit_view',$data);
	}

	function popupDelete_kid() {
		$this->user_auth->check_permission('kids_delete');

		$uid = $this->uri->segment(3);
		$data = $this->kids_model->get_kids_details($uid)->row_array();
		$data['id'] = $uid;
		$this->load->view('kids/popups/kids_delete_view', $data);
	}

	/**
    *
    * Function to update_kids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function update_kids() {
		$this->user_auth->check_permission('kids_edit');
		
		$flag='';
		$data['rootId'] = $_REQUEST['rootId'];
		$id=$data['rootId'];
		$data['id']=$id;
		$data['center']=$_REQUEST['center'];
		$data['name']=$_REQUEST['name'];
		$data['sex']=$_REQUEST['sex'];
		
		$date= date('Y-m-d', strtotime($_REQUEST['birthday']));
		$data['birthday'] = $date;
		$data['description']=$_REQUEST['description'];
		$returnFlag= $this->kids_model->update_student($data);
		
		$config['upload_path'] = './uploads/kids/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg

		foreach($_FILES as $key => $value) {
            if( ! empty($key['name'])) {
                $this->upload->initialize($config);
                if ( ! $this->upload->do_upload($key)) {
                    $errors[] = $this->upload->display_errors();
                } else {
                    $flag = $this->users_model->process_pic($data, 'kids');
                }
            }
        }
		if($returnFlag != '') {
			$this->session->set_flashdata('success', 'Student updated successfully.');
			redirect('kids/manageaddkids');  
		} elseif($flag!= '') {
			$this->session->set_flashdata('success', 'Student updated successfully.');
			redirect('kids/manageaddkids');
		} else {
			$this->session->set_flashdata('error', 'Student not edited.');
			redirect('kids/manageaddkids');  
		}
	}
	
	/**
    *
    * Function to addkids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function addkids() {
		$this->user_auth->check_permission('kids_add');
		$data['center']	= $_REQUEST['center'];
		$data['name']	= $_REQUEST['name'];
		$data['sex']	= $_REQUEST['sex'];
		
		$data['date'] = '';
		if(!empty($_REQUEST['date-pick'])) {
			$data['date'] = date('Y-m-d', strtotime($_REQUEST['date-pick']));
		}
		$data['description']=$_REQUEST['description'];
		
		$returnFlag= $this->kids_model->add_kids($data);
		$data['id']=$returnFlag;
		
		$config['upload_path'] = dirname(BASEPATH) . '/uploads/kids/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg
		
		foreach($_FILES as $key => $value) {
            if( ! empty($key['name'])) {
                $this->upload->initialize($config);
        
                if ( ! $this->upload->do_upload($key)) {
                    $errors[] = $this->upload->display_errors();
                    
                } else {
                    $this->users_model->process_pic($data, 'kids');
                }
             }
        }
        
		if($returnFlag) {
			$this->session->set_flashdata('success', 'Student Inserted successfully');
			redirect('kids/manageaddkids');
		} else {
			$this->session->set_flashdata('error', 'Insertion Failed');
			redirect('kids/manageaddkids');
		}
	}
	
	function delete_student($student_id) {
		$this->user_auth->check_permission('kids_delete');

		$reason_for_leaving = $this->input->post('reason_for_leaving');

		$data = array('rootId' => $student_id, 'reason_for_leaving' => $reason_for_leaving);
		$this->kids_model->update_student($data);
		
		$flag = $this->kids_model->delete_kids($student_id);
		if($flag){
			$this->session->set_flashdata('success', 'The Student has been deleted successfully.');
			redirect('kids/manageaddkids');
		}
	}

	function undelete($student_id) {
		$this->user_auth->check_permission('kids_delete');

		$this->kids_model->undelete($student_id);

		$this->session->set_flashdata('success', 'The Student has been un-deleted.');
		redirect('kids/show_deleted');
	}

	/**
    *
    * Function to ajax_deleteStudent
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function ajax_deleteStudent($kid_id)
	{	
		$this->user_auth->check_permission('kids_delete');
		
		$flag= $this->kids_model->delete_kids($kid_id);
		if($flag){
			$this->session->set_flashdata('success', 'The Student has been deleted successfully.');
			redirect('kids/manageaddkids');
		}
	}

	/// Show all the students in the center with the level mappings. This is to move students between levels
	function students_in_center($center_id) {
		$action = $this->input->post('action');

		if($action == 'Save') {
			$student_level_mapping = $this->input->post('level_id');
			foreach($student_level_mapping as $student_id => $level_id) {
				$this->level_model->save_student_level_mapping($student_id, $level_id);
			}
			$this->session->set_flashdata('success', 'The Student Level Mapping saved.');
		}

		$all_students = idNameFormat($this->kids_model->get_kidsby_center($center_id)->result());
		$all_levels = idNameFormat($this->level_model->get_all_level_names_in_center($center_id));
		$all_levels[0] = 'None';
		$student_level_mapping_raw = $this->level_model->get_student_level_mapping($center_id);
		$student_level_mapping = idNameFormat($student_level_mapping_raw, array('student_id', 'level_id'));

		$this->load->view('kids/students_in_center',array(
				'title'			=> 'Student Class Assignment',
				'all_students'	=> $all_students,
				'all_levels'	=> $all_levels,
				'student_level_mapping'	=> $student_level_mapping,
			));
	}
	
	
	/// Importing the CSV file
	function import() {
		$centers = $this->center_model->get_all();
		$this->load->view('kids/import', array('centers'=>$centers));
	}
	
	function import_field_select() {
		// Read the CSV file and analyis it. Give the user a chance to make sure the connections are correct.
		if(!empty($_FILES['csv_file']['tmp_name'])) {
			ini_set('auto_detect_line_endings', true);
			$handle = fopen($_FILES['csv_file']['tmp_name'],'r');
			if(!$handle) die('Cannot open uploaded file.');
		
			$row_count = 0;
			$rows = array();
		
			//Read the file as csv
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$row_count++;
				$rows[] = $data;
				if($row_count > 5) break;
			}
			fclose($handle);
			move_uploaded_file($_FILES['csv_file']['tmp_name'], $_FILES['csv_file']['tmp_name']."_saved");
			
			$center_id = $this->input->post('center_id');
			
			$this->load->view('kids/import_field_select', array('all_rows'=>$rows, 'center_id'=>$center_id));
		}
	}
	
	/// User has made the choice - add the data into the database
	function import_action() {
		if($this->input->post('uploaded_file')) {
			if(!preg_match('/\/tmp\/[^\.]+$/', $this->input->post('uploaded_file'))) die("Hack attempt"); // someone changed the value of the uploaded_file in the form.
			
			$handle = fopen($this->input->post('uploaded_file'),'r');
			if(!$handle) die('Cannot open uploaded file.');
		
			$row_count = 0;
			$rows = array();
			$fields = $this->input->post('field');

			//Read the file as csv
			while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
				$row_count++;
				if($this->input->post('ignore_header') == 1 and $row_count == 1) continue; // Ignore the first row.
				
				$insert = array();
				$emails = array();
				$phones = array();
				
				foreach($data as $key=>$value) {
					if(empty($fields[$key])) continue;
					
					if($fields[$key] == 'Name') $insert['name'] = $value;
					elseif($fields[$key] == 'Description') $insert['description'] = $value;
					elseif($fields[$key] == 'Sex') $insert['sex'] = strtolower($value);
					elseif($fields[$key] == 'Birthday') $insert['birthday'] = date('Y-m-d', strtotime($value));
				}
				$insert['center_id'] = $this->input->post('center_id');
				
				if($insert['name'])
					$this->db->insert('Student', $insert);
			}
			fclose($handle);
			unlink($this->input->post('uploaded_file'));
			$this->load->view('kids/import_success');
		}
	}
}
