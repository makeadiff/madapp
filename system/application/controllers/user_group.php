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
	/*
     * Function Name : manageadd_group()
     * Wroking :This function used create manage group window
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function manageadd_group()
	{
		$this->user_auth->check_permission('user_group_index');
		
		$data['title'] = 'Manage User Group';
		$data['details']= $this->users_model->getgroup_details();
		$this->load->view('layout/header',$data);
		$this->load->view('user_group/add_groupname_view', $data);
		$this->load->view('layout/footer');
	}
	
    /*
     * Function Name : popupaddgroup()
     * Wroking :This function used create window for popup window for add group
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
    function popupaddgroup()
	{	
		$this->user_auth->check_permission('user_group_add');
		$data['permission']= $this->permission_model->getpermission_details();
		$this->load->view('user_group/popups/add_group',$data);
	}
	/*
     * Function Name : addgroup_name()
     * Wroking :This function used saving group name.
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function addgroup_name()
	{	
		$this->user_auth->check_permission('user_group_add');
		$permission = $_REQUEST['permission'];
		$permission = substr($permission,0,strlen($permission)-1);
		$permission=explode(",",trim($permission));
		$groupname = $_REQUEST['groupname'];
		$group_id= $this->users_model->add_group_name($groupname);
		if($group_id)
		{
			$returnFlag= $this->users_model->add_group_permission($permission,$group_id);
			if($returnFlag) {
			$this->session->set_flashdata('success', "Successfully Inserted");
			redirect('user_group/manageadd_group');
			}else {
			$this->session->set_flashdata('success', "Successfully Inserted");
			redirect('user_group/manageadd_group');
			}
		}
		
	}
	/*
     * Function Name : popupEdit_group()
     * Wroking :This function used for create popup window for edit group
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function popupEdit_group()
	{		
		$this->user_auth->check_permission('user_group_edit');
		$uid = $this->uri->segment(3);
		$data['details']= $this->users_model->edit_group($uid);
		$data['permission']= $this->permission_model->getpermission_details();
		$data['group_permission']= $this->permission_model->getgroup_permission_details($uid);
		$this->load->view('user_group/popups/group_edit_view',$data);
	}
	/*
     * Function Name : updategroup_name()
     * Wroking :This function used for update group info
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function updategroup_name()
	{
		$this->user_auth->check_permission('user_group_edit');
	
		$group_id = $this->uri->segment(3);
		$group_name = $_REQUEST['groupname'];
		$permission = $_REQUEST['permission'];
		
		$this->users_model->update_group($group_id, $group_name);
		
		$returnFlag = $this->users_model->update_permission($group_id, $permission);
		
		if($returnFlag == true) 
			  {
				$this->session->set_flashdata('success', "Successfully Updated");
				redirect('user_group/manageadd_group');
			  }
			else
			  {
				$this->session->set_flashdata('error', "Updation Failed");
				redirect('user_group/manageadd_group');
			 }
	}
	/*
     * Function Name : updategroup_name()
     * Wroking :This function used for delete group info
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function ajax_deletegroup()
	{
		$this->user_auth->check_permission('user_group_delete');
		$data['entry_id'] = $this->uri->segment(3);
		$flag= $this->users_model->delete_group($data);
		if(flag){
		$this->session->set_flashdata('success', "Group Deleted Successfully");
		redirect('user_group/manageadd_group');
		} else {
		$this->session->set_flashdata('success', "Failed To Delete Group");
		redirect('user_group/manageadd_group');
		}
	}
	/*
     * Function Name : updategroup_name()
     * Wroking :This function used for view all  group permissions
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
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