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
			if ($this->validation->run() == FALSE)
					{	
							$error['details']= $this->center_model->getcity();
							$this->load->view('user/register_view',$error);
					}
					else
					{
						$status = $this->user_auth->register($data);
						if($status)		
							{
								
									
								redirect('dashboard/dashboard_view');
								/*$new_recruit_mail= $this->users_model->get_new_recruit_mail();
								$new_recruit_mail=$new_recruit_mail->data;
								$hr_email= $this->users_model->get_hr_email($city_id);
								$hr_email=$hr_email->value;
								$new_registration_notification= $this->users_model->get_new_registration_notification();
								$new_registration_notification=$new_registration_notification->data;
									//mail function
									
									$this->email->from('madapp','Maddapp');
									$this->email->to($email);
									$this->email->subject('Thanks');
									$this->email->message($new_recruit_mail);
									$this->email->send(); 
											$this->email->from('madapp','Maddapp');
											$this->email->to($hr_email);
											$this->email->subject('Notification');
											$this->email->message($new_registration_notification);
											$this->email->send(); */
								
								
								
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
