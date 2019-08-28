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
		$login_url = MAD_APPS_FOLDER . 'auth/';
		header("Location: " . $login_url . "?url=" . base64_encode($redirect_url));
		exit;
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
