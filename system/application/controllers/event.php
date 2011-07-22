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

class Event extends controller{

	/**
    * constructor 
    **/
	function Event()
	{
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
		$this->load->library('session');
		$this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		$this->load->helper('url');
		$this->load->helper('misc');
		$this->load->helper('form');
		$this->load->helper('file');
		$this->load->model('kids_model');
		$this->load->model('center_model');
		$this->load->model('event_model');
		}
	/**
    *
    * Function to index
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function index()
	{
		$this->user_auth->check_permission('event_index');
		$this->load->view('layout/header',array('title'=>'Manage Event'));
		$data['details']= $this->event_model->getevent_list();
		$this->load->view('event/index',$data);
		$this->load->view('layout/footer');
	
	}
	/**
    *
    * Function to addevent
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function addevent()
	{
		$this->user_auth->check_permission('event_add');
		$data['center']= $this->center_model->getcity();
		$this->load->view('event/add_event',$data);
	}
	/**
    *
    * Function to insert_event
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function insert_event()
	{
		$this->user_auth->check_permission('event_add');
		//$data['city']=$_REQUEST['city'];
		$data['name']=$_REQUEST['name'];
		$data['startdate']=$_REQUEST['date-pick'];
		$data['enddate']=$_REQUEST['date-pick-ends'];
		$data['place']=$_REQUEST['place'];
		$data['type']=$_REQUEST['type'];
		$flag= $this->event_model->add_event($data);
		if($flag)
		{
			$this->session->set_flashdata('success', 'Event Added Successfully.');
			redirect('event/index');  
		}
		
	}
	/**
    *
    * Function to user_event
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function user_event()
	{
		$this->user_auth->check_permission('event_mark_attendance');
		$id=$this->uri->segment(3);
		$data['events']= $this->event_model->get_event_type($id);
		$data['users']= $this->users_model->getuser_details();
		$this->load->view('event/user_event',$data);
	}
	
	/**
    *
    * Function to insert_userevent
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function insert_userevent()
	{
		$this->user_auth->check_permission('event_mark_attendance');
		$data['event_id']=$_REQUEST['event'];
		$users=$_REQUEST['users'];
		for($i=0;$i< count($users);$i++)
		{
			$data['user_id']=$users[$i];
			$flag= $this->event_model->insert_user_event($data);
		}
		$this->session->set_flashdata('success', 'Users attendance marked.');
		redirect('event/index');  
	}
	/**
    *
    * Function to event_edit
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function event_edit()
	{
		$this->user_auth->check_permission('event_edit');
		$id=$this->uri->segment(3);
		$data['center']= $this->center_model->getcity();
		$data['event']= $this->event_model->getevent($id);
		$this->load->view('event/edit_event',$data);
	}
	/**
    *
    * Function to update_event
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function update_event()
	{
		$this->user_auth->check_permission('event_edit');
		$data['root_id']=$_REQUEST['root_id'];
		//$data['city']=$_REQUEST['city'];
		$data['name']=$_REQUEST['name'];
		$data['startdate']=$_REQUEST['date-pick'];
		$data['enddate']=$_REQUEST['date-pick-ends'];
		$data['place']=$_REQUEST['place'];
		$data['type']=$_REQUEST['type'];
		$flag= $this->event_model->update_event($data);
		if($flag)
		{
			$this->session->set_flashdata('success', 'Event Updated Successfully.');
			redirect('event/index');  
		}else{
		$this->session->set_flashdata('success', 'No Updation Performed.');
		redirect('event/index');
		}
		
	
	}
	/**
    *
    * Function to event_delete
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function event_delete()
	{
		$this->user_auth->check_permission('event_delete');
		$data['id']=$this->uri->segment(3);
		$flag= $this->event_model->delete_event($data);
		if($flag)
		{
			$this->session->set_flashdata('success', 'Event Deleted Successfully.');
			redirect('event/index');  
		}
	}
	/**
    *
    * Function to mark_attendence
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function mark_attendence()
	{
		$this->user_auth->check_permission('event_mark_attendance');
		$id=$this->uri->segment(3);
		$data['events']= $this->event_model->get_event_type($id);
		$data['attended_users']= $this->event_model->get_event_users($id);
		$this->load->view('event/attended_users',$data);
	}
	/**
    *
    * Function to update_userstatus
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function update_userstatus()
	{
		$this->user_auth->check_permission('event_mark_attendance');
		$data['event_id']=$this->uri->segment(3);
		$data['user_id']=$this->uri->segment(4);
		$flag= $this->event_model->update_user_status($data);
	
	}
	/**
    *
    * Function to update_user_status
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function update_user_status()
	{
		$this->user_auth->check_permission('event_mark_attendance');
		$this->session->set_flashdata('success', 'Status Updated  Successfully.');
		redirect('event/index');  
	}	

}
