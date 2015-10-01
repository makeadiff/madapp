<?php

Class User_auth {
	protected $error_start_delimiter;
	protected $error_end_delimiter;
	protected $hash = '2o^6uU!';

	private $ci;
	function User_auth() {
		$this->ci = &get_instance();
		$this->ci->load->model('users_model');
		$this->ci->load->config('ion_auth', TRUE);
		$this->ci->lang->load('ion_auth');
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
 	 function login($username, $password, $remember_me=false) {
		$data['username']=$username;
		$data['password']=$password;
		$status = $this->ci->users_model->login($data);

		if($status) {
			$this->ci->session->set_userdata('id', $status['id']);
			$this->ci->session->set_userdata('email', $status['email']);
			$this->ci->session->set_userdata('name', $status['name']);
			$this->ci->session->set_userdata('permissions', $status['permissions']);
			$this->ci->session->set_userdata('groups', $status['groups']);
			$this->ci->session->set_userdata('positions', $status['positions']);
			
			$this->ci->session->set_userdata('city_id', $status['city_id']);
			$this->ci->session->set_userdata('project_id', $status['project_id']);
			$this->ci->session->set_userdata('year', get_year()); // Current year. Change every year. Should be get_year()

			$_SESSION['user_id'] = $status['id'];
			
			if($remember_me) {
				setcookie('email', $status['email'], time() + (3600 * 24 * 30), '/'); // Expires in a month.
				setcookie('password_hash', md5($password . $this->hash), time() + (3600 * 24 * 30), '/');
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

		} elseif(!empty($_SESSION['user_id'])) {
			$user_data = $this->ci->users_model->db->query("SELECT email,password FROM User WHERE id=".$_SESSION['user_id'])->row();
			$status = $this->login($user_data->email, $user_data->password);

			return $status['id'];

		} elseif(get_cookie('email') and get_cookie('password_hash')) {
			//This is a User who have enabled the 'Remember me' Option - so there is a cookie in the users system
			$email = get_cookie('email');
			$password_hash = get_cookie('password_hash');

			$user_details = $this->ci->users_model->db->query("SELECT email,password FROM User WHERE email='$email' AND MD5(CONCAT(password,'{$this->hash}'))='$password_hash'")->row();

			if($user_details) {
				$status = $this->login($user_details->email, $user_details->password);
				return $status['id'];
			}
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
		delete_cookie('email');
		delete_cookie('password_hash');

		unset($_SESSION['user_id']);
		return $this->ci->session->unset_userdata('id');
	}
	
	/// Check to see if the user has permission to do the given activity. Redirect to the no-permissions page if he don't.
	function check_permission($permission_name) {
		if($this->get_permission($permission_name)) return true;
		
		redirect('auth/no_permission');
	}
	
	/// Returns true if the current user has permission to do the action specified in the argument
	function get_permission($permission_name) {
		if($this->ci->session->userdata('id') == 1) return true; //:UGLY:
		
		return in_array($permission_name, $this->ci->session->userdata('permissions'));
	}
	/**
    * Function to register
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function register($data) {
		list($status, $message) = $this->ci->users_model->user_registration($data);

		if($status) {
			$this->ci->load->model('settings_model');
			
			// Returns the email id of the HR person of the given city.
			$hr_email = $this->ci->settings_model->get_setting_value('hr_email_city_common'); // For diff city, use 'hr_email_city_'.$status['city_id']
			
			$new_registration_welcome_message = $this->ci->settings_model->get_setting_value('new_registration_welcome_message'); /// Returns the template of the email that should be sent to new recruites when they register on the site.
			$new_registration_notification = $this->ci->settings_model->get_setting_value('new_registration_notification'); /// Returns the template of the email that should be sent to the HR when someone registers
			
			$replace_these = array('%NAME%', '%FIRST_NAME%', '%CITY_HR_EMAIL%');
			$with_these = array($status['name'], short_name($status['name']), $hr_email);
			$new_registration_notification = str_replace($replace_these, $with_these, $new_registration_notification);
			$new_registration_welcome_message = str_replace($replace_these, $with_these, $new_registration_welcome_message);
			
			// Send Email to the newbie
			$this->ci->email->from($hr_email, "Make A Difference");
			$this->ci->email->to($status['email']);
			$this->ci->email->subject('Make A Difference - Registration Details');
			$this->ci->email->message($new_registration_welcome_message);
			$this->ci->email->send();
			//echo $this->ci->email->print_debugger();
		
			// Send email to HR
			$this->ci->email->clear();
			$this->ci->email->from($status['email'], $status['name']);
			$this->ci->email->to($hr_email);
			$this->ci->email->subject('Make A Difference - New Registration');
			$this->ci->email->message($new_registration_notification);
			$this->ci->email->send();
			//echo $this->ci->email->print_debugger();
			
		}
		return array($status, $message);
		
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
		$this->ci->load->model('users_model');
		$users = $this->ci->users_model->search_users(array('email'=>$identity, 'city_id'=>0));
		
		if($users) {
			$user = reset($users);
			$password_message = <<<END
Hey {$user->name},

MADApp password reminder...
Username: {$user->email}
Password: {$user->password}
Login At: http://makeadiff.in/madapp/

Thanks.
--
MADApp
END;

			// $this->ci->email->from('madapp@makeadiff.in', "MADApp");
			// $this->ci->email->to($user->email);
			// $this->ci->email->subject('MADApp Password Reminder');
			// $this->ci->email->message($password_message);
			// $this->ci->email->send();
			sendEmailWithAttachment($user->email, 'MADApp Password Reminder', $password_message, "MADApp <madapp@makeadiff.in>");

			return true;
		}
		return false;
	}
}
