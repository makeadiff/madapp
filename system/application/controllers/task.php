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
class Task extends controller
{
        /*
        * constructor 
        */
	function Task()
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
		$this->load->model('task_model');
		
	}
        /*
     * Function Name : index()
     * Wroking :This function used for showing index of task window
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function index()
	{
		$this->user_auth->check_permission('task_index');
		$this->load->view('layout/header',array('title'=>'Manage Task'));
		$data['details']= $this->task_model->get_task();
		$this->load->view('task/index',$data);
		$this->load->view('layout/footer');
	}
        /*
     * Function Name : addtask()
     * Wroking :This function used for showing add window of task list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function addtask()
	{
		$this->user_auth->check_permission('task_add');
		$this->load->view('task/add_task');
	}
         /*
     * Function Name : insert_task()
     * Wroking :This function used for saving  task list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function insert_task()
	{
		$this->user_auth->check_permission('task_add');
		$data['name']=$_REQUEST['name'];
		$data['credit']=$_REQUEST['credit'];
		$data['type']=$_REQUEST['type'];
		$flag= $this->task_model->add_task($data);
		if($flag) {
			$this->session->set_flashdata('success', 'Task Added Successfully.');
			redirect('task/index');  
		}
	}
	 /*
     * Function Name : task_delete()
     * Wroking :This function used for deleting  task list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function task_delete()
	{
		$this->user_auth->check_permission('task_delete');
		$data['id']=$this->uri->segment(3);
		$flag= $this->task_model->delete_task($data);
		
		if($flag) {
			$this->session->set_flashdata('success', 'Task Deleted Successfully.');
			redirect('task/index');  
		}
	}
         /*
     * Function Name : task_edit()
     * Wroking :This function used for showing edit  task list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function task_edit($task_id) {
		$this->user_auth->check_permission('task_edit');
		$data['event']= $this->task_model->gettask($task_id);
		$this->load->view('task/edit_task',$data);
	}
	  /*
     * Function Name : update_task()
     * Wroking :This function used for updating task list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
	function update_task() {
		$this->user_auth->check_permission('task_edit');
		$data['root_id']=$_REQUEST['root_id'];
		$data['name']=$_REQUEST['name'];
		$data['credit']=$_REQUEST['credit'];
		$data['vertical']=$_REQUEST['type'];
		$flag= $this->task_model->update_task($data);
		
		if($flag) {
			$this->session->set_flashdata('success', 'Task Updated Successfully.');
			redirect('task/index');  
		} else {
			$this->session->set_flashdata('success', 'No Updation Performed.');
			redirect('task/index');
		}
	}
}
