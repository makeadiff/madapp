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
		redirect_url('https://makeadiff.in/apps/auth/forgot_password.php');
	}
	
	public function reset_password($code)
	{
		redirect_url('https://makeadiff.in/apps/auth/forgot_password.php');
	}

}
