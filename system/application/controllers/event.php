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
		$data['name']		= $this->input->post('name');
		$data['startdate']	= $this->input->post('date-pick');
		$data['enddate']	= $this->input->post('date-pick-ends');
		$data['description']= $this->input->post('description');
		$data['place']		= $this->input->post('place');
		$data['type']		= $this->input->post('type');
		$data['city_id']	= $this->session->userdata('city_id');
		
		$flag= $this->event_model->add_event($data);
		if($flag) {
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
	function user_event($event_id) {
		$this->user_auth->check_permission('event_mark_attendance');

		$data['events']= $this->event_model->get_event_type($event_id);
		$data['all_groups'] = idNameFormat($this->users_model->get_all_groups());
		// Remove some national level groups.
		unset($data['all_groups'][1]);
		unset($data['all_groups'][2]);
		unset($data['all_groups'][3]);
		unset($data['all_groups'][16]);
		$data['all_centers'] =idNameFormat($this->center_model->get_all());
		
		$data['selected_centers'] = $this->input->post('center');
		if(!$data['selected_centers']) $data['selected_centers'] = array();
		$data['selected_user_groups'] = $this->input->post('user_group');
		if(!$data['selected_user_groups']) $data['selected_user_groups'] = array();
		
		$user_data['center'] = $data['selected_centers'];
		$user_data['user_group'] = $data['selected_user_groups'];
		$user_data['get_user_groups'] = true;
		$user_data['user_type'] = 'volunteer';
		$all_users = $this->users_model->search_users($user_data);
		
		
		$this->load->view('event/user_event',$data);
		
		foreach($all_users as $row) {
			$event_users = $this->event_model->getEventUser($event_id, $row->id);
			$user_data = array(
				'name'		=> $row->name,
				'user_id'	=> $row->id,
				'selected'	=> false,
			);
			
			if(count($event_users)) {
				$user_data['selected'] = true;
			}			
			
			$this->load->view('event/user_event_user_view', $user_data);
		}
		$this->load->view('event/user_event_footer',$data);
		
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
		$user_id_list=array();
		if($this->input->post('users')){
			$users = $_REQUEST['users'];
			$evnt = $this->event_model->get_user_event($data);
			$ds = $evnt->result_array();
			
			foreach($ds as $row) {
				$user_id_list[] = $row['user_id'];
			}
			
			if(count($users) < count($user_id_list)) {
				$difference=array_diff($user_id_list,$users);
				$difference_key=array_keys($difference);
				for($i=0;$i< count($difference_key);$i++) {
					$key=$difference_key[$i];
					$data['user_id']=$difference[$key];
					$flag= $this->event_model->delete_user_event($data);
				}
			}
			
			for($i=0;$i< count($users);$i++) {
				$data['user_id']=$users[$i];
				$flag= $this->event_model->insert_user_event($data);
			}
		
			$this->session->set_flashdata('success', 'Users attendance marked.');
			redirect('event/index'); 
		} else{ 
			$flags=$this->event_model->deletefull_user_event($data);
			if($flags) {
				$this->session->set_flashdata('success', 'Users attendance marked.');
				redirect('event/index'); 
			} else {
				$this->session->set_flashdata('success', 'Please Select One user.');
				redirect('event/index'); 
			}
		}
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
