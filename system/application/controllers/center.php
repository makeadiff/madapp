<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
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
		$this->load->model('users_model');
    }
	/*
     * Function Name : manageaddcenters()
     * Wroking :This function used for manage centers
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function manageaddcenters()
	{
		$this->user_auth->check_permission('center_index');		
		set_city_year($this);		
		$page_no = !empty($_REQUEST['pageno']) ? $_REQUEST['pageno'] : 0;
		$data['title'] = 'Manage Centers';
		$data['details']= $this->center_model->getcenter_details($page_no);		
		$this->load->view('layout/header',$data);
		$this->load->view('center/center_list',$data);
		$this->load->view('layout/footer');
	
	}
    /*
     * Function Name : getcenterlist()
     * Wroking :
     * @author:Rabeesh
     * @param :[$type,$item_id]
     * @return: type: []
     */
	function getcenterlist()
	{
	
		
	}
	/*
     * Function Name : popupaddCenter()
     * Wroking :This function used for viewing the window for add centers
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function popupaddCenter()
	{
		$this->user_auth->check_permission('center_add');
		$data['all_users']= $this->users_model->get_users_in_city();
		$this->load->view('center/popups/addcenter_popup',$data);
	}
/*
     * Function Name : addCenter()
     * Wroking :This function used for adding centers under the perticular city
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function addCenter() {
		$this->user_auth->check_permission('center_add');
		$data['city']= $this->session->userdata('city_id');
		$data['user_id']=$_REQUEST['user_id'];
		$data['center']=$_REQUEST['center'];
		$returnFlag= $this->center_model->add_center($data);
	
		if($returnFlag) {
			$this->session->set_flashdata('success', 'The Center has been added successfully');
			redirect('center/manageaddcenters');
		} else {
			$this->session->set_flashdata('error', 'Insertion Failed.');
			redirect('center/manageaddcenters');
		}
	
	}
	
        /*
     * Function Name : popupEdit_center()
     * Wroking :This function used for adding centers under the perticular city
     * @author:Rabeesh
     * @param :[$center_id]
     * @return: type: []
     */
	function popupEdit_center($center_id)
	{
		$this->user_auth->check_permission('center_edit');
		$data['details']= $this->center_model->edit_center($center_id);
		$data['all_users']= $this->users_model->get_users_in_city();
		$this->load->view('center/popups/center_edit_view',$data);
	}
	
   /*
     * Function Name : update_Center()
     * Wroking :This function used for updating centers
     * @author:Rabeesh
     * @param :[$center_id]
     * @return: type: []
     */
	function update_Center()
	{
		$this->user_auth->check_permission('center_edit');
		$data['rootId'] = $_REQUEST['rootId'];
		$data['user_id']= $_REQUEST['user_id'];
		$data['center']= $_REQUEST['center'];
		$returnFlag= $this->center_model->update_center($data);
		
		if($returnFlag == true) {
			$this->session->set_flashdata('success', 'The Center has been updated successfully');
			redirect('center/manage/'.$data['rootId']);		  
		} else {
			$this->session->set_flashdata('error', 'The Center updation failed.');
			redirect('center/manage/'.$data['rootId']);	  
		}
	
	}
	
	
/*
     * Function Name : deletecenter()
     * Wroking :This function used for deleting centers
     * @author:Rabeesh
     * @param :[$center_id]
     * @return: type: []
     */
	function deletecenter($center_id)
	{
		$this->user_auth->check_permission('center_delete');
				
		if($this->center_model->delete_center($center_id)) $this->session->set_flashdata("success", "Center deleted");
		else $this->session->set_flashdata("error", "Error deleting center.");
		
		redirect("center/manageaddcenters");
	}
	
	/*
     * Function Name : manage()
     * Wroking :This function used for managing centers.
     * @author:Rabeesh
     * @param :[$center_id]
     * @return: type: []
     */
	function manage($center_id) {
		$this->user_auth->check_permission('center_edit');
		
		$issues = $this->center_model->find_issues($center_id);
		$issues['center_name'] = $this->center_model->get_center_name($center_id);
		$issues['center_id'] = $center_id;
		$this->session->set_userdata("active_center", $center_id);
		
		$this->load->view('center/manage', $issues);
	}
}
