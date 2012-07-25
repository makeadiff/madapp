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
 
class Project extends Controller  {

    /**
     * constructor 
     * */
    function Project()
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
		$this->load->model('project_model');
		$this->load->model('kids_model');
		$this->load->model('level_model');
    }
   /*
     * Function Name : manage_project()
     * Wroking :This function used for showing the project window with all the project list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function manage_project()
	{
		$this->user_auth->check_permission('project_index');
		$data['title'] = 'Projects';
		$this->load->view('layout/header',$data);
		
		$data['details']= $this->project_model->getproject();
		$this->load->view('project/manage_project', $data);
		$this->load->view('layout/footer');
	}
	/*
     * Function Name : popupaddproject()
     * Wroking :This function used for showing the project add window.
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function popupaddproject()
	{
		$this->user_auth->check_permission('project_add');
		$this->load->view('project/popups/add_projects');
	}
	/*
     * Function Name : addproject()
     * Wroking :This function used for saving the project.
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function addproject()
	{
		$this->user_auth->check_permission('project_add');
		$data['name'] = $_REQUEST['name'];
	   	$returnFlag=$this->project_model->add_project($data);
		if($returnFlag){
	   	$this->session->set_flashdata("success", "The Project has been added successfully.");
	   	redirect('project/manage_project');
		} else {
		$this->session->set_flashdata("success", "The Project Not Added");
	   	redirect('project/manage_project');
		}
	}
	/*
     * Function Name : popupEdit_project()
     * Wroking :This function used for showing the project edit window.
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function popupEdit_project()
	{	
		$this->user_auth->check_permission('project_edit');
		$uid=$this->uri->segment(3);
		$data['details']=$this->project_model->get_project_byid($uid);
		$this->load->view('project/popups/edit_projects',$data);
	}
	/*
     * Function Name : update_project()
     * Wroking :This function used for updating the project details.
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function update_project()
	{
		$this->user_auth->check_permission('project_edit');
		$data['name']=$_REQUEST['name'];
		$data['rootId'] = $_REQUEST['rootId'];
		$returnFlag=$this->project_model->update_project($data);
		
		if($returnFlag == true) {
			$this->session->set_flashdata("success", "Project updated successfully.");
			redirect('project/manage_project');
		} else {
			$this->session->set_flashdata("success", "Failed To Update Project");
			redirect('project/manage_project');
		}
		
	}
	/*
     * Function Name : ajax_deleteproject()
     * Wroking :This function used for deleting the project details.
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function ajax_deleteproject()
	{	
		$this->user_auth->check_permission('project_delete');
		$data['entry_id'] = $this->uri->segment(3);
		$flag= $this->project_model->delete_project($data);
		$this->session->set_flashdata("success", "Project successfully Deleted.");
		redirect('project/manage_project');
	}
	
}