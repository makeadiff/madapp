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
class Common extends Controller {
   	function Common() {
        parent::Controller();
		$this->load->library('session');
		$this->load->library('navigation');
        $this->load->library('user_auth');
		$this->load->library('validation');
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->model('center_model');
		$this->load->model('project_model');
		$this->load->model('users_model');
		$this->load->model('city_model');
	}
	/**
	Function to register
    * @author : rabeesh
    * @param  : []
    * @return : type : []
    **/
    function register()
    {
		if(Navigation::isPost()){

			$error['city']='0';
			$data['firstname'] = $_POST['firstname'];
			$data['email'] = $_POST['email'];
			$data['mobileno'] = $_POST['mobileno'];
			$city = $_POST['city'];
			if($city== '-1')
			{
				$error['city']='1';
			}
			else
			{
				$data['city']=$city;
			}
			
			
			//set Rules..........
			$rules['firstname']	= "required|alpha_numeric";
			$rules['email']	= "required|valid_email";
			$rules['mobileno'] = "trim|required|min_length[8]|max_length[12]|callback__validate_phone_number";
			$this->validation->set_rules($rules);
			$fields['firstname'] = "Firstname";
			$fields['email']	= "Email";
			$fields['mobileno']	= "mobileno";
			$fields['city']	= "City";

			$this->validation->set_fields($fields);
			if ($this->validation->run() == FALSE) {	
				$data['message']= "";
				$error['details']= $this->center_model->getcity();
				$this->load->view('user/register_view',$error);
				
			} else {
				$status = $this->user_auth->register($data);
				if($status)	{
					redirect('common/thank_you');
				
				} else{
					$data['message']= "Registration Failed";
					$data['details']= $this->center_model->getcity();
					$this->load->view('user/register_view',$data);
				}
			}
          }
        else {
			$data['message']= "";
			$data['details']= $this->center_model->getcity();
			$this->load->view('user/register_view',$data);
		}
    }

	/// Handle the responses sent as the reply to the confirmation text here.
	function sms_response() {
		$data = print_r($_REQUEST, 1);
		$this->db->query("UPDATE Setting SET data='".mysql_real_escape_string($data)."' WHERE name='temp'");
	}

	function show() {
		print "<pre>";
		print $this->db->query("SELECT data FROM Setting WHERE name='temp'")->row()->data;
		print "</pre>";
	}
}
