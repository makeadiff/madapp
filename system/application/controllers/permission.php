<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package         MadApp
 * @author          Rabeesh
 * @copyright       Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link            http://orisysindia.com
 * @since           Version 1.0
 * @filesource
 */
class Permission extends controller {

	/**
    * constructor 
    **/
	function Permission()
	{
		parent::Controller();
		$this->load->library('session');
        $this->load->library('user_auth');
		$this->load->helper('url');
        $this->load->helper('form');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL )
		{
			redirect('auth/login');
		}
		$this->load->model('permission_model');

	}
    function index()
    {
        
    }
	/**
    * Function to manage_permission
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function manage_permission()
	{
		$this->user_auth->check_permission('permission_index');
		
		$data['currentPage'] = 'db';
		$data['navId'] = '5';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('permission/permission_view');
		$this->load->view('dashboard/includes/footer');
	
	}
	/**
    * Function to get_permissionlist
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function get_permissionlist()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Permission';
		$linkCount = $this->permission_model->permisssion_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->permission_model->getpermission_details();
		$this->load->view('permission/permission_list',$data);
	}
	
	/**
    * Function to popupAddPermission
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function popupAddPermission()
	{
		$this->user_auth->check_permission('permission_add');
		$this->load->view('permission/popups/addpermission');
	
	}
	/**
    * Function to addpermission
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function addpermission()
	{
		$this->user_auth->check_permission('permission_add');
		$permission = $_REQUEST['permission'];
		$returnFlag= $this->permission_model->add_permission($permission);
		if($returnFlag)
		  {
		  echo "Successfully Inserted!!";
		  }
		else
		  {
		  echo "Insertion failed!!";
		  }
	
	}
	/**
    * Function to popupEdit_permission
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function popupEdit_permission()
	{
		$this->user_auth->check_permission('permission_edit');
		$uid=$this->uri->segment(3);
		$data['content']= $this->permission_model->getedit_permission($uid);
		$this->load->view('permission/popups/edit_permission',$data);
	}
	/**
    * Function to edit_permission
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function edit_permission()
	{
		$this->user_auth->check_permission('permission_edit');
		$data['permission'] = $_REQUEST['permission'];
		$data['rootId'] = $this->uri->segment(3);
		$returnFlag= $this->permission_model->update_permission($data);
		
		if($returnFlag == true) 
			  {
				echo "Successfully Updated!!"; 
			  }
			else
			  {
				echo "Updation failed!!	 "; 
			 }
	}
	/**
    * Function to ajax_deletepermission
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function ajax_deletepermission()
	{
		$this->user_auth->check_permission('permission_delete');
		$data['entry_id'] = $_REQUEST['entry_id'];
		$returnFlag= $this->permission_model->delete_permission($data);
	}
	
}