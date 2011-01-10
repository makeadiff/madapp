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
 
class Admin extends Controller  {

    /**
    * constructor 
    **/

    function Admin()
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
    * Function to dashboard
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
    function dashboard()
    {	
		
			$data['currentPage'] = 'db';
			$data['navId'] = '0';
		   
			$this->load->view('admin/includes/header',$data);
			$this->load->view('admin/includes/superadminNavigation',$data);
			$this->load->view('admin/dashboard');
			$this->load->view('admin/includes/footer');

    }
	/**
    *
    * Function to manageaddcenters
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function manageaddcenters()
	{
	$data['currentPage'] = 'db';
	$data['navId'] = '1';
	$this->load->view('admin/includes/header',$data);
	$this->load->view('admin/includes/superadminNavigation',$data);
	$this->load->view('admin/addcenter_view');
	$this->load->view('admin/includes/footer');
	
	}
	/**
    *
    * Function to getcenterlist
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function getcenterlist()
	{
		$page_no = (empty($_REQUEST['pageno'])) ? 1 : $_REQUEST['pageno'];
		$data['title'] = 'Manage Centers';
		$linkCount = $this->center_model->getcenter_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->center_model->getcenter_details($page_no);
		$this->load->view('admin/center_list',$data);
	}
	/**
    *
    * Function to popupaddCneter
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupaddCneter()
	{
		$data['details']= $this->center_model->getcity();
		$data['user_name']= $this->center_model->getheadname();
		$this->load->view('admin/popups/addcenter_popup',$data);
	}
	/**
    *
    * Function to addCenter
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function addCenter()
	{
		$data['city']=$_REQUEST['city'];
		$data['user_id']=$_REQUEST['user_id'];
		$data['center']=$_REQUEST['center'];
		$returnFlag= $this->center_model->add_center($data);
		
		if($returnFlag)
			{
		  	$message['msg']   =  "Center added successfully.";
			$message['successFlag'] = "1";
			$message['link']  =  "popupaddCneter";
			$message['linkText'] = "add new Center";
			$message['icoFile'] = "ico_addScheme.png";
			
			$this->load->view('admin/errorStatus_view',$message);
		  }
		else
		  {
		  	$message['msg']   =  "no updates performed.";
			$message['successFlag'] = "0";
			$message['link']  =  "popupaddCneter";
			$message['linkText'] = "add new Center";
			$message['icoFile'] = "ico_addScheme.png";
			
			$this->load->view('admin/errorStatus_view',$message);
		  }
	
	
	
	}
	/**
    *
    * Function to popupEdit_center
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupEdit_center()
	{
		$uid = $this->uri->segment(3);
		$data['details']= $this->center_model->edit_center($uid);
		$data['city']=$this->center_model->getcity();
		$data['user_name']= $this->center_model->getheadname();
		$this->load->view('admin/popups/center_edit_view',$data);
	}
	/**
    *
    * Function to update_Center
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function update_Center()
	{
		$data['rootId'] = $_REQUEST['rootId'];
		$data['city']=$_REQUEST['city'];
		$data['user_id']=$_REQUEST['user_id'];
		$data['center']=$_REQUEST['center'];
		$returnFlag= $this->center_model->update_center($data);
		
		if($returnFlag == true) 
			  {
					$message['msg']   =  "Center edited successfully.";
					$message['successFlag'] = "1";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('admin/errorStatus_view',$message);		  
			  }
			else
			  {
					$message['msg']   =  "Center not edited.";
					$message['successFlag'] = "0";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('admin/errorStatus_view',$message);		  
			 }
	
	}
	/**
    *
    * Function to ajax_deletecenter
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function ajax_deletecenter()
	{
		$data['entry_id'] = $_REQUEST['entry_id'];
		$flag= $this->center_model->delete_center($data);
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
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/includes/superadminNavigation',$data);
		$this->load->view('admin/addkids_view');
		$this->load->view('admin/includes/footer');
	
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
		$linkCount = $this->center_model->getcenter_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->kids_model->getkids_details();
		$this->load->view('admin/kids_list',$data);
	
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
		$data['level']= $this->level_model->get_all_levels();
		$this->load->view('admin/popups/addkids_popup',$data);
	
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
	$data['level']=$_REQUEST['level'];
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
			
				$this->load->view('admin/errorStatus_view',$message);
		  }
		else
		  {
		  		$message['msg']   =  "no updates performed.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupaddCneter";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
			
				$this->load->view('admin/errorStatus_view',$message);
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
		$data['level']= $this->level_model->get_all_levels();
		$data['kids_details']= $this->kids_model->get_kids_details($uid);
		$this->load->view('admin/popups/kids_edit_view',$data);
	
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
		$data['center'] = $_REQUEST['center'];
		$data['level']	= $_REQUEST['level'];
		$data['name']	= $_REQUEST['name'];
		
		$date = $_REQUEST['date-pick'];
		$newdate = explode("/",$date);
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
		
					$this->load->view('admin/errorStatus_view',$message);		  
			  }
			else
			  {
					$message['msg']   =  "Center not edited.";
					$message['successFlag'] = "0";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('admin/errorStatus_view',$message);		  
			 }
	
	
	}
	
}