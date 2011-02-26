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
 class User_group extends Controller  {
    /**
    * constructor 
    **/
    function User_group()
    {
        parent::Controller();
        
		
		$this->load->library('session');
		$this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL )
		{
			redirect('auth/login');
		}
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('users_model');
		$this->load->model('permission_model');
    }
	 /**
    *
    * Function to manageadd_group
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function manageadd_group()
	{
		$this->user_auth->check_permission('user_group_index');
		$data['currentPage'] = 'db';
		$data['navId'] = '';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('user_group/add_groupname_view');
		$this->load->view('dashboard/includes/footer');
	
	}
	 /**
    *
    * Function to get_grouplist
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get_grouplist()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Group';
		$linkCount = $this->users_model->group_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->users_model->getgroup_details();
		$this->load->view('user_group/group_list',$data);
	
	
	}
    /**
    *
    * Function to popupaddgroup
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
    function popupaddgroup()
	{	
		$this->user_auth->check_permission('user_group_add');
		$data['permission']= $this->permission_model->getpermission_details();
		$this->load->view('user_group/popups/add_group',$data);
	}
	/**
    *
    * Function to addgroup_name
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function addgroup_name()
	{	
		$this->user_auth->check_permission('user_group_add');
		$permission = $_REQUEST['permission'];
		$groupname = $_REQUEST['groupname'];
		$group_id= $this->users_model->add_group_name($groupname);
		if($group_id)
		{
		$returnFlag= $this->users_model->add_group_permission($permission,$group_id);
		if($returnFlag)
		  {
		  		$message['msg']   =  "Group added successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "popupaddgroup";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
			
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
		else
		  {
		  		$message['msg']   =  "No actions performed.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupaddgroup";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
			
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
		}
		
	}
	/**
    *
    * Function to popupEdit_group
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupEdit_group()
	{		
		$this->user_auth->check_permission('user_group_edit');
		$uid = $this->uri->segment(3);
		$data['details']= $this->users_model->edit_group($uid);
		$data['permission']= $this->permission_model->getpermission_details();
		$data['group_permission']= $this->permission_model->getgroup_permission_details($uid);
		$this->load->view('user_group/popups/group_edit_view',$data);
	}
	/**
    *
    * Function to updategroup_name
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function updategroup_name()
	{
		$this->user_auth->check_permission('user_group_edit');
	
		
		$data['rootId'] = $_REQUEST['rootId'];
		$data['groupname']=$_REQUEST['groupname'];
		$data['permission'] = $_REQUEST['permission'];
		$this->users_model->update_group($data);
		
		$returnFlag=$this->users_model->update_permission($data);
		
		if($returnFlag == true) 
			  {
					$message['msg']   =  "Group edited successfully.";
					$message['successFlag'] = "1";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('dashboard/errorStatus_view',$message);		  
			  }
			else
			  {
					$message['msg']   =  "Group not edited.";
					$message['successFlag'] = "0";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('dashboard/errorStatus_view',$message);		  
			 }
	
	}
	/**
    *
    * Function to ajax_deletegroup
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function ajax_deletegroup()
	{
		$this->user_auth->check_permission('user_group_delete');
		$data['entry_id'] = $_REQUEST['entry_id'];
		$flag= $this->users_model->delete_group($data);
	
	}
	function view_permission()
	{
		$this->user_auth->check_permission('user_group_view');
		$uid = $this->uri->segment(3);
		$data['details']= $this->users_model->edit_group($uid);
		$data['permission']= $this->permission_model->getpermission_details();
		$data['group_permission']= $this->permission_model->getgroup_permission_details($uid);
		$this->load->view('user_group/popups/view_permission',$data);
	
	}
}