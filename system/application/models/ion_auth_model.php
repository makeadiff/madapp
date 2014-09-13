<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
if(!class_exists('CI_Model')) { class CI_Model extends Model {} }


class Ion_auth_model extends CI_Model
{
	/**
	 * Holds an array of tables used
	 *
	 * @var string
	 **/
	public $tables = array();

	/**
	 * activation code
	 *
	 * @var string
	 **/
	public $activation_code;

	/**
	 * forgotten password key
	 *
	 * @var string
	 **/
	public $forgotten_password_code;

	/**
	 * new password
	 *
	 * @var string
	 **/
	public $new_password;

	/**
	 * Identity
	 *
	 * @var string
	 **/
	public $identity;

	public function __construct()
	{
		parent::__construct();
		$this->load->database();
		$this->load->config('ion_auth', TRUE);
		$this->load->helper('cookie');
		$this->load->helper('date');
		$this->load->library('session');

		$this->tables  = $this->config->item('tables', 'ion_auth');
		$this->columns = $this->config->item('columns', 'ion_auth');

		$this->identity_column = $this->config->item('identity', 'ion_auth');
		$this->store_salt      = $this->config->item('store_salt', 'ion_auth');
		$this->salt_length     = $this->config->item('salt_length', 'ion_auth');
		$this->meta_join       = $this->config->item('join', 'ion_auth');
	}

	
/**
	 * Identity check
	 *
	 * @return bool
	 * @author Mathew
	 **/
	protected function identity_check($identity = '')
	{
	    if (empty($identity))
	    {
		return FALSE;
	    }

	    return $this->db->where($this->identity_column, $identity)
			->count_all_results($this->tables['user']) > 0;
	}
	/**
	 * login
	 *
	 * @return bool
	 **/
	public function login($identity, $password, $remember=FALSE)
	{
	    if (empty($identity) || empty($password) || !$this->identity_check($identity))
	    {
		return FALSE;
	    }
	    $query = $this->db->select($this->identity_column.', id, password,name')
			      ->where($this->identity_column, $identity)
			      ->where($this->ion_auth->_extra_where)
			      ->limit(1)
			      ->get($this->tables['user']);
	    $result = $query->row();
	    if ($query->num_rows() == 1)
	    {
		if ($result->password === $password)
		{
		    $this->update_last_login($result->id);
		    $session_data = array(
					$this->identity_column => $result->{$this->identity_column},
					'id'                   => $result->id, //kept for backwards compatibility
					'user_id'              => $result->id, //everyone likes to overwrite id so we'll use user_id
					'name'                 => $result->name,
					 );

		    $this->session->set_userdata($session_data);

		    if ($remember && $this->config->item('remember_users', 'ion_auth'))
		    {
			$this->remember_user($result->id);
		    }

		    return TRUE;
		}
	    }

	    return FALSE;
	}

	/**
	 * get_user
	 *
	 * @return object
	 **/
	public function get_user($id = false)
	{
	    //if no id was passed use the current users id
	    if (empty($id))
	    {
		$id = $this->session->userdata('user_id');
	    }
		$this->db->select('*');
		$this->db->from('User');
		$this->db->where('id',$id);
		$result=$this->db->get();
		return $result;
	}

	/**
	 * get_user_by_email
	 *
	 * @return object
	 **/
	public function get_user_by_email($email)
	{
	    $this->db->limit(1);

	    return $this->get_users_by_email();
	}

	/**
	 * update_last_login
	 *
	 * @return bool
	 **/
	public function update_last_login($id)
	{
	    $this->load->helper('date');

	    if (isset($this->ion_auth->_extra_where))
	    {
		$this->db->where($this->ion_auth->_extra_where);
	    }

	    $this->db->update($this->tables['user'], array('last_login' => now()), array('id' => $id));

	    return $this->db->affected_rows() == 1;
	}


	/**
	 * set_lang
	 *
	 * @return bool
	 **/
	public function set_lang($lang = 'en')
	{
	    set_cookie(array(
			'name'   => 'lang_code',
			'value'  => $lang,
			'expire' => $this->config->item('user_expire', 'ion_auth') + time()
			    ));

	    return TRUE;
	}

	/**
	 * login_remembed_user
	 *
	 * @return bool
	 **/
	public function login_remembered_user()
	{
	    //check for valid data
	    if (!get_cookie('identity') || !get_cookie('remember_code') || !$this->identity_check(get_cookie('identity')))
	    {
		    return FALSE;
	    }

	    //get the user
	    if (isset($this->ion_auth->_extra_where))
	    {
		$this->db->where($this->ion_auth->_extra_where);
	    }

	    $query = $this->db->select($this->identity_column.', id')
			      ->where($this->identity_column, get_cookie('identity'))
			      ->where('remember_code', get_cookie('remember_code'))
			      ->limit(1)
			      ->get($this->tables['user']);

	    //if the user was found, sign them in
	    if ($query->num_rows() == 1)
	    {
		$user = $query->row();

		$this->update_last_login($user->id);


		$session_data = array(
				    $this->identity_column => $user->{$this->identity_column},
				    'id'                   => $user->id, //kept for backwards compatibility
				    'user_id'              => $user->id, //everyone likes to overwrite id so we'll use user_id
				    //'name'                 => $user->name,
				     );

		$this->session->set_userdata($session_data);


		//extend the users cookies if the option is enabled
		if ($this->config->item('user_extend_on_login', 'ion_auth'))
		{
		    $this->remember_user($user->id);
		}

		return TRUE;
	    }

	    return FALSE;
	}

	/**
	 * remember_user
	 *
	 * @return bool
	 **/
	private function remember_user($id)
	{
	    if (!$id)
	    {
		return FALSE;
	    }
	    $user = $this->get_user($id)->row();
	    $salt = sha1($user->password);
	    $this->db->update($this->tables['user'], array('remember_code' => $salt), array('id' => $id));

	    if ($this->db->affected_rows() > -1)
	    {
		set_cookie(array(
			    'name'   => 'identity',
			    'value'  => $user->{$this->identity_column},
			    'expire' => $this->config->item('user_expire', 'ion_auth'),
				));

		set_cookie(array(
			    'name'   => 'remember_code',
			    'value'  => $salt,
			    'expire' => $this->config->item('user_expire', 'ion_auth'),
				));

		return TRUE;
	    }
	    return FALSE;
	}
	/**
    * Function to forgotten_password
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	public function forgotten_password($email = '')
	{
	    if (empty($email))
	    {
		return FALSE;
	    }

	    $key = rand('11111','99999');

	    $this->forgotten_password_code = $key;

	    $this->db->where('email',$email);

	    $this->db->update('user', array('forgotten_password_code' => $key), array('email' => $email));

	    return $this->db->affected_rows() == 1;
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
	    
		return $this->db->where('email',$identity)->get('user');
		
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
	    if (empty($code))
	    {
		return FALSE;
	    }

	    $this->db->where('forgotten_password_code', $code);

	    if ($this->db->count_all_results('user') > 0)
	    {
		$password = rand('111111','999999');

		$data = array(
			    'password'			=> $password ,
			    'forgotten_password_code'   => '0',
			    //'active'			=> 1,
			     );

		$this->db->update('users', $data, array('forgotten_password_code' => $code));

		return $password;
	    }

	    return FALSE;
	}
	
}
