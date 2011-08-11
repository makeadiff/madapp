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
		$this->load->view('layout/header',array('title'=>'Admin Credit'));
		$data['details']= $this->admincredit_model->get_credit();
		echo count($data['details']);
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
		$this->user_auth->check_permission('event_add');
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
	function insert_credit()
	{
		
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
	function alladmincredit()
	{
		$this->load->view('layout/header',array('title'=>'Admin Credit'));
		$data['details']= $this->admincredit_model->get_alladmincredit();
		$this->load->view('admincredit/alladmin_credit_index',$data);
		$this->load->view('layout/footer');
	}
}
