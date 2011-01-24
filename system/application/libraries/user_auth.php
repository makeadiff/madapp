<?php
Class User_auth {
	private $ci;

	function User_auth() {
		$this->ci = &get_instance();
		$this->ci->load->model('users_model');
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
			
			if($remember_me) {
				$this->ci->session->set_userdata('password_hash', md5($password, '2o^6uU!'));
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
		
		} elseif($this->ci->session->userdata('email') and $this->ci->session->userdata('password_hash')) {
			//This is a User who have enabled the 'Remember me' Option - so there is a cookie in the users system
			$email = $this->ci->session->userdata('email');
			$password_hash = $this->ci->session->userdata('password_hash');
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
		return $this->ci->session->unset_userdata('id');
	}
	
	/// Check to see if the user has permission to do the given activity. Redirect to the no-permissions page if he don't.
	function check_permission($permission_name) {
		if(in_array($permission_name, $this->ci->session->userdata('permissions'))) {
			return true;
		}
		
		redirect('auth/no_permission');
	}
}


