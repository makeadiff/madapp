<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 * @package		madapp
 * @author		Rabeesh
 */
class Settings extends Controller {
	private $message;
	
	function Settings() {
		parent::Controller();
		$message = array('success'=>false, 'error'=>false);
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
	
		$this->load->scaffolding('Setting');
		$this->load->model('settings_model','model', TRUE);
		$this->load->helper('url');
	}
	/**
    * Function to index
    * @author : Rabeesh
    * @param  : [$data]
    **/
	function index() {
		$this->user_auth->check_permission('setting_index');
		$all_settings = $this->model->getsettings();
		$this->load->view('settings/settings_index', array('all_settings'	=> $all_settings, 'message'=>$this->message));
	}
	/**
    * Function to create
    * @author : Rabeesh
    * @param  : [$data]
    **/
	function create() {
		$this->user_auth->check_permission('setting_create');
		
		// Make a new setting.
		if($this->input->post('action') == 'New') {
			$data = array(
						'name'			=>	$this->input->post('name'), 
						'value'	=>	$this->input->post('value'),
						'data'	=>	$this->input->post('data'),
					);
			$this->model->addsetting($data);
			$this->message['success'] = 'The Setting has been added';
			$this->index();
		
		} else {
		// Show the form to make a new setting.
			$this->load->helper('form');
			$this->load->helper('misc');
			$this->load->model('Users_model','user_model');
			$this->load->view('settings/settings_view.php', array(
				'action' => 'New',
				
				));
		}
	}
	/**
    * Function to edit
    * @author : Rabeesh
    * @param  : [$data]
    **/
	function edit() {
		$this->user_auth->check_permission('setting_edit');
		if($this->input->post('action') == 'Edit') {
			$data = array(
						'name'			=>	$this->input->post('name'), 
						'value'	=>	$this->input->post('value'),
						'data'	=>	$this->input->post('data'),
					);
			$this->model->editsetting($data);
			$this->message['success'] = 'The Setting has been edited successfully';
			$this->index();
		
		} else {
			$this->load->helper('misc');
			$this->load->helper('form');
			$settings_id = $this->uri->segment(3);
			$settings = $this->model->get_settings($settings_id);
			$this->load->view('settings/settings_view.php', array(
				'action' 		=> 'Edit',
				'setting'			=> $settings
				));
		}
	}
	function delete()
	{
		$id = $this->uri->segment(3);
		$this->model->deletesetting($id);
		$this->message['success'] = 'The Setting successfully deleted';
		$this->index();
	}

}