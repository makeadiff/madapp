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
    **/

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
    
	function manage_project()
	{
		$this->user_auth->check_permission('project_index');
		$data['title'] = 'Projects';
		$this->load->view('layout/header',$data);
		
		$data['details']= $this->project_model->getproject();
		$this->load->view('project/manage_project', $data);
		$this->load->view('layout/footer');
	}
	
	function popupaddproject()
	{
		$this->user_auth->check_permission('project_add');
		$this->load->view('project/popups/add_projects');
	}
	function addproject()
	{
		$this->user_auth->check_permission('project_add');
		$data['name'] = $_REQUEST['name'];
	   	$returnFlag=$this->project_model->add_project($data);
	   	$this->session->set_flashdata("success", "Project added.");
	   	redirect('project/manage_project');
	}
	
	function popupEdit_project()
	{	
		$this->user_auth->check_permission('project_edit');
		$uid=$this->uri->segment(3);
		$data['details']=$this->project_model->get_project_byid($uid);
		$this->load->view('project/popups/edit_projects',$data);
	}
	
	function update_project()
	{
		$this->user_auth->check_permission('project_edit');
		$data['name']=$_REQUEST['name'];
		$data['rootId'] = $_REQUEST['rootId'];
		$returnFlag=$this->project_model->update_project($data);
		
		if($returnFlag == true) {
			$this->session->set_flashdata("success", "Project updated successfully.");
		} else {
			$this->session->set_flashdata("error", "Project not edited.");
		}
		redirect('project/manage_project');
	}
	
	function ajax_deleteproject()
	{	
		$this->user_auth->check_permission('project_delete');
		$data['entry_id'] = $_REQUEST['entry_id'];
		$flag= $this->project_model->delete_project($data);
		redirect('project/manage_project');
	}
	
}