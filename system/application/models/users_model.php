<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @license		http://orisysindia.com/licence/brilliant.html
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */

class Users_model extends Model
{
	
    function Users_model()
    {
        parent::Model();
    }
    /**
    * Function to Login
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function login($data)
    {	
		$memberCredentials;
      	$username= $data['username'];
        $password = $data['password'];
		$this->db->select('*');
		$this->db->from('user');
        $this->db->where('email', $username);
		$this->db->where('password',$password);
        
        $query = $this->db->get();
		//print_r($query->result());
        if($query->num_rows() > 0)
            {
			$memberR = $query->first_row();
   			$memberCredentials['id'] = $memberR->id;
			$memberCredentials['email'] = $memberR->email;
			$memberCredentials['name'] = $memberR->name;
            return $memberCredentials;
            }
		else
           {
           return false;
           }
        }
}