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
	/*
     * Function Name : manage_permission()
     * Wroking :This function used for showing permission window
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function manage_permission()
	{
		$this->user_auth->check_permission('permission_index');
		
		$data = array();
		$data['details']= $this->permission_model->getpermission_details();
		$this->load->view('layout/header',array('title'=>"Permission Settings"));
		$this->load->view('permission/permission_view', $data);
		$this->load->view('layout/footer');
	
	}
	
	/*
     * Function Name : popupAddPermission()
     * Wroking :This function used for showing permission add window
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function popupAddPermission()
	{
		$this->user_auth->check_permission('permission_add');
		$this->load->view('permission/popups/addpermission');
	
	}
	/*
     * Function Name : addpermission()
     * Wroking :This function used for save permissions
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function addpermission()
	{
		$this->user_auth->check_permission('permission_add');
		$permission = $_REQUEST['permission'];
		$returnFlag= $this->permission_model->add_permission($permission);
		if($returnFlag) {
			$this->session->set_flashdata('success', 'Permission Inserted Successfully !');
			redirect('permission/manage_permission');
		} else {
			$this->session->set_flashdata('success', 'Permission Insertion Failed !');
			redirect('permission/manage_permission');
		}
		
	}
	
	/*
     * Function Name : popupEdit_permission()
     * Wroking :This function used for showing permission edit window
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function popupEdit_permission()
	{
		$this->user_auth->check_permission('permission_edit');
		$uid=$this->uri->segment(3);
		$data['content']= $this->permission_model->getedit_permission($uid);
		$this->load->view('permission/popups/edit_permission',$data);
	}
	/*
     * Function Name : edit_permission()
     * Wroking :This function used for edit permissions
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function edit_permission()
	{
		$this->user_auth->check_permission('permission_edit');
		$data['permission'] = $_REQUEST['permission'];
		$data['rootId'] = $this->uri->segment(3);
		$returnFlag= $this->permission_model->update_permission($data);
		
		if($returnFlag == true) {
			$this->session->set_flashdata('success', 'Permission Updated Successfully !');
			redirect('permission/manage_permission');
		} else {
			$this->session->set_flashdata('success', 'Permission Updation Failed !');
			redirect('permission/manage_permission');
		}
	}
	
	/*
     * Function Name : ajax_deletepermission()
     * Wroking :This function used for delete permissions
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function ajax_deletepermission()
	{
		$this->user_auth->check_permission('permission_delete');
		$data['entry_id'] = $this->uri->segment(3);
		$returnFlag=$this->permission_model->delete_permission($data);
		if($returnFlag == true) {
			$this->session->set_flashdata('success', 'Permission Deleted Successfully !');
			redirect('permission/manage_permission');
		} else {
			$this->session->set_flashdata('success', 'Failed to Delete Permission!');
			redirect('permission/manage_permission');
		}
	}
}
