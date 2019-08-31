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
 	 function login($username, $password, $remember_me=false, $auth_token='') {
		$data['username'] = $username;
		$data['password'] = $password;
		$data['auth_token']=$auth_token;
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
				setcookie('email', $status['email'], time() + (10 * 365 * 24 * 60 * 60), '/'); // Expires in 10 years
				// setcookie('password_hash', md5($password . $this->hash), time() + (10 * 365 * 24 * 60 * 60), '/');
				$token = $this->ci->users_model->setAuthToken($status['id']);
				setcookie('auth_token', $token, time() + (10 * 365 * 24 * 60 * 60), '/');
			}
		}

		return $status;
	}

	function accessControl() {
		if(empty($_SESSION['user_id']) and empty($_SESSION['id'])) {
			$current_link = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? "https" : "http") . "://{$_SERVER['HTTP_HOST']}{$_SERVER['REQUEST_URI']}";
			$login_url = MAD_APPS_FOLDER . 'auth/';

			header("Location: " . $login_url . "?url=" . base64_encode($current_link));
			exit;
		}

		if(empty($_SESSION['id'])) $_SESSION['id'] = $_SESSION['user_id']; // Backward compatability.

		if(!$this->ci->session->userdata('permissions')) {
			$user_details = $this->ci->users_model->db->query("SELECT email,password,auth_token FROM User WHERE id=$_SESSION[user_id]")->row();

			if($user_details) {
				$status = $this->login($user_details->email, $user_details->password, false, $user_details->auth_token);
			}
		}

		return $_SESSION['user_id'];
	}

    /**
     * This function will make sure that the user gets logged in automatically. And there are 3 different ways - userdata(), $_SESSION and cookie.
     */
	function logged_in() {
		if ( $this->ci->session->userdata('id') ) {
			return $this->ci->session->userdata('id');

		} elseif( $this->ci->session->userdata('user_id') ) {
			// This was an older methord of authentication, not necessay after moving to Auth
			// $user_data = $this->ci->users_model->db->query("SELECT email,password,city_id,auth_token FROM User WHERE id=".$_SESSION['user_id'])->row();
			// $user_details = $this->login($user_data->email, '', false, $user_data->auth_token);
			// return $user_details['id'];

			return $this->ci->session->userdata('user_id');

		} elseif(get_cookie('email') and get_cookie('auth_token')) {
			//This is a User who have enabled the 'Remember me' Option - so there is a cookie in the users system
			$email = get_cookie('email');
			$auth_token = get_cookie('auth_token');

			$user_details = $this->ci->users_model->db->query("SELECT email,password,city_id FROM User WHERE email='$email' AND auth_token='$auth_token'")->row();

			if($user_details) {
				$status = $this->login($user_details->email, $user_details->password, true, $auth_token);
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
		$info = "Logout from Madapp::User_Auth->logout() at " . date("Y-m-d H:i:s") . "\nBefore...\nSession : ". json_encode($_SESSION) . "\nCookie : " . json_encode($_COOKIE) . "\n";
		delete_cookie('email');
		delete_cookie('password_hash');

		unset($_SESSION['user_id']);
		// file_put_contents(__DIR__ . '../../../apps/Auth/system/Logout.log', $info, FILE_APPEND | LOCK_EX); // :DEBUG:
		return $this->ci->session->unset_userdata('id');
	}

	/// Check to see if the user has permission to do the given activity. Redirect to the no-permissions page if he don't.
	function check_permission($permission_name) {
		if($this->get_permission($permission_name)) return true;

		redirect('auth/no_permission');
	}

	/// Returns true if the current user has permission to do the action specified in the argument
	function get_permission($permission_name) {
		if($this->ci->session->userdata('user_id') == 1) return true; //:UGLY:

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

	public function find_reset_code($code) {
		// See if there is a existing change request.
		$existing_code = $this->ci->users_model->db->query("SELECT U.id, U.email, U.phone,UD.data AS code FROM UserData UD
													INNER JOIN User U ON U.id=UD.user_id
													WHERE UD.value=1 AND UD.name='password_reset_code' AND UD.data='$code' AND U.status='1' AND U.user_type='volunteer'")->row();

		return $existing_code;
	}

	public function disable_reset_code($code) {
		$this->ci->users_model->db->query("DELETE FROM UserData WHERE data='$code'");
	}

	/**
    * Function to forgotten_password
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	public function send_password_reset_link($identity)
	{
		$this->ci->load->model('users_model');
		$user = $this->ci->users_model->db->query("SELECT id,name,email,mad_email,phone FROM User
													WHERE (email='$identity' OR mad_email='$identity' OR phone='$identity') AND status='1' AND user_type='volunteer'")->row();

		if($user) {
			// See if there is a existing change request.
			$existing_code = $this->ci->users_model->db->query("SELECT id,data,value FROM UserData
													WHERE user_id=$user->id AND value=1 AND name='password_reset_code'")->row();
			if(!$existing_code) {
				$code = md5(uniqid() . time());
				$this->ci->users_model->db->insert('UserData', [
					'user_id' 	=> $user->id,
					'name'		=> 'password_reset_code',
					'value'		=> 1,
					'data'		=> $code,
				]);
			} else {
				$code = $existing_code->data;
			}

			$password_message = <<<END
Hey {$user->name},

Someone, hopefully you, has requested to change your MADApp Password. If you wish to do that, go to this URL and set the new password...
http://makeadiff.in/madapp/index.php/auth/reset_password/{$code}

If you did not make this request, just ignore this email.

Thanks.
--
MADApp
END;

			// $this->ci->email->from('madapp@makeadiff.in', "MADApp");
			// $this->ci->email->to($user->email);
			// $this->ci->email->subject('MADApp Password Reminder');
			// $this->ci->email->message($password_message);
			// $this->ci->email->send();
			sendEmailWithAttachment($identity, 'MADApp Password Reset', $password_message, "MADApp <madapp@makeadiff.in>");

			return true;
		}
		return false;
	}
}
