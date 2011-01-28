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
		if($logged_user_id == NULL )
		{
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
	
		$data['currentPage'] = 'db';
		$data['navId'] = '';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('project/project_view');
		$this->load->view('dashboard/includes/footer');
	
	}
	function getprojectlist()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Projects';
		$linkCount = $this->project_model->project_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->project_model->getproject();
		$this->load->view('project/project_list',$data);
	
	}
	function popupaddproject()
	{
		$this->load->view('project/popups/add_projects');
	}
	function addproject()
	{
		$data['name'] = $_REQUEST['name'];
	   	$returnFlag=$this->project_model->add_project($data);
		
		if($returnFlag)
		  {
		  		$message['msg']   =  "project added successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "popupaddproject";
				$message['linkText'] = "add new Project";
				$message['icoFile'] = "ico_addScheme.png";
			
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
		else
		  {
		  		$message['msg']   =  "No Operations  performed.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupaddproject";
				$message['linkText'] = "add new Project";
				$message['icoFile'] = "ico_addScheme.png";
			
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
	}
	function popupEdit_project()
	{	
		$uid=$this->uri->segment(3);
		$data['details']=$this->project_model->get_project_byid($uid);
		$this->load->view('project/popups/edit_projects',$data);
	}
	function update_project()
	{
		$data['name']=$_REQUEST['name'];
		$data['rootId'] = $_REQUEST['rootId'];
		$returnFlag=$this->project_model->update_project($data);
		if($returnFlag == true) 
			  {
					$message['msg']   =  "Project updated successfully.";
					$message['successFlag'] = "1";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
					$this->load->view('dashboard/errorStatus_view',$message);		  
			  }
			else
			  {
					$message['msg']   =  "Project not edited.";
					$message['successFlag'] = "0";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('dashboard/errorStatus_view',$message);		  
			 }
	
	}
	function ajax_deleteproject()
	{
		$data['entry_id'] = $_REQUEST['entry_id'];
		$flag= $this->project_model->delete_project($data);
	
	}
	
}