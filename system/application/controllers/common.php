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
					$error['center']='0';
                    $data['firstname'] = $_POST['firstname'];
                    $data['password'] = $_POST['password'];
                    $data['repassword'] = $_POST['repassword'];
                    $data['email'] = $_POST['email'];
					$data['position'] = $_POST['position'];	
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
					
					$center= $_POST['center'];
					if($center== '-1')
					{
						$error['center']='1';
					}
					else
					{
						$data['center']=$center;
					}
					
					//set Rules..........
                    $rules['firstname']	= "required|alpha_numeric";
                    $rules['position']	= "required|alpha_numeric";
                    $rules['password']  = "required";
                    $rules['repassword'] = "required|matches[password]";
                    $rules['email']	= "required|valid_email";
                    $rules['mobileno'] = "trim|required|min_length[8]|max_length[12]|callback__validate_phone_number";
                    $rules['center'] = "required";
					$rules['city'] = "required";

                    $this->validation->set_rules($rules);

                    $fields['firstname'] = "Firstname";
                    $fields['position']	= "Position";
                    $fields['email']	= "Email";
                    $fields['mobileno']	= "mobileno";
					$fields['center']	= "center";
					$fields['city']	= "City";

                    $this->validation->set_fields($fields);
                    if ($this->validation->run() == FALSE)
                            {	
									$error['details']= $this->center_model->getcity();
									$this->load->view('user/register_view',$error);
                            }
                            else
                            {
								$status = $this->user_auth->register($data);
								//print_r($status->result());
								if($status)		
		 					 		{
										redirect('dashboard/dashboard_view');
										
		 						 	}
                            }
          }
        else {
				$data['details']= $this->center_model->getcity();
				$this->load->view('user/register_view',$data);
        	}
    }
	function getcenter_name()
	{
		$data['centers']= $this->center_model->getcenter();
		$this->load->view('user/center_update_div',$data);
		
	}
	
	
	
	
}
