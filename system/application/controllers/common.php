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
	* Function to register
    * @author : rabeesh
    * @param  : []
    * @return : type : []
    **/
    function register($user_id_encoded='')
    {
		if(Navigation::isPost()){
			$data = $_POST;
			$data['phone'] = $_POST['phone'] = preg_replace('/[^+\d]/','', $_POST['phone']);
			$data['cities'] = $this->city_model->get_unique_cities();
			
			//Set Rules..........
			$rules['name']	= "required";
			$rules['email']	= "required|valid_email";
			$rules['phone'] = "trim|required|min_length[8]|max_length[12]";
			$rules['city_id']="required";
			$this->validation->set_rules($rules);
			$fields['name'] 	= "Name";
			$fields['email']	= "Email";
			$fields['phone']	= "Phone";
			$fields['city_id']	= "City";

			$this->validation->set_fields($fields);
			if ($this->validation->run() == FALSE) {
				$this->load->view('user/register_view', $data);
				
			} else {

				require('system/vendor/autoload.php');
				$secret = '6Le_7hsTAAAAAG8-PiI757qs4LQEzQ28TZOgL-vJ';
				$recaptcha = new \ReCaptcha\ReCaptcha($secret);
				$gRecaptchaResponse = $_REQUEST['g-recaptcha-response'];
				$remoteIp = $_SERVER['REMOTE_ADDR'];

				$resp = $recaptcha->verify($gRecaptchaResponse, $remoteIp);
				if ($resp->isSuccess()) {
				    // verified!
				} else {
					print "Your application has triggered our spam detection algorithm. Click on the back button then make sure you have the \"I'm not a robot\" checkbox ticked.";
					return false; // Spam.
				}

				// Check for spam...
				if((stripos($data['address'], '<a ') !== false) or (stripos($data['address'], 'http://') !== false) or (stripos($data['address'], 'https://') !== false)) {					// There is a link in the address field. Spam.
						// Make sure this is spam. There are some people with malware infection who automatially inject links into any form they fill.
						if(
							(stripos($data['email'], '@gmail.com') !== false)		// People with gmail email ids tend to be real.
							// and (preg_match('/^[0987]/', $data['phone']))		// Indian Phone number(Well, first number is accurate.)
							and (strpos($data['name'], ' ') !== false)
							) {
						// Not spam
						$data['address'] = trim(strip_tags($data['address']));
						$data['why_mad'] = trim(strip_tags($data['why_mad']));

					} else { // Is spam.
						print "Your application has triggered our spam detection algorithm. Make sure you don't have links/URLs in your application.";
						return false;
					}
				}

				list($status, $message) = $this->user_auth->register($data);
				if($status)	{
					redirect('common/thank_you');
				
				} else {
					$data['error'] = $message;
					
					$this->load->view('user/register_view',$data);
				}
			}

        } else {
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
			$this->load->view('user/register_view', $data);
		}
    }
    
    function thank_you() {
		$this->load->model('settings_model');
		$registerations = $this->users_model->db->query("SELECT COUNT(id) AS count FROM User WHERE user_type='applicant'")->row();
		$reg_count = $registerations->count;
		
		$this->load->view('common/thank_you',array('reg_count'=>$reg_count));
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
		$this->load->library('email');
		$this->load->helper('misc_helper');
		$this->load->model('settings_model');
		$debug = false;
		
		if(!$debug) error_reporting(0);
		
		$log = '';
		
		$phone = preg_replace('/^91/', '', $_REQUEST['msisdn']); // Gupshup uses a 91 at the start. Remove that.
		$time = $_REQUEST['timestamp'];
		$keyword = strtolower($_REQUEST['keyword']);
		$content = $_REQUEST['content'];
		$log .= "From $phone at $time:\n";
		if($debug) print "From $phone at $time:<br />";
		
		list($full_name, $email, $city) = explode(",", str_ireplace('IMAD ','', $content));
		$name = short_name($full_name);
		$city = strtolower(trim($city));
		$name = short_name(trim($full_name));
		$email = trim($email);
		
		
		$log .= "$city:$full_name:$name:$email\n";
		if($debug) print "$city:$full_name:$name:$email<br />";

		// Find the user with who sent the SMS - using the phone number.
		$user = $this->city_model->db->query("SELECT id,name,phone,email,city_id,status,user_type FROM User WHERE phone='$phone' OR email='$email'")->row();
		if($user) {
			if($user->user_type != 'volunteer') {
				// User exists in the database. Can't add.
				$this->city_model->db->where('id', $user->id)->update('User', array('joined_on'=>date('Y-m-d H:i:s'), 'user_type'=>'applicant'));

				if(!$debug) $this->sms->send($phone, "$name, you are already in our Database. Your application is bumped up. You'll be informed when there is a recruitment in your city. Thank you.");
			} else {
				if(!$debug) $this->sms->send($phone, "$name, you are already a volunteer according to our database. If this is a mistake, please send an email to contact@makeadiff.in");
			}

			$log .= "User exists in Database.\n";
			$log .= print_r($user, 1);
			if($debug) print "User Exists...<br />" . print_r($user, 1);
			
		} else {
			// Then sent a thank you sms to that user.
			if(!$debug) $this->sms->send($phone, "Dear $name, thank you for registering with Make A Difference. Check your email for more details.");
			
			// Find which city the user is from...
			// First, use some presets...
			if($city == 'hyd') $city = 'hyderabad';
			elseif($city == 'blore') $city = 'bangalore';
			elseif($city == 'mlore') $city = 'mangalore';
			elseif($city == 'tvm') $city = 'trivandrum';
			elseif($city == 'calcutta') $city = 'kolkata';
			elseif($city == 'cmb') $city = 'coimbatore';
			elseif($city == 'cbe') $city = 'coimbatore';
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
				'name'				=> $full_name,
				'email'				=> $email,
				'phone'				=> $phone,
				'password'			=> 'pass',
				'city_id'			=> $most_likely,
				'city_other'		=> $city,
				'project_id'		=> 1,
				'user_type' 		=> 'applicant',
				'source'			=> 'sms',
				'source_other'		=>'sms',
				'dream_tee'			=> '0',
				'english_teacher'	=> '1',
				'placements'		=> '0',
				'events'			=> '0',
				'joined_on'			=> date('Y-m-d H:i:s'),
			);
			if(!$debug) $this->users_model->db->insert('User',$user_array);
			$user_id = $this->users_model->db->insert_id();
			if($debug) print $user_id."<br />";
			$link = site_url('common/register/'.base64_encode($user_id . ":;-)"));
			if($debug) print $link."<br />";
			
			// Send email to the user...
			$email_body = $this->settings_model->get_setting_value('sms_registration_email');
			$email_body = str_replace(array('%NAME%', '%LINK%'),array($name, $link), $email_body);
			if($debug) print $email_body."<br />";
			
			$hr_email = $this->settings_model->get_setting_value('hr_email_city_common'); // For diff city, use 'hr_email_city_'.$status['city_id']
			if($debug) print $hr_email."<br />";
			$this->email->from($hr_email, "Make A Difference");
			$this->email->to($email);
			$this->email->subject('Thanks for Registering with Make A Difference');
			$this->email->message($email_body);
			$this->email->send();
			if($debug) echo $this->email->print_debugger();
			
		}
		
		log_message('info', $log);
		
 		$this->db->query("UPDATE Setting SET data='".mysql_real_escape_string($log)."' WHERE name='temp'");
 		// localhost/Projects/Madapp/CI/trunk/index.php/common/sms_register?msisdn=919746068565&timestamp=1339356546&keyword=IMAD&content=IMAD+Binny+V+A,binnyva@gmail.com,Cochin
	}
	

	function show($setting='temp') {
		print "<pre>";
		print $this->db->query("SELECT data FROM Setting WHERE name='$setting'")->row()->data;
		print "</pre>";
	}
	
	function test($text) {
		$this->load->library('sms');
		$this->sms->send('9746068565', $text);
	}
	
	
	function runners() {
		$cities = array('Lucknow','Mumbai','Ahmedabad','Dehradun','Pune','Gawlior','Delhi','Kolkata','Nagpur','Chandigarh','Bhopal');

		$count = 0;
		foreach($cities as $city_name) {
			$city = strtolower($city_name);
			$user_id = $this->users_model->adduser(array(
				'name' 		=> "$city_name President",
				'email'		=> "president.$city@makeadiff.in",
				'phone'		=> '97460685'.$count,
				'address'	=> '',
				'sex'		=> 'f',
				'password'	=> 'pass',
				'city'		=> '0',
				'project'	=> '1',
				'type' 		=> 'volunteer',
			));
			if(!$user_id) continue;
			
			$city_id  = $this->city_model->createCity(array('name'=>$city_name,'president_id'=>$user_id));
			$this->users_model->adduser_to_group($user_id, array(2));
			
			$roles = array(
				array('name' => 'EPH',	'group_id' => 4,	'email' => 'englishproject'),
				array('name' => 'HR',	'group_id' => 4,	'email' => 'hr'),
				array('name' => 'Ops',	'group_id' => 5,	'email' => 'operations'),
				);
			foreach($roles as $role) {
				$user_id = $this->users_model->adduser(array(
					'name' 		=> $role['name'],
					'email'		=> "{$role['email']}.$city@makeadiff.in",
					'phone'		=> '97460685'. $count,
					'password'	=> 'pass',
					'address'	=> '',
					'sex'		=> 'f',
					'city'		=> $city_id,
					'project'	=> '1',
					'type'		 => 'volunteer',
				));
				if($user_id) $this->users_model->adduser_to_group($user_id, array($role['group_id']));
				
				$count++;
			}
		}
	}
}
