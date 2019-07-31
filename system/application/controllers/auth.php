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
	function index() {
		if (!$this->user_auth->logged_in()) {
			redirect('auth/login');
		} else {
			redirect('dashboard/dashboard_view');
		}
	}

	//log the user in
	function login($redirect_url = '') {
		if($this->user_auth->logged_in()) {
			if($redirect_url and $redirect_url != 'aHR0cDovL21ha2VhZGlmZi5pbi9jZnIv') // For some reason, google indexed this link. So if people search for madapp, they go to CFR page. Fixing that. 
				redirect(base64_decode($redirect_url));
			else redirect('dashboard/dashboard_view');
			exit;
		}

		// Just use the Auth app to do the authentication.
		$domain = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://$_SERVER[HTTP_HOST]/";
		if(stripos($domain, 'localhost')) $main_site_url = 'http://localhost/MAD/';
		else $main_site_url = $domain;

		$login_url = $main_site_url . 'apps/auth/';
		header("Location: " . $login_url . "?url=" . base64_encode($redirect_url));
		exit;
		
		//validate form input
		$this->form_validation->set_rules('email', 'Email Address', 'required|valid_email');
		$this->form_validation->set_rules('password', 'Password', 'required');

		if ($this->form_validation->run() == true) {
			//check to see if the user is logging in
			$remember = (bool) $this->input->post('remember'); //check for "remember me"

			if ($this->user_auth->login($this->input->post('email'), $this->input->post('password'), $remember)) {
				//if the login is successful
				if($this->input->post('redirect_url')) {
					redirect(base64_decode($this->input->post('redirect_url')), 'refresh');
					exit;
				}
				//redirect them back to the home page
				$this->session->set_flashdata('message', "Welcome, ".$this->session->userdata('name'));
				redirect('dashboard/dashboard_view', 'refresh');
			} else {
				//if the login was un-successful
				//redirect them back to the login page
				$this->session->set_flashdata('message', "Invalid login");
				redirect('auth/login'); //use redirects instead of loading views for compatibility with MY_Controller libraries
				
			}
		} else {  
			//the user is not logging in so display the login page
			//set the flash data error message if there is one
			$this->data['message'] = array('error' => (validation_errors()) ? validation_errors() : $this->session->flashdata('message'));
			$this->data['email'] = array('name' => 'email',
				'id' => 'email',
				'type' => 'text',
				'value' => $this->form_validation->set_value('email'),
                'class' => 'form-control',
                'placeholder' => 'Email',
			);
			$this->data['password'] = array('name' => 'password',
				'id' => 'password',
				'type' => 'password',
                'class' => 'form-control',
                'placeholder' => 'Password',
				//'value' => $this->form_validation->set_value('password'),
			);
			$this->data['redirect_url'] = $redirect_url;

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
	
	function forgotpassword()
	{
		$this->form_validation->set_rules('email','Email Address', 'required');
		
		if ($this->form_validation->run() == FALSE) { 
			$this->data['email'] = array('name' => 'email', 'id' => 'email', 'class' => 'form-control','placeholder' => 'Email/Phone');
			if(validation_errors()) 
				$this->session->set_flashdata('error',validation_errors());
				
			$this->load->view('auth/forgotpassword_view', $this->data);
		
		} else {
			$forgotten = $this->user_auth->send_password_reset_link($this->input->post('email'));
			if ($forgotten) { //if there were no errors
				$this->session->set_flashdata('success', "Your password reset link was sent to " .$this->input->post('email') );
				redirect("auth/login"); //we should display a confirmation page here instead of the login page
			} else {
				$this->session->set_flashdata('error', "Couldn't find a user with that email address/phone. Are you sure that this is correct - " .$this->input->post('email')."?");
				redirect("auth/forgotpassword");
			}
		}
	}
	
	public function reset_password($code)
	{
		$reset = $this->user_auth->find_reset_code($code);

		if ($reset) {  
			$password = $this->input->post('password');
			if($password != $this->input->post('password_confirm')) {
				$this->session->set_flashdata('message', 'Password and confirmation doesn\'t match - try again');
				redirect('auth/reset_password/' . $code);
				return;
			}

			if($password) {
				$this->load->model('users_model');
				$password_hash = password_hash($password, PASSWORD_BCRYPT, ['cost' => 10]);

				$changed = $this->users_model->updateuser([
					'rootId' => $reset->id, 
					'password' => $password
				]);
				$this->user_auth->disable_reset_code($code);

				$this->session->set_flashdata('success', "Password for {$reset->email} has been reset.");
				redirect("auth/login", 'refresh');

			} else {
				$this->load->view('auth/reset_password', $reset);
			}
		}
		else
		{ //if the reset didnt work then send them back to the forgot password page
			$this->session->set_flashdata('message', "Can't find any user with the given reset code.");
			redirect("auth/forgotpassword", 'refresh');
		}
	}

}
