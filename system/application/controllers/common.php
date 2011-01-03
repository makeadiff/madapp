<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package         MadApp
 * @author          Rabeesh
 * @copyright       Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @license         http://orisysindia.com/licence/brilliant.html
 * @link            http://orisysindia.com
 * @since           Version 1.0
 * @filesource
 */
class Common extends Controller
{
    /**
    *  constructor 
    **/

    function Common()
    {
        parent::Controller();
		$this->load->library('session');
		$this->load->library('navigation');
        $this->load->library('user_auth');
		$this->load->helper('url');
        $this->load->helper('form');
	}
		
	
	/**
    *
    * Function to logout
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/  
	function logout()
	{
		$this->session->sess_destroy();
		redirect ('admin/login');
	}
	/**
    *
    * Function to student_login
    * @author :Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
    function login()
    {	
     if(Navigation::isPost())
     		{
            
           $username = $_POST['username'];
           $password = $_POST['password'];
           $status = $this->user_auth->login($username,$password);
		   $Id= $status['id'];
           	if($Id != 0 )
               {
               		redirect('admin/dashboard');
			   }
                
				$error['error']='invalid username or password';
                $this->load->view('admin/includes/login_header');
                $this->load->view('admin/login_view',$error);
                $this->load->view('admin/includes/login_register_footer');
    	 }
     else 
        {
                $error['error']='';
                $this->load->view('admin/includes/login_header');
                $this->load->view('admin/login_view',$error);
                $this->load->view('admin/includes/login_register_footer');

         }
	  }
   
	/**
    * Function to student_logout
    * @author :Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function student_logout()
	{
		$this->session->sess_destroy();
		$this->load->view('student/includes/login_header');
        $this->load->view('student/login_view',$error);
        $this->load->view('student/includes/login_register_footer');
	}

}
