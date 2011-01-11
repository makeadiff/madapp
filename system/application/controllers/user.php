<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package         MadApp
 * @author          Rabeesh
 * @copyright       Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @license         http://orisysindia.com/licence/brilliant.html
 * @link            http://orisysindia.com
 * @since           Version 1.0
 * @filesource
 */
class User extends Controller  {

    /**
    * constructor 
    **/
    function User()
    {
        parent::Controller();
		$this->load->library('session');
        $this->load->library('user_auth');
		$this->load->helper('url');
        $this->load->helper('form');
		$logged_user_id = $this->session->userdata('email');
		if($logged_user_id == NULL )
		{
			redirect('common/login');
		}
		$this->load->model('center_model');
		$this->load->model('project_model');
		$this->load->model('users_model');
    }
	
    /**
    * Function to 
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/

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
		$data['center']= $this->center_model->getcenter();
		$data['details']= $this->center_model->getcity();
		$data['project']= $this->project_model->getproject();
		$data['user_group']= $this->users_model->getgroup_details();
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
	$data['name'] = $_REQUEST['name'];
	$data['group'] = $_REQUEST['group'];
	$data['position'] = $_REQUEST['position'];
	$data['email'] = $_REQUEST['email'];
	$data['password'] = $_REQUEST['password'];
	$data['phone'] = $_REQUEST['phone'];
	$data['city'] = $_REQUEST['city'];
	$data['center'] = $_REQUEST['center'];
	$data['project'] = $_REQUEST['project'];
	$data['type'] = $_REQUEST['type'];
	$data['insert_id']= $this->users_model->adduser($data);
	if($data['insert_id'])
	{
	$returnFlag= $this->users_model->adduser_to_group($data);
	if($returnFlag)
		  {
		  		$message['msg']   =  "User Added successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "popupAdduser";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
		else
		  {
		  		$message['msg']   =  "No Action performed.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupAdduser";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
	}
	else	
	{
		   	    $message['msg']   =  "No Group created.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupAdduser";
				$message['linkText'] = "add new Center";
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
		$uid = $this->uri->segment(3);
		$data['center']= $this->center_model->getcenter();
		$data['details']= $this->center_model->getcity();
		$data['project']= $this->project_model->getproject();
		$data['user_group']= $this->users_model->getgroup_details();
		$data['user']= $this->users_model->user_details($uid);
		$content=$data['user']->result_array();
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
	function update_user()
	{
	$data['rootId'] = $_REQUEST['rootId'];
	$data['name'] = $_REQUEST['name'];
	$data['group'] = $_REQUEST['group'];
	$data['position'] = $_REQUEST['position'];
	$data['email'] = $_REQUEST['email'];
	$data['password'] = $_REQUEST['password'];
	$data['phone'] = $_REQUEST['phone'];
	$data['city'] = $_REQUEST['city'];
	$data['center'] = $_REQUEST['center'];
	$data['project'] = $_REQUEST['project'];
	$data['type'] = $_REQUEST['type'];
	$flag= $this->users_model->updateuser($data);
	if($flag)
	{
	$returnFlag= $this->users_model->updateuser_to_group($data);
	if($returnFlag) 
			  {
					$message['msg']   =  "Profile edited successfully.";
					$message['successFlag'] = "1";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('dashboard/errorStatus_view',$message);		  
			  }
			else
			  {
					$message['msg']   =  "Profile not edited.";
					$message['successFlag'] = "0";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('dashboard/errorStatus_view',$message);		  
			 }
	}
	}
	/**
    * Function to ajax_deleteuser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function ajax_deleteuser()
	{
		$data['entry_id'] = $_REQUEST['entry_id'];
		$flag1= $this->users_model->delete_groupby_userid($data);
	}
}	