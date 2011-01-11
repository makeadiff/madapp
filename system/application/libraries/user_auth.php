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
 	 function login($username, $password) {
		$data['username']=$username;
		$data['password']=$password;
		print 'there';
		$status = $this->ci->users_model->login($data);
			 
		$this->ci->session->set_userdata('adminid', $status['id']);
		$this->ci->session->set_userdata('email', $status['email']);
		$this->ci->session->set_userdata('name', $status['name']);
		
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
		if ( $this->ci->session->userdata('adminid') ) {
			return $this->ci->session->userdata('adminid');
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
		if($user_id) {
			return $this->ci->user_auth_model->retrieve_by_pk($user_id);
		}
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
		return $this->ci->session->unset_userdata('adminid');
		$this->ci->session->sess_destroy();
	}
}


