<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package         MadApp
 * @author          Rabeesh
 * @copyright       Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link            http://orisysindia.com
 * @since           Version 1.0
 * @filesource
 */
class User extends Controller  {
    /*
    * constructor 
    **/
    function User()
    {
        parent::Controller();
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->helper('misc');
		$this->load->model('center_model');
		$this->load->model('project_model');
		$this->load->model('users_model');
		$this->load->model('city_model');
		$this->load->library('upload');
    }

    function index()
    {
        
    }
    
    /// View all the important details about the user in one convinent location.
    function view($user_id) {
		$this->user_auth->check_permission('user_view');
		$data['all_cities']= $this->city_model->get_all(0);
		$data['all_cities'][0] = 'None';
		$data['user'] = $this->users_model->user_details($user_id);
		$data['user']->intern_credit = $this->users_model->get_intern_credit($user_id);
	
		$data['all_groups'] = idNameFormat($this->users_model->get_all_groups());
		
		$this->load->view('user/view',$data);
    }

	/**
    * Function to get_userlist
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_userlist()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Users';
		$linkCount = $this->users_model->users_count();
		$data['details']= $this->users_model->getuser_details();
		$this->load->view('user/user_list',$data);
	}
	
	/**
    * Function to popupAdduser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupAdduser()
	{
		$this->user_auth->check_permission('user_add');
		$data['all_cities']= $this->city_model->get_all();
		$data['all_cities'][0] = 'None';
		$data['all_groups'] = idNameFormat($this->users_model->get_all_groups());
		$data['this_city_id'] = $this->session->userdata('city_id');
		$data['this_project_id'] = $this->session->userdata('project_id');
	
		$this->load->view('user/popups/add_user',$data);
	}
	
	/**
    * Function to adduser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function adduser()
	{
		$this->user_auth->check_permission('user_add');
		$data['name'] = $_POST['names'];
		$data['group'] = $this->input->post('group');
		$data['email'] = $_POST['emails'];
		$data['password'] = $_POST['spassword'];
		$data['phone'] = $_POST['phone'];
		$data['address'] = $_POST['address'];
		$data['sex'] = $_POST['sex'];
		$data['joined_on'] = $_REQUEST['joined_on'];
		$data['left_on'] = $_REQUEST['left_on'];
		$data['type'] = $_POST['type'];
			
		$data['city'] = $this->session->userdata('city_id');
		$data['project'] = $this->session->userdata('project_id');
		
		$data['id']= $this->users_model->adduser($data);
		
		$config['upload_path'] = dirname(BASEPATH) . '/uploads/users/';
                $config['allowed_types'] = 'gif|jpg|png';
                $config['max_size']    = '1000'; //2 meg
		foreach($_FILES as $key => $value)
        {
            if( ! empty($key['name']))
            {
                $this->upload->initialize($config);
        
                if ( ! $this->upload->do_upload($key))
                {
                    $errors[] = $this->upload->display_errors();
                }    
                else
                {
                    $flag=$this->users_model->process_pic($data);
                }
             }
        }
		if($data['id'] !='')
		{
			$returnFlag= $this->users_model->adduser_to_group($data['id'], $data['group']);
			if($returnFlag) {
				$this->session->set_flashdata('success', 'User Inserted successfully');
			} else {
				$this->session->set_flashdata('error', 'User Insertion failed!');
			}
		} else  {
			$this->session->set_flashdata('error', "The User can't be added because email '".$data['email']."' is already taken");
		}
		redirect('user/view_users');
	}
	
	/**
    * Function to popupEditusers
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupEditusers($user_id)
	{	
		$this->user_auth->check_permission('user_edit');

		$data['all_cities']= $this->city_model->get_all(0);
		$data['all_cities'][0] = 'None';
		$data['user'] = $this->users_model->user_details($user_id);
	
		$data['all_groups'] = idNameFormat($this->users_model->get_all_groups());
		
		$this->load->view('user/popups/user_edit_view',$data);
	}
	
	/// Edits a user.
	function update_user() {
		$this->user_auth->check_permission('user_edit');
		$data['rootId'] = $this->input->post('rootId');
		$data['name'] = $this->input->post('names');
		
		$data['group'] = array();
		if(!empty($_POST['group'])) $data['group'] = $_POST['group'];
		$data['email'] = $this->input->post('emails');
		
		if($this->input->post('spassword')) $data['password'] = $this->input->post('spassword');
		
		$data['sex'] = $this->input->post('sex');
		$data['phone'] = $this->input->post('phone');
		if($this->input->post('city')) $data['city'] = $this->input->post('city');
		$data['address'] = $this->input->post('address');
		if($this->input->post('project')) $data['project'] = $this->input->post('project');
		if($this->input->post('type')) $data['type'] = $this->input->post('type');
		$data['joined_on'] = $this->input->post('joined_on');
		$data['left_on'] = $this->input->post('left_on');
		$data['reason_for_leaving'] = $this->input->post('reason_for_leaving');
		
		$flag= $this->users_model->updateuser($data);
		$returnFlag= $this->users_model->updateuser_to_group($data);
		$data['id']=$data['rootId'];
		$config['upload_path'] = './uploads/users/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg
        
        
		foreach($_FILES as $key => $value) {
            if(!empty($key['name'])) {
                $this->upload->initialize($config);
                if (!$this->upload->do_upload($key)) {
                    $errors[] = $this->upload->display_errors();
                } else {
                    $flag1=$this->users_model->process_pic($data);
                }
            }
        }
		
		if($flag || $returnFlag ) $this->session->set_flashdata('success', 'User Updated successfully');
		else $this->session->set_flashdata('error', 'User Updation failed');

		redirect('user/view_users');
	}


	function edit_bank_details($user_id = 0) {
		$this->user_auth->check_permission('user_edit_bank_details');
		if(!$user_id) $user_id = $this->session->userdata('id');

		$bank_details_all = $this->users_model->get_user_data($user_id, 'bank_%');
		$bank_details = array(
			'bank_name' 			=> '',
			'bank_address' 			=> '',
			'bank_account_number'	=> '',
			'bank_ifsc_code' 		=> '',
			'bank_account_type' 	=> '',
			'user_id' 				=> $user_id
		);

		foreach ($bank_details_all as $detail) {
			$bank_details[$detail['name']] = ($detail['value']) ? $detail['value'] : $detail['data'];
		}
		$this->load->view('user/popups/bank_details', $bank_details);
	}

	function save_bank_details() {
		$this->user_auth->check_permission('user_edit_bank_details');

		$user_id = $this->input->post('user_id');
		$bank_details = array('bank_name', 'bank_address', 'bank_account_number', 'bank_ifsc_code', 'bank_account_type');

		foreach ($bank_details as $key) {
			$data = array(
					'user_id'	=> $user_id,
					'name'		=> $key,
					'data'		=> $this->input->post($key)
				);
			$this->users_model->save_user_data($user_id, $data);
		}

		redirect('dashboard/dashboard_view');
	}


	function delete($user_id) {	
		$this->user_auth->check_permission('user_delete');
	
		if($this->users_model->delete($user_id)) $this->session->set_flashdata('success', 'User deleted successfully');
		else $this->session->set_flashdata('error', 'Error deleting User!');
		redirect('user/view_users');
	}
	
	function undelete($user_id) {
		$this->users_model->undelete($user_id);
		$this->session->set_flashdata('success', "User has been restored.");
		redirect('user/view/'.$user_id);
    }
	
	/// Bulk SMS and Email goes thru here.
	function bulk_communication() {
		if($this->input->post('action') == 'Send Emails') {
			$this->user_auth->check_permission('user_bulk_email');
			$this->load->library('email');
			
			$users = $this->input->post('users');
			$all_emails = $this->input->post('email');
			$user = $this->users_model->get_user($this->session->userdata('id'));

			$this->email->initialize(array('mailtype'=>'html'));
			$this->email->from($user->email, $user->name);
			$this->email->to('binnyva+Temp@gmail.com');
			foreach($users as $user_id) $this->email->bcc($all_emails[$user_id]);
			$this->email->subject($this->input->post('email-subject'));
			$this->email->message($this->input->post('email-content'));
			$this->email->send();
			
			$this->session->set_flashdata('success', "Emails sent to ".count($users)." people.");
			redirect('user/view_users/'.$this->input->post('query_string'));

		} elseif($this->input->post('action') == 'Update') {
			$this->user_auth->check_permission('user_bulk_edit');
			$users = $this->input->post('users');
			$group_ids = $this->input->post('group-bulk');
			$user_type = $this->input->post('user-type-bulk');

			if($group_ids) {
				foreach ($users as $user_id) {
					$this->users_model->adduser_to_group($user_id, $group_ids);
				}
			}

			if($user_type) {
				foreach ($users as $user_id) {
					$this->users_model->db->where('id', $user_id);
					$this->users_model->db->update('User', array('user_type' => $user_type));
				}
			}
			
			$this->session->set_flashdata('success', "Data updated for ".count($users)." people.");
			redirect('user/view_users/'.$this->input->post('query_string'));

		} elseif($this->input->post('action') == 'Delete Selected Users') {
			$this->user_auth->check_permission('user_delete');

			$users = $this->input->post('users');

			foreach ($users as $user_id) {
				$this->users_model->delete($user_id);
			}

			$this->session->set_flashdata('success', count($users)." users deleted.");
			redirect('user/view_users/'.$this->input->post('query_string'));
		}

	}
	
	/// The User index is handled by this action
	function view_users($city_id='', $user_groups=0, $name=0,$user_type='volunteer', $search_id=0, $email=0, $phone=0, $current_page=1) {
		$this->user_auth->check_permission('user_index');
		set_city_year($this);
		
		$data = array('title'=>'Manage Volunteers');
		
		// City selection...
		if($this->input->post('city_id') !== false) $data['city_id'] = $this->input->post('city_id');
		elseif($city_id != '') $data['city_id'] = $city_id;
		else $data['city_id'] = $this->session->userdata('city_id');
		
		// User group selection
		if($this->input->post('user_group') !== false) $data['user_group'] = $this->input->post('user_group');
		elseif($user_groups) $data['user_group'] = explode(',', $user_groups);
		else $data['user_group'] = array();

		// ID selection
		if($this->input->post('search_id') !== false) $data['search_id'] = $this->input->post('search_id');
		elseif($search_id) $data['search_id'] = $search_id;
		else $data['search_id'] = '';
		$data['id'] = $data['search_id'];
		// dump($search_id, $data['search_id']);

		// Name selection
		if($this->input->post('name') !== false) $data['name'] = $this->input->post('name');
		elseif($name) $data['name'] = $name;
		else $data['name'] = '';

		// Email selection
		if($this->input->post('email') !== false) $data['email'] = $this->input->post('email');
		elseif($email) $data['email'] = $email;
		else $data['email'] = '';

		// Phone selection
		if($this->input->post('phone') !== false) $data['phone'] = $this->input->post('phone');
		elseif($phone) $data['phone'] = $phone;
		else $data['phone'] = '';
		
		// User type
		if($this->input->post('user_type') !== false) $data['user_type'] = $this->input->post('user_type');
		elseif($user_type) $data['user_type'] = $user_type;
		else $data['user_type'] = 'volunteer';
		
		// Create the query_string.
		$group = implode(',', $data['user_group']);
		if(!$group) $group = 0;
		$name = $data['name'];
		if(!$name) $name = 0;

		// Paging
		if($this->input->post('current_page') !== false) $data['current_page'] = $this->input->post('current_page');
		elseif($current_page) $data['current_page'] = $current_page;
		else $data['current_page'] = 1;

		$data['items_per_page'] = 10;

		// This will be passed to the export page...
		$data['query_string'] = $data['city_id'] . '/' . $group . '/' . $name . '/' . $data['user_type'] . '/' . $search_id . '/' . $email . '/' . $phone . '/' . $current_page; 
		
		// If we don't have a query_string yet, get the necessary data from the hidden field.
		if(empty($data['query_string'])) {
			list($data['city_id'], $data['user_group'], $data['name'], $data['user_type']) = explode('/', $this->input->post('query_string'));
			if($data['name'] == '0') $data['name'] = '';
			if($data['user_group'] == '0') $data['user_group'] = array();
			else $data['user_group'] = explode(',', $data['user_group']);
			
			$data['query_string'] = $this->input->post('query_string');
		}
		
		// Some data needed for rendering the page.
		$data['all_cities'] = $this->city_model->get_all();
		$data['all_user_group'] = idNameFormat($this->users_model->get_all_groups());
		$data['get_user_groups'] = true;
		$data['get_user_class'] = true;

		$users_data = $this->users_model->search_users($data, true);

		$data['all_users'] = $users_data['all_users'];
		$data['total_items'] = $users_data['total_items'];
		$data['total_pages'] = $users_data['total_pages'];
		
		$this->load->view('user/view_users', $data);
	}
	
	function search_email() {
		$email = '';
		$phone = '';
		$id = '';
		$name = '';
		$data = array();
		
		if($this->input->post('id')) {
			$id = $this->input->post('id');
			$data = $this->users_model->db->query("SELECT * FROM User WHERE id='$id'")->result();
		
		} elseif($this->input->post('name')) {
			$name = $this->input->post('name');
			$data = $this->users_model->db->query("SELECT * FROM User WHERE name LIKE '%$name%'")->result();

		} else if($this->input->post('email')) {
			$email = $this->input->post('email');
			$data = $this->users_model->db->query("SELECT * FROM User WHERE email LIKE '%$email%'")->result();
		
		} else if($this->input->post('phone')) {
			$phone = $this->input->post('phone');
			$data = $this->users_model->db->query("SELECT * FROM User WHERE phone LIKE '%$phone%'")->result();
		}
		$this->load->view('user/search_email', array('email'=>$email, 'phone'=>$phone, 'name'=>$name, 'id'=>$id, 'search_id' => $id, 'data'=>$data));
	}

	/// Export to CSV
	function export($city_id='0', $user_group='0', $name='', $user_type="volunteer") {
		$this->user_auth->check_permission('user_export');
		$data['city_id']	= $city_id;
		$data['user_group']	= ($user_group == 0) ? array() : explode(',',$user_group);
		$data['name']		= ($name == 0) ? '' : $name;
		$data['user_type']	= $user_type;
		$data['get_user_groups'] = true;
		$data['get_user_class'] = true;
		
		$data['all_users'] = $this->users_model->search_users($data);
		header("Content-type: application/octet-stream");  
		header("Content-Disposition: attachment; filename=Volunteers.csv");  
		header("Pragma: no-cache");  
		header("Expires: 0");
		$this->load->view('user/export_csv', $data);
	}
	
	/**
    * Function to edit_profile
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function edit_profile()
	{	
		$uid = $this->session->userdata('id');
		$data['user']= $this->users_model->user_details($uid);
		$this->load->view('user/edit_profile',$data);
	}
	
	/**
    * Function to update_profile
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function update_profile()
	{
		$data['rootId'] = $this->session->userdata('id');
		$data['name'] = $this->input->post('name');
		$data['email'] = $this->input->post('email');
		$data['phone'] = $this->input->post('phone');
		$data['address'] = $this->input->post('address');
		$data['sex'] = $this->input->post('sex');
		
		if($this->input->post('password')) $data['password'] = $this->input->post('password');
		
		$flag = $this->users_model->updateuser($data);
		
		$data['id'] = $data['rootId'];
		$config['upload_path'] = dirname(BASEPATH) . '/uploads/users/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg
		$errors = array();

        if( ! empty($_FILES['image']['name'])) {
			$this->upload->initialize($config);
			if (!$this->upload->do_upload('image')) $errors[] = $this->upload->display_errors();
			else {
				$this->users_model->process_pic($data);
				$flag = 1;
			}
		}
		
		if($flag) {
			$this->session->set_flashdata('success', "Profile edited successfully.");
			redirect('user/edit_profile');
		} else {
			$this->session->set_flashdata('error', 'Profile not edited.');
			redirect('user/edit_profile');
		}
	}
	
	
	/// Importing the CSV file
	function import() {
		$this->user_auth->check_permission('user_add');
		$this->load->view('user/import/import');
	}
	
	function import_field_select() {
		ini_set("auto_detect_line_endings", "1");

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
			
			$this->load->view('user/import/import_field_select', array('all_rows'=>$rows));
		}
	}
	
	/// User has made the choice - add the data into the database
	function import_action() {
		ini_set("auto_detect_line_endings", "1");

		if($this->input->post('uploaded_file')) {
			if(!preg_match('/\/tmp\/[^\.]+$/', $this->input->post('uploaded_file'))) die("Hack attempt"); // someone changed the value of the uploaded_file in the form.
			$handle = fopen($this->input->post('uploaded_file'),'r');
			if(!$handle) die('Cannot open uploaded file.');
		
			$row_count = 0;
			$rows = array();
			$fields = $this->input->post('field');
			
			$message = array();
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
					elseif($fields[$key] == 'Role') $insert['title'] = $value;
					elseif($fields[$key] == 'Email') $insert['email'] = $value;
					elseif($fields[$key] == 'Phone') $insert['phone'] = $value;
				}
				$insert['city_id'] = $this->session->userdata('city_id');
				$insert['project_id'] = $this->session->userdata('project_id');
				$insert['user_type'] = 'volunteer';
				$insert['password'] = 'pass'; //Default Password.
				$insert['credit'] = 3;
				$insert['joined_on'] = date('Y-m-d');
				
				if($insert['name'] and $insert['email']) { // Make sure that we have the neceassy values before importing.
					$flag = $this->users_model->check_email_availability($insert);
				
					if($flag) {
						$message[] = "'$insert[name]' can't be imported - the email '$insert[email]' is already in the database";
					} else {
						$this->db->insert('User', $insert);
						
						// Add volunteer to the default user group...
						$default_group = 9; // :HARD-CODE: 9 being the default group
						$this->users_model->adduser_to_group($this->db->insert_id(), array($default_group)); 
					}
				}
			}
			fclose($handle);
			unlink($this->input->post('uploaded_file'));
			
			if($message) {
				$this->load->view('user/import/import_error',array('message'=>$message));
			} else {
				$this->load->view('user/import/import_success');
			}
		}
	}
	
	/**
    * Function to credithistory
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function credithistory($current_user_id = 0) {
		$for_user = '';
		if(!$current_user_id) $current_user_id = $this->session->userdata('id');
		else $for_user = ' of ' . $this->users_model->get_user($current_user_id)->name;
		$this->load->view('layout/header', array('title'=>'Credit History'.$for_user));

		$credit_log = $this->users_model->get_credit_history($current_user_id);
		
		$this->load->view('user/usercredit', array('credit_log'=>$credit_log));
		$this->load->view('layout/footer');
	}	
}	
	
