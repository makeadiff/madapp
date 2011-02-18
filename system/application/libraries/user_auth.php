<?php

Class User_auth {

	protected $error_start_delimiter;
	protected $error_end_delimiter;
	private $ci;
	function User_auth() {
		$this->ci = &get_instance();
		$this->ci->load->model('users_model');
		$this->ci->load->model('ion_auth_model');
		$this->ci->load->library('email');
		$this->ci->load->library('session');
		
		$this->ci->load->helper('cookie');
	}

	/**
    *
    * Function to login
    * @author : Rabeesh
    * @param  : []
    * @return : type : [Array()]
    *
    **/
 	 function login($username, $password, $remember_me) {
		$data['username']=$username;
		$data['password']=$password;
		$status = $this->ci->users_model->login($data);
		
		if($status) {
			$this->ci->session->set_userdata('id', $status['id']);
			$this->ci->session->set_userdata('email', $status['email']);
			$this->ci->session->set_userdata('name', $status['name']);
			$this->ci->session->set_userdata('permissions', $status['permissions']);
			$this->ci->session->set_userdata('groups', $status['groups']);
			
			$this->ci->session->set_userdata('city_id', $status['city_id']);
			$this->ci->session->set_userdata('project_id', $status['project_id']);
			
			if($remember_me) {
				set_cookie('email', $status['email']);
				set_cookie('password_hash', md5($password, '2o^6uU!'));
			}
		}
		
		return $status;
	}
	
    /**
    * Function to logged_in
    * @author : Rabeesh
    * @param  : []
    * @return : type : [Boolean]
    *
    **/    
	function logged_in() {
		if ( $this->ci->session->userdata('id') ) {
			return $this->ci->session->userdata('id');
		
		} elseif(get_cookie('email') and get_cookie('password_hash')) {
			//This is a User who have enabled the 'Remember me' Option - so there is a cookie in the users system
			$email = get_cookie('email');
			$password_hash = get_cookie('password_hash');
			$user_details = $this->ci->users_model->db->query("SELECT id,name FROM User 
				WHERE email='$email' AND MD5(CONCAT(password,'2o^6uU!'))='$password_hash'")->row();
			
			if($row) return $row->id;
		}
		return false;
	}
	
	/**
    *
    * Function to getUser
    * @author : Rabeesh
    * @param  : []
    * @return : type : [Boolean]
    *
    **/
    function getUser() {
		$user_id = $this->logged_in();
		if($user_id) return $this->ci->user_model->get_user($user_id);
		return false;
	}
	
	/**
    * Function to logout
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function logout () {
		$this->ci->session->sess_destroy();
		
		delete_cookie('email');
		delete_cookie('password_hash');
		
		return $this->ci->session->unset_userdata('id');
	}
	
	/// Check to see if the user has permission to do the given activity. Redirect to the no-permissions page if he don't.
	function check_permission($permission_name) {
		if($this->get_permission($permission_name)) return true;
		
		redirect('auth/no_permission');
	}
	
	/// Returns true if the current user has permission to do the action specified in the argument
	function get_permission($permission_name) {
		return true; // :DEBUG: :TEMP: 
		return in_array($permission_name, $this->ci->session->userdata('permissions'));
	}
	/**
    * Function to register
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function register($data)
	{
		$status = $this->ci->users_model->user_registration($data);
		
			 if($status)
                {
					$this->ci->session->set_userdata('id', $status['id']);
					$this->ci->session->set_userdata('email', $status['email']);
					$this->ci->session->set_userdata('name', $status['name']);
					$this->ci->session->set_userdata('permissions', $status['permissions']);
					$this->ci->session->set_userdata('groups', $status['groups']);	
					return $status;
	        	}
             return false;
		
	}
	/**
    * Function to forgotten_password
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	public function forgotten_password($identity)    
	{
		if ( $this->ci->ion_auth_model->forgotten_password($identity) )   //changed
		{
			// Get user information
			$user = $this->get_user_by_identity($identity);  //changed to get_user_by_identity from email
			$data = array(
				'identity'		=> $user->{$this->ci->config->item('identity', 'ion_auth')},
				'forgotten_password_code' => $user->forgotten_password_code
			);

			$message = $this->ci->load->view($this->ci->config->item('email_templates', 'ion_auth').$this->ci->config->item('email_forgot_password', 'ion_auth'), $data, true);
			$this->ci->email->clear();
			$config['mailtype'] = $this->ci->config->item('email_type', 'ion_auth');
			$this->ci->email->initialize($config);
			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from($this->ci->config->item('admin_email', 'ion_auth'), $this->ci->config->item('site_title', 'ion_auth'));
			$this->ci->email->to($user->email);
			$this->ci->email->subject($this->ci->config->item('site_title', 'ion_auth') . ' - Forgotten Password Verification');
			$this->ci->email->message($message);

			if ($this->ci->email->send())
			{
				$this->set_message('forgot_password_successful');
				return TRUE;
			}
			else
			{
				$this->set_error('forgot_password_unsuccessful');
				return FALSE;
			}
		}
		else
		{
			$this->set_error('forgot_password_unsuccessful');
			return FALSE;
		}
	}
	/**
    * Function to get_user_by_identity
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	public function get_user_by_identity($identity)
	{
		return $this->ci->ion_auth_model->get_user_by_identity($identity)->row();
	}
	/**
    * Function to set_error
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	public function set_error($error)
	{
		$this->errors[] = $error;

		return $error;
	}
	/**
    * Function to errors
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	public function errors()
	{
		$_output = '';
		foreach ($this->errors as $error)
		{
			$_output .= $this->error_start_delimiter . $this->ci->lang->line($error) . $this->error_end_delimiter;
		}

		return $_output;
	}
	/**
    * Function to forgotten_password_complete
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	public function forgotten_password_complete($code)
	{
		$identity = $this->ci->config->item('identity', 'ion_auth');
		

		$new_password = $this->ci->ion_auth_model->forgotten_password_complete($code);

		if ($new_password)
		{
			$data = array(
				'identity'     => $profile->{$identity},
				'new_password' => $new_password
			);

			$message = $this->ci->load->view($this->ci->config->item('email_templates', 'ion_auth').$this->ci->config->item('email_forgot_password_complete', 'ion_auth'), $data, true);

			$this->ci->email->clear();
			$config['mailtype'] = $this->ci->config->item('email_type', 'ion_auth');
			$this->ci->email->initialize($config);
			$this->ci->email->set_newline("\r\n");
			$this->ci->email->from($this->ci->config->item('admin_email', 'ion_auth'), $this->ci->config->item('site_title', 'ion_auth'));
			$this->ci->email->to($profile->email);
			$this->ci->email->subject($this->ci->config->item('site_title', 'ion_auth') . ' - New Password');
			$this->ci->email->message($message);

			if ($this->ci->email->send())
			{
				$this->set_message('password_change_successful');
				return TRUE;
			}
			else
			{
				$this->set_error('password_change_unsuccessful');
				return FALSE;
			}
		}

		$this->set_error('password_change_unsuccessful');
		return FALSE;
	}
	
}


