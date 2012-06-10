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
    function register($user_id_encoded='')
    {
		if(Navigation::isPost()){
			$data = $_POST;

			//Set Rules..........
			$rules['name']	= "required";
			$rules['email']	= "required|valid_email";
			$rules['phone'] = "trim|required|min_length[8]|max_length[12]|callback__validate_phone_number";
			$this->validation->set_rules($rules);
			$fields['name'] 	= "Name";
			$fields['email']	= "Email";
			$fields['phone']	= "Phone";
			$fields['city_id']	= "City";

			$this->validation->set_fields($fields);
			if ($this->validation->run() == FALSE) {
				$data['cities'] = $this->city_model->get_unique_cities();
				$this->load->view('user/register_view');
				
			} else {
				$status = $this->user_auth->register($data);
				if($status)	{
					redirect('common/thank_you');
				
				} else {
					$data['cities'] = $this->city_model->get_unique_cities();
					$this->load->view('user/register_view',$data);
				}
			}
          }
        else {
			if($user_id_encoded) {
				$user_id_text = base64_decode($user_id_encoded);
				$user_id = reset(explode(":", $user_id_text));
				
				$user_data = $this->users_model->get_user($user_id); //:HACK:
				if($user_data) {
					$data['user_id'] = $user_id;
					$this->validation = $user_data;
				}
			}
			$data['cities'] = $this->city_model->get_unique_cities();
			$this->load->view('user/register_view',$data);
		}
    }
    
    function thank_you() {
		$this->load->view('common/thank_you');
    }

	/// Handle the responses sent as the reply to the confirmation text here.
	function sms_response() {
		$this->load->model('class_model');
		$this->load->library('sms');
		$this->load->helper('misc_helper');
		
		$log = '';
		
		$phone = preg_replace('/^91/', '', $_REQUEST['msisdn']); // Gupshup uses a 91 at the start. Remove that.
		$time = $_REQUEST['timestamp'];
		$keyword = strtolower($_REQUEST['keyword']);
		$content = $_REQUEST['content'];
		$log .= "From $phone at $time:";

		// Find the user with who sent the SMS - using the phone number.
		$user = reset($this->users_model->search_users(array('phone'=>$phone,'city_id'=>0)));
		if(!$user) {
			$log .= "User Not Found!";
			$this->db->query("UPDATE Setting SET data='".mysql_real_escape_string($log)."' WHERE name='temp'");
			log_message('error', $log);
			return;
		}
		
		// Find the unconfirmed class closest to today by the person who sent the text.
		$closest_unconfirmed_class = $this->class_model->get_closest_unconfirmed_class($user->id);
		
		$this->class_model->confirm_class($closest_unconfirmed_class, $user->id); // ... and confirm it.
		
		$log .= " User {$user->id}, Class $closest_unconfirmed_class. ";
		
		// Then sent a thank you sms to that user.
		$name = short_name($user->name);
		$this->sms->send($phone, "Thank you for confirming your class. All the best, $name :-)");
		
		$log .= " Sent a thank you SMS to $name.";
		
		log_message('info', $log);
		
 		$this->db->query("UPDATE Setting SET data='".mysql_real_escape_string($log)."' WHERE name='temp'");
	}
	

	/// SMS Registerations.
	function sms_register() {
		$this->load->library('sms');
		$this->load->helper('misc_helper');
		$this->load->model('settings_model');
		
		$log = '';
		
		$phone = preg_replace('/^91/', '', $_REQUEST['msisdn']); // Gupshup uses a 91 at the start. Remove that.
		$time = $_REQUEST['timestamp'];
		$keyword = strtolower($_REQUEST['keyword']);
		$content = $_REQUEST['content'];
		$log .= "From $phone at $time:\n";
		
		list($full_name, $email, $city) = explode(",", str_replace('IMAD ','', $content));
		$name = short_name($full_name);
		$city = strtolower(trim($city));
		$name = short_name(trim($full_name));
		$email = trim($email);
		
		
		$log .= "$city:$full_name:$name:$email\n";

		// Find the user with who sent the SMS - using the phone number.
		$user = $this->city_model->db->query("SELECT id,name,phone,email FROM User WHERE phone='$phone' OR email='$email'")->row();
		if($user) {
			// User exists in the database. Can't add.
			$this->sms->send($phone, "$name, you are already in the MAD Database. Thanks for your interest.");
			$log .= "User exists in Database.";
			
		} else {
			// Then sent a thank you sms to that user.
			$this->sms->send($phone, "Dear $name, thank you for registering with Make A Difference. Check your email for more details.");
			
			// Find which city the user is from...
			// First, use some presets...
			if($city == 'hyd') $city = 'hyderabad';
			elseif($city == 'blore') $city = 'bangalore';
			elseif($city == 'mlore') $city = 'mangalore';
			elseif($city == 'tvm') $city = 'trivandrum';
			elseif($city == 'calcutta') $city = 'kolkata';
			elseif($city == 'cmb') $city = 'coimbatore';
			elseif($city == 'ekm') $city = 'cochin';
			elseif($city == 'poona') $city = 'pune';
			
			// Now find the city with least text distance from the given text
			$cities = $this->city_model->get_unique_cities();
			$most_likely = 0;
			$most_likely_difference = 100;
			foreach($cities as $city_id=>$city_name) {
				$difference = levenshtein(strtolower($city_name), $city);
				if(!$difference) {
					$most_likely = $city_id;
					$most_likely_difference = 0;
					break;
				}
				
				if($most_likely_difference > $difference) {
					$most_likely_difference = $difference;
					$most_likely = $city_id;
				}
			}
			if($most_likely_difference > 4) $most_likely = 0;
			
			$log .= "Given City : $city. Most likely means: ".$cities[$most_likely]."($most_likely) with $most_likely_difference difference.\n";
			$log .= "Sent a thank you SMS to $name.";
			
			// Add the user to the database.
			$user_array = array(
				'name'		=> $full_name,
				'email'		=> $email,
				'phone'		=> $phone,
				'password'	=> 'pass',
				'city_id'	=> $most_likely,
				'city_other'=> $city,
				'project_id'=> 1,
				'user_type' => 'applicant',
				'joined_on'	=> date('Y-m-d'),
			);
			$this->users_model->db->insert('User',$user_array);
			$user_id = $this->users_model->db->insert_id();
			
			$link = site_url('common/register/'.base64_encode($user_id . ":;-)"));
			
			// Send email to the user...
			$email_body = $this->settings_model->get_setting_value('sms_registration_email');
			$email_body = str_replace(array('%NAME%', '%LINK%'),array($name, $link), $email_body);
			
			$hr_email = $this->ci->settings_model->get_setting_value('hr_email_city_common'); // For diff city, use 'hr_email_city_'.$status['city_id']
			$this->ci->email->from($hr_email, "Make A Difference");
			$this->ci->email->to($email);
			$this->ci->email->subject('Thanks for Registering with Make A Difference');
			$this->ci->email->message($email_body);
			$this->ci->email->send();
			
		}
		
		log_message('info', $log);
		
 		$this->db->query("UPDATE Setting SET data='".mysql_real_escape_string($log)."' WHERE name='temp'");
 		// localhost/Projects/Madapp/CI/trunk/index.php/common/sms_register?msisdn=91974608565&timestamp=1339356546&keyword=IMAD&content=IMAD+Binny+V+A,binnyva@gmail.com,Cochin
	}
	

	function show() {
		print "<pre>";
		print $this->db->query("SELECT data FROM Setting WHERE name='temp'")->row()->data;
		print "</pre>";
	}
	
	function test($text) {
		$this->load->library('sms');
		$this->sms->send('9746068565', $text);
	}
}
