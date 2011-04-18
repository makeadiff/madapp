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
		$this->load->helper('csv');
		$this->load->model('center_model');
		$this->load->model('project_model');
		$this->load->model('users_model');
		$this->load->model('city_model');
		$this->load->library('upload');
    }

    function index()
    {
        
    }
	/**
    * Function to manageadd_user
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function manageadd_user()
	{
		$this->user_auth->check_permission('user_index');
		$data['currentPage'] = 'db';
		$data['navId'] = '';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('user/user_view');
		$this->load->view('dashboard/includes/footer');
	
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
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
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
		$data['center']= $this->center_model->getcenter();
		$data['details']= $this->center_model->getcity();
		$data['project']= $this->project_model->getproject();
		$data['user_group']= $this->users_model->getgroup_details();
		//$this->load->view('user/popups/user_edit_view',$data);
		//$this->load->view('user/popups/user_edit_view',$data);	
		
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
		$data['name'] = $_POST['name'];
		$data['group'] = $_POST['group'];
		$data['position'] = $_POST['position'];
		$data['email'] = $_POST['email'];
		$data['password'] = $_POST['password'];
		$data['phone'] = $_POST['phone'];
		$data['city'] = $_POST['city'];
		$data['center'] = $_POST['center'];
		$data['project'] = $_POST['project'];
		$data['type'] = $_POST['type'];
		$data['id']= $this->users_model->adduser($data);
		
		$config['upload_path'] = dirname(BASEPATH) . '/uploads/';
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
			$returnFlag= $this->users_model->adduser_to_group($data);
			if($returnFlag)
			{
				$message['msg']   =  "User Added successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "popupAdduser";
				$message['linkText'] = "Add New User";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);
			}
			else
			{
				$message['msg']   =  "No Action performed.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupAdduser";
				$message['linkText'] = "Add New User";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);
			}
		}
		else	
		{
			$message['msg']   =  "No Group created.";
			$message['successFlag'] = "0";
			$message['link']  =  "popupAdduser";
			$message['linkText'] = "Add New User";
			$message['icoFile'] = "ico_addScheme.png";
			$this->load->view('dashboard/errorStatus_view',$message);
		}
	}
	/**
    * Function to popupEditusers
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupEditusers()
	{	
		$this->user_auth->check_permission('user_edit');
		$uid = $this->uri->segment(3);
		$data['center']= $this->center_model->getcenter();
		$data['details']= $this->center_model->getcity();
		$data['project']= $this->project_model->getproject();
		$data['user']= $this->users_model->user_details($uid);
		$content=$data['user']->result_array();
		//print_r($content);
		foreach($content as $row) 
		{
			$uid=$row['group_id'];
		}
		
		$data['group_name'] = $this->users_model->edit_group($uid);
		$data['group_details'] = $this->users_model->getgroup_details();
		$this->load->view('user/popups/user_edit_view',$data);
	}
	/**
    * Function to update_user
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function update_user() {
		$this->user_auth->check_permission('user_edit');
		$data['rootId'] = $_POST['rootId'];
		$data['name'] = $_POST['name'];
		
		$data['group'] = array();
		if(!empty($_REQUEST['group'])) $data['group'] = $_POST['group'];
		$data['email'] = $_POST['email'];
		$data['position'] = $_POST['position'];
		
		if($_POST['password']) {
			$data['password'] = $_POST['password'];
		}
		
		$data['phone'] = $_REQUEST['phone'];
		$data['city'] = $_REQUEST['city'];
		$data['center'] = $_REQUEST['center'];
		$data['project'] = $_REQUEST['project'];
		$data['type'] = $_REQUEST['type'];
		$flag= $this->users_model->updateuser($data);
		$returnFlag= $this->users_model->updateuser_to_group($data);
		$data['id']=$data['rootId'];
		$config['upload_path'] = './uploads/';
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
		
		
		if($flag || $returnFlag ) 
		{
			$message['msg']   =  "Profile edited successfully.";
			$message['successFlag'] = "1";
			$message['link']  =  "";
			$message['linkText'] = "";
			$message['icoFile'] = "ico_addScheme.png";
			$this->load->view('dashboard/errorStatus_view',$message);
		} else {
			$message['msg']   =  "Profile not edited.";
			$message['successFlag'] = "0";
			$message['link']  =  "";
			$message['linkText'] = "";
			$message['icoFile'] = "ico_addScheme.png";

			$this->load->view('dashboard/errorStatus_view',$message);
		}
	}
	
	function delete($user_id) {	
		$this->user_auth->check_permission('user_delete');
		$this->users_model->delete($user_id);
		
		$this->session->set_flashdata('success', 'User deleted successfully');
		redirect('user/users_view');
	}
	
	/**
    * Function to view_users
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function view_users()
	{
		$this->user_auth->check_permission('user_index');
		$data['title'] = 'Manage Volunteers';
		$data['all_cities'] = $this->city_model->get_city();
		$data['city_id'] = $this->session->userdata('city_id');
		if($this->input->post('city_id') !== false) $data['city_id'] = $this->input->post('city_id');
		
		$data['all_user_group'] = $this->users_model->getgroup_details();
		if($this->input->post('user_group') !== false) $data['user_group'] = $this->input->post('user_group');
		else $data['user_group'] = array();
		
		$data['name'] = '';
		if($this->input->post('name') !== false) $data['name'] = $this->input->post('name');
		
		$data['all_users'] = $this->users_model->search_users($data);
		
		$this->load->view('user/view_users', $data);
	}
	/**
    * Function to user_search
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function user_search()
	{
		$this->user_auth->check_permission('user_index');
		$data['city']=$_REQUEST['city'];
		$data['name']=$_REQUEST['name'];
		$group = $_REQUEST['group'];
		$data['title'] = 'Users View';
		
		//search by any city with group only
		if($data['city'] == 0 && $group !='' && $data['name']=='' )
		{
			$agents = substr($group,0,strlen($group)-1);
			$explode_agent = explode(",",trim($agents));
			for($i=0;$i<sizeof($explode_agent);$i++)
			{
		 		$data['group']=$explode_agent[$i];
		 		$data['details']= $this->users_model->searchuser_by_anycity($data);
				$flag=$data['details']->result_array();
				if($flag )
				{
				$this->load->view('user/update_search_view_list',$data);
				}
			}
		}
		//search by any city with group and name.
		else if($data['city'] == 0 && $group !='' && $data['name'] !='' )
		{
		
			$agents = substr($group,0,strlen($group)-1);
			$explode_agent = explode(",",trim($agents));
			for($i=0;$i<sizeof($explode_agent);$i++)
			{
		 		$data['group']=$explode_agent[$i];
		 		$data['details']= $this->users_model->searchuser_by_anycity_grp_name($data);
				$flag=$data['details']->result_array();
				if($flag )
				{
				$this->load->view('user/update_search_view_list',$data);
				}
			}
		
		}
		//search by city only.
		else if($data['city'] !='' && $data['name']=='' && $group=='')
		{
			$data['details']= $this->users_model->search_by_city($data);
			$this->load->view('user/update_search_view_list',$data);
		}
		//search by city with group and name.
		else if($data['city'] !='' && $data['name'] !='' && $group !='')
		{
			$agents = substr($group,0,strlen($group)-1);
			$explode_agent = explode(",",trim($agents));
			for($i=0;$i<sizeof($explode_agent);$i++)
			{
				$data['group']=$explode_agent[$i];
				$data['details']= $this->users_model->searchuser_details($data);
				$flag=$data['details']->result_array();
				if($flag )
				{
				$this->load->view('user/update_search_view_list',$data);
				}
				
			}
		}
		//search by city and group.
		else if($data['city'] !='' && $data['name'] =='' && $group !='')
		{
			$agents = substr($group,0,strlen($group)-1);
			$explode_agent = explode(",",trim($agents));
			for($i=0;$i<sizeof($explode_agent);$i++)
			{
				$data['group']=$explode_agent[$i];
				$data['details']= $this->users_model->searchuser_details_by_grp_city($data);
				$flag=$data['details']->result_array();
				if($flag )
				{
				$this->load->view('user/update_search_view_list',$data);
				}
				
			}
		
		
		}
		//search by city and name.
		 else if($data['city'] !='' && $data['name'] !='' && $group =='')
		 {
			$data['details']= $this->users_model->search_by_city_name($data);
			$this->load->view('user/update_search_view_list',$data);
		}
		else
		{
		echo "No result fount";
		}
	}
	
	
	/**
    * Function to csv_export
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function csv_export()
	{
		$this->user_auth->check_permission('user_export');
		$query= $this->users_model->getuser_details_csv();
		query_to_csv($query, TRUE, 'user_details.csv');
	}
	/**
    * Function to update_footer
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function update_footer()
	{
		$data['city']=$_REQUEST['city'];
		$data['name']=$_REQUEST['name'];
		$group=$_REQUEST['group'];
		$group_sub = substr($group,0,strlen($group)-1);
		$group_ex= explode(",",trim($group_sub));
		$data['group'] =implode("-",$group_ex);
		$this->load->view('user/update_csvbutton_footer',$data);
	}
	
	/**
    * Function to updated_csv_export
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function updated_csv_export()
	{
		$this->user_auth->check_permission('user_export');
		$data['city']=$this->uri->segment(3);
		$data['group']=$this->uri->segment(4);
		$data['name']=$this->uri->segment(5);
		$group=$data['group'];
		//search by any city with group only
		if($data['city'] == 0 && $data['group'] !='' && $data['name']=='' )
		{
			$query= $this->users_model->searchuser_by_anycity($data);
			//print_r($query->result());
			query_to_csv($query,TRUE,'user_details.csv');
		}
		//search by any city with group only
		else if($data['city'] == 0 && $group !='' && $data['name'] !='' )
		{
			$query= $this->users_model->searchuser_by_anycity_grp_name($data);
			query_to_csv($query,TRUE,'user_details.csv');
		
		}
		//search by city only.
		else if($data['city'] !='' && $data['name']=='' && $group=='')
		{
			$query= $this->users_model->search_by_city($data);
			query_to_csv($query,TRUE,'user_details.csv');
		
		}
		
		//search by city with group and name.
		else if($data['city'] !='' && $data['name'] !='' && $group !='')
		{
			$explode_agent = explode("-",trim($group));
			for($i=0;$i<sizeof($explode_agent);$i++)
			{
		 	$data['group']=$explode_agent[$i];
			//$query= $this->users_model->searchuser_details_csv($data);
			$query= $this->users_model->searchuser_details($data);
			$result=$query->result_array();
			foreach($result as $row)
			{
				$id=$row['id'];
				$name=$row['name'];
				$title=$row['title'];
				$email=$row['email'];
				$phone=$row['phone'];
				$center_name=$row['center_name'];
				$city_name=$row['city_name'];
				$user_type=$row['user_type'];
				
				$details_array=array( $id, $name, $title,$email,$phone,$center_name,$city_name,$user_type);
				
			}
			//$header_array=array('id','Name','Position Held','Email','Mobile No','Center','City');
			$array[$i]=$details_array;
		
			}
			array_to_csv($array,'user_details.csv');
		}
		//search by city and group.
		else if($data['city'] !='' && $data['name'] =='' && $group !='')
		{
				$explode_agent = explode("-",trim($group));
				//$j=0;
			for($i=0;$i<sizeof($explode_agent);$i++)
			{
		 	$data['group']=$explode_agent[$i];
			$query= $this->users_model->searchuser_details_by_grp_city($data);
			$result=$query->result_array();
			$j=0;
			foreach($result as $row)
			{
			
				$id=$row['id'];
				$name=$row['name'];
				$title=$row['title'];
				$email=$row['email'];
				$phone=$row['phone'];
				$center_name=$row['center_name'];
				$city_name=$row['city_name'];
				$user_type=$row['user_type'];
				
				$details_array=array( $id, $name, $title,$email,$phone,$center_name,$city_name,$user_type);
				$array[$j]=$details_array;
				$j++;
			}
			//$header_array=array('id','Name','Position Held','Email','Mobile No','Center','City');
			}
			array_to_csv($array,'user_details.csv');
		
		}
		//search by city and name.
		 else if($data['city'] !='' && $data['name'] !='' && $group =='')
		 {
			 $query= $this->users_model->search_by_city_name($data);
			 query_to_csv($query,TRUE,'user_details.csv');
		 }
	}
	
	function edit_profile()
	{	
		$data['msg']='';
		//$this->user_auth->check_permission('user_edit');
		$uid = $this->session->userdata('id');
		$data['center']= $this->center_model->getcenter();
		$data['details']= $this->center_model->getcity();
		$data['project']= $this->project_model->getproject();
		$data['user']= $this->users_model->user_details($uid);
		$content=$data['user']->result_array();
		//print_r($content);
		foreach($content as $row) 
		{
			$uid=$row['group_id'];
		}
		
		$data['group_name'] = $this->users_model->edit_group($uid);
		$data['group_details'] = $this->users_model->getgroup_details();
		$this->load->view('layout/header');
		$this->load->view('user/edit_profile_view',$data);
	
	}
	function update_profile()
	{
		$data['rootId'] = $this->session->userdata('id');
		$data['name'] = $_REQUEST['name'];
		
		$data['group'] = array();
		if(!empty($_REQUEST['group'])) $data['group'] = $_REQUEST['group'];
		$data['email'] = $_REQUEST['email'];
		$data['position'] = $_REQUEST['position'];
		$password=$_REQUEST['password'];
		if($password=='')
		{
			$password=$this->users_model->get_password($data);
			$password=$password->password;
			$data['password']=$password;
		}
		else{
		$data['password']=$password;
		}
		
		
		$data['phone'] = $_REQUEST['phone'];
		$data['city'] = $_REQUEST['city'];
		$data['center'] = $_REQUEST['center'];
		$data['project'] = $_REQUEST['project'];
		$data['type'] = $_REQUEST['type'];
		$flag= $this->users_model->updateuser($data);
		$returnFlag= $this->users_model->updateuser_to_group($data);
		$data['id']=$data['rootId'];
		$config['upload_path'] = './uploads/';
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
                    $flag1=$this->users_model->process_pic($data);
                }
             }
        }
		
		
		if($flag || $returnFlag ) 
		{
				$message['msg']   =  "Profile edited successfully.";
				$this->load->view('layout/header');
				$this->load->view('user/popups/profile_successview',$message);		  
			} else {
				$message['msg']   =  "Profile not edited.";
				$this->load->view('layout/header');
				$this->load->view('user/popups/profile_failview',$message);		  
		}
	}
	
	
	/// Importing the CSV file
	function import() {
		$this->load->view('user/import');
	}
	
	function import_field_select() {
		// Read the CSV file and analyis it. Give the user a chance to make sure the connections are correct.
		if(!empty($_FILES['csv_file']['tmp_name'])) {
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
			
			$this->load->view('user/import_field_select', array('all_rows'=>$rows));
		}
	}
	
	/// User has made the choice - add the data into the database
	function import_action() {
		if($this->input->post('uploaded_file')) {
			if(!preg_match('/^\/tmp\/[^\.]+$/', $this->input->post('uploaded_file'))) die("Hack attempt"); // someone changed the value of the uploaded_file in the form.
			
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
					elseif($fields[$key] == 'Role') $insert['title'] = $value;
					elseif($fields[$key] == 'Email') $insert['email'] = $value;
					elseif($fields[$key] == 'Phone') $insert['phone'] = $value;
				}
				$insert['city_id'] = $this->session->userdata('city_id');
				$insert['project_id'] = $this->session->userdata('project_id');
				$insert['user_type'] = 'volunteer';
				$insert['password'] = 'network'; //Default Password.
				$insert['credit'] = 3;
				
				$this->db->insert('User', $insert);
			}
			fclose($handle);
			unlink($this->input->post('uploaded_file'));
			$this->load->view('user/import_success');
		}
	}
	
}	


