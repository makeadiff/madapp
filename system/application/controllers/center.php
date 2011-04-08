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
 
class Center extends Controller  {

    /**
    * constructor 
    **/

    function Center()
    {
        parent::Controller();

		$this->load->library('session');
        $this->load->library('user_auth');
		$this->load->helper('url');
        $this->load->helper('form');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		
		$this->load->library('validation');
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('level_model');
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
		$this->user_auth->check_permission('center_index');
		
		$data['currentPage'] = 'db';
		$data['navId'] = '1';
		
		$page_no = !empty($_REQUEST['pageno']) ? $_REQUEST['pageno'] : 0;
		$data['title'] = 'Manage Centers';
		$linkCount = $this->center_model->getcenter_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->center_model->getcenter_details($page_no);
		
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('center/center_list',$data);
		$this->load->view('dashboard/includes/footer');
	
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
	
		
	}
	/**
    *
    * Function to popupaddCneter
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupaddCenter()
	{
		$this->user_auth->check_permission('center_add');
		$data['details']= $this->center_model->getcity();
		$data['user_name']= $this->center_model->getheadname();
		$this->load->view('center/popups/addcenter_popup',$data);
	}
	/**
    *
    * Function to addCenter
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function addCenter() {
		$this->user_auth->check_permission('center_add');
		$data['city']=$_REQUEST['city'];
		$data['user_id']=$_REQUEST['user_id'];
		$data['center']=$_REQUEST['center'];
		$returnFlag= $this->center_model->add_center($data);
	
		if($returnFlag) {
			$message['msg']   =  "Center added successfully. Next step - <a href='".site_url('level/creater/center/'.$returnFlag)."'>Add Levels</a>.";
			$message['successFlag'] = "1";
			$message['link']  =  "popupaddCneter";
			$message['linkText'] = "Add New Center";
			$message['icoFile'] = "ico_addScheme.png";
		
			$this->load->view('dashboard/errorStatus_view',$message);
		} else {
			$message['msg']   =  "No updates performed.";
			$message['successFlag'] = "0";
			$message['link']  =  "popupaddCneter";
			$message['linkText'] = "Add new Center";
			$message['icoFile'] = "ico_addScheme.png";
		
			$this->load->view('dashboard/errorStatus_view',$message);
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
		$this->user_auth->check_permission('center_edit');
		$uid = $this->uri->segment(3);
		$data['details']= $this->center_model->edit_center($uid);
		$data['city']=$this->center_model->getcity();
		$data['user_name']= $this->center_model->getheadname();
		$this->load->view('center/popups/center_edit_view',$data);
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
		$this->user_auth->check_permission('center_edit');
		$data['rootId'] = $_REQUEST['rootId'];
		$data['city']=$_REQUEST['city'];
		$data['user_id']=$_REQUEST['user_id'];
		$data['center']=$_REQUEST['center'];
		$returnFlag= $this->center_model->update_center($data);
		
		if($returnFlag == true) {
			$message['msg']   =  "Center edited successfully.";
			$message['successFlag'] = "1";
			$message['link']  =  "";
			$message['linkText'] = "";
			$message['icoFile'] = "ico_addScheme.png";

			$this->load->view('dashboard/errorStatus_view',$message);		  
		} else {
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
    * Function to ajax_deletecenter
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function deletecenter($center_id)
	{
		$this->user_auth->check_permission('center_delete');
		
		if($this->level_model->get_all_levels_in_center($center_id)) {
			show_error("This Center has levels under it. Please delete those first.");
		}
		if($this->kids_model->get_kidsby_center($center_id)->result()) {
			show_error("This Center has Kids under it. Please delete them first.");
		}
		
		$this->center_model->delete_center($data);
		
		$this->session->set_flashdata("success", "Center deleted");
		redirect("center/manageaddcenters");
	}
	
	
	function manage($center_id) {
		$this->user_auth->check_permission('center_edit');
		
		$issues = $this->center_model->find_issues($center_id);
		$issues['center_name'] = $this->center_model->get_center_name($center_id);
		$issues['center_id'] = $center_id;
		
		$this->load->view('center/manage', $issues);
	}
}
