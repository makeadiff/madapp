<?php defined('BASEPATH') OR exit('No direct script access allowed');
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
if ( ! class_exists('Controller'))
{
	class Controller extends CI_Controller {}
}
class Auth extends Controller {


	function __construct()
	{
		parent::__construct();
		$this->load->library('user_auth');
		$this->load->library('session');
		$this->load->library('form_validation');
		$this->load->database();
		$this->load->helper('url');
	}

	//redirect if needed, otherwise display the user list
	function index()
	{
		if (!$this->user_auth->logged_in())
		{
			//redirect them to the login page
			redirect('auth/login');
		}
		else
		{
			//set the flash data error message if there is one
			$this->session->set_flashdata('message', "Welcome...");
			redirect('dashboard/dashboard_view');
		}
	}

	//log the user in
	function login()
	{
		//validate form input
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true)
		{ 
			//check to see if the user is logging in
			//check for "remember me"
			$remember = (bool) $this->input->post('remember');

			if ($this->user_auth->login($this->input->post('email'), $this->input->post('password'), $remember))
			{
				 //if the login is successful
				//redirect them back to the home page
				$this->session->set_flashdata('message', "Welcome, ".$this->session->userdata('name'));
				redirect('dashboard/dashboard_view', 'refresh');
			}
			else
			{ 
				//if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('message', "Invalid login");
				redirect('auth/login'); //use redirects instead of loading views for compatibility with MY_Controller libraries
				
			}
		}
		else
		{  
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
				//'value' => $this->form_validation->set_value('password'),
			);

			$this->load->view('auth/login', $this->data);
		}
	}
	
	function no_permission() {
		$this->load->view('auth/no_permission');
	}

	//log the user out
	function logout()
	{
		$this->data['title'] = "Logout";

		//log the user out
		$logout = $this->user_auth->logout();

		//redirect them back to the page they came from
		redirect('auth/login', 'refresh');
	}
	
	/**
	Function to forgotpassword
    * @author : rabeesh
    * @param  : []
    * @return : type : []
    **/
	function forgotpassword()
	{
		$this->form_validation->set_rules('email','Email Address', 'required|valid_email');
		
		if ($this->form_validation->run() == FALSE)
		{ 
			//$email =  $this->input->post('email');
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',);
				$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
			$this->load->view('auth/forgotpassword_view', $this->data);
		}
		
		else
		{
		//$this->data['message'] = (validation_errors()) ? validation_errors() : $this->session->flashdata('message');
		//$this->load->view('auth/forgotpassword_view', $this->data);
			$forgotten = $this->user_auth->forgotten_password($this->input->post('email'));
			if ($forgotten)
			{ //if there were no errors
				$this->session->set_flashdata('message', $this->ion_auth->messages());
				redirect("auth/login", 'refresh'); //we should display a confirmation page here instead of the login page
			}
			else
			{
				$this->session->set_flashdata('message', $this->user_auth->errors());
				redirect("auth/forgotpassword", 'refresh');
			}
		}
	}
	
	public function reset_password($code)
	{
		$reset = $this->user_auth->forgotten_password_complete($code);

		if ($reset)
		{  //if the reset worked then send them to the login page
			$this->session->set_flashdata('message', $this->user_auth->messages());
			redirect("auth/login", 'refresh');
		}
		else
		{ //if the reset didnt work then send them back to the forgot password page
			$this->session->set_flashdata('message', $this->user_auth->errors());
			redirect("auth/forgot_password", 'refresh');
		}
	}

}
