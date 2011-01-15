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
 
class Kids extends Controller  {

    /**
    * constructor 
    **/

    function Kids()
    {
        parent::Controller();
        
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('email');
		if($logged_user_id == NULL )
		{
			redirect('common/login');
		}
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('level_model');
    }
	
    /**
    *
    * Function to 
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/

    function index()
    {
        
    }

    /**
    *
    * Function to manageaddkids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function manageaddkids()
	{
		$data['currentPage'] = 'db';
		$data['navId'] = '2';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('kids/addkids_view');
		$this->load->view('dashboard/includes/footer');
	
	}
   /**
    *
    * Function to getkidslist
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function getkidslist()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Kids';
		$linkCount = $this->kids_model->kids_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->kids_model->getkids_details();
		$this->load->view('kids/kids_list',$data);
	
	}
	/**
    *
    * Function to popupaddKids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupaddKids()
	{
		$data['center']= $this->center_model->getcenter();
		//$data['level']= $this->level_model->getlevel();
		$this->load->view('kids/popups/addkids_popup',$data);
	
	}
	/**
    *
    * Function to popupEdit_kids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupEdit_kids()
	{
		$uid = $this->uri->segment(3);
		$data['center']= $this->center_model->getcenter();
		//$data['level']= $this->level_model->getlevel();
		$data['kids_details']= $this->kids_model->get_kids_details($uid);
		$this->load->view('kids/popups/kids_edit_view',$data);
	
	}
	/**
    *
    * Function to update_kids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function update_kids()
	{
		$data['rootId'] = $_REQUEST['rootId'];
		$data['center']=$_REQUEST['center'];
		$data['name']=$_REQUEST['name'];
		$date=$_REQUEST['date-pick'];
		$newdate=explode("/",$date);
		$data['date']=$newdate[2]."/".$newdate[1]."/".$newdate[0];
		$data['description']=$_REQUEST['description'];
		
		
		$returnFlag= $this->kids_model->update_student($data);
		
		if($returnFlag == true) 
			  {
					$message['msg']   =  "Student updated successfully.";
					$message['successFlag'] = "1";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('dashboard/errorStatus_view',$message);		  
			  }
			else
			  {
					$message['msg']   =  "Center not edited.";
					$message['successFlag'] = "0";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('dashboard/errorStatus_view',$message);		  
			 }
	
	
	}
	/**
    *
    * Function to addkids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function addkids()
	{
	$data['center']=$_REQUEST['center'];
	$data['name']=$_REQUEST['name'];
	$date=$_REQUEST['date-pick'];
	$newdate=explode("/",$date);
	$data['date']=$newdate[2]."/".$newdate[1]."/".$newdate[0];
	$data['description']=$_REQUEST['description'];
	
	$returnFlag= $this->kids_model->add_kids($data);
	
	if($returnFlag)
		  {
		  		$message['msg']   =  "Student added successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "popupaddKids";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
			
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
		else
		  {
		  		$message['msg']   =  "no updates performed.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupaddCneter";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
			
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
	}
	/**
    *
    * Function to ajax_deleteStudent
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function ajax_deleteStudent()
	{
		$data['entry_id'] = $_REQUEST['entry_id'];
		$flag= $this->kids_model->delete_kids($data);
	}
}