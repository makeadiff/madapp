<?php if(!defined('BASEPATH')) exit('No direct script access allowed');
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
class Admincredit extends controller
{
	function Admincredit()
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
		$this->load->model('admincredit_model');
		$this->load->model('task_model');
		
	}
	/**
    * Function to index
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function index()
	{
		$this->user_auth->check_permission('admincredit_index');
		$this->load->model('users_model');
		
		$groups = $this->session->userdata('groups');
		$data['all_users'] = idNameFormat($this->users_model->get_users_in_city());
		
		if(in_array('Operations Fellow', $groups)
				or in_array('HR + EPH Fellow', $groups)
				or in_array('CR Fellow', $groups)
				or in_array('Placements Fellow', $groups)
				or in_array('PR Fellow', $groups)
				or in_array('National Team', $groups)) {
			$data['details']= $this->admincredit_model->get_credits_awarded_by();
			$title = 'Credits awarded by you';
		} else {
			$data['details']= $this->admincredit_model->get_credit();
			$title = 'Your Credits';
		}
		
		$this->load->view('layout/header',array('title'=>$title));
		$this->load->view('admincredit/index',$data);
		$this->load->view('layout/footer');
	}
	/**
    * Function to addcredit
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function addcredit()
	{
		$this->user_auth->check_permission('admincredit_add_credit');
		$data['users']= $this->admincredit_model->get_users();
		$data['task']= $this->admincredit_model->get_task();
		$this->load->view('admincredit/add_credit',$data);
	}
	
	/**
    * Function to insert_credit
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function insert_credit() {
		$this->user_auth->check_permission('admincredit_add_task');
		$data['user']=$_REQUEST['user'];
		$data['task_id']=$_REQUEST['task'];
		$flag= $this->admincredit_model->update_admincredits($data);
		if($flag)
		{
			$this->session->set_flashdata('success', 'Task Added Successfully.');
			redirect('admincredit/index');  
		}
	}
	/**
    * Function to alladmincredit
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function alladmincredit() {
		$this->user_auth->check_permission('admincredit_index_all');
		
		$this->load->view('layout/header',array('title'=>'All Admin Credit'));
		$data['details']= $this->admincredit_model->get_alladmincredit();
		$this->load->view('admincredit/alladmin_credit_index',$data);
		$this->load->view('layout/footer');
	}
	
	function delete($admincredit_id) {
		//$this->user_auth->check_permission('admincredit_delete');
		$this->admincredit_model->delete_admincredit($admincredit_id);
	
		$this->session->set_flashdata('success', 'Task Deleted Successfully.');
		redirect('admincredit/index');
	}
}
