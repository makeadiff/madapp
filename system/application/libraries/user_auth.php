<?php

Class User_auth {

    protected $error_start_delimiter;
    protected $error_end_delimiter;
    private $ci;

    function User_auth() {
        $this->ci = &get_instance();
        $this->ci->load->model('users_model');
        $this->ci->load->config('ion_auth', TRUE);
        $this->ci->lang->load('ion_auth');
        $this->ci->load->model('ion_auth_model');
        $this->ci->load->library('email');
        $this->ci->load->library('session');
        $this->ci->load->helper('cookie');
    }

    /*
     * Function Name : login()
     * Wroking :This function used login
     * @author:Rabeesh
     * @param :[$username, $password, $remember_me=false]
     * @return: type: [array]
     */

    function login($username, $password, $remember_me=false) {
        $data['username'] = $username;
        $data['password'] = $password;
        $status = $this->ci->users_model->login($data);

        if ($status) {
            $this->ci->session->set_userdata('id', $status['id']);
            $this->ci->session->set_userdata('email', $status['email']);
            $this->ci->session->set_userdata('name', $status['name']);
            $this->ci->session->set_userdata('permissions', $status['permissions']);
            $this->ci->session->set_userdata('groups', $status['groups']);

            $this->ci->session->set_userdata('city_id', $status['city_id']);
            $this->ci->session->set_userdata('project_id', $status['project_id']);
            $this->ci->session->set_userdata('year', '2012');

            if ($remember_me) {
                setcookie('email', $status['email'], time() + 3600 * 24 * 30, '/'); // Expires in a month.
                setcookie('password_hash', md5($password . '2o^6uU!'), time() + 3600 * 24 * 30, '/');
            }
        }

        return $status;
    }

    /*
     * Function Name : logged_in()
     * Wroking :This function used check if the user is login in.
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */

    function logged_in() {
        if ($this->ci->session->userdata('id')) {
            return $this->ci->session->userdata('id');
        } elseif (get_cookie('email') and get_cookie('password_hash')) {
            //This is a User who have enabled the 'Remember me' Option - so there is a cookie in the users system
            $email = get_cookie('email');
            $password_hash = get_cookie('password_hash');
            $user_details = $this->ci->users_model->db->query("SELECT email,password FROM User 
				WHERE email='$email' AND MD5(CONCAT(password,'2o^6uU!'))='$password_hash'")->row();

            if ($user_details) {
                $status = $this->login($user_details->email, $user_details->password);
                return $status['id'];
            }
        }
        return false;
    }

    /*
     * Function Name : getUser()
     * Wroking :This function used check if the user is login in.
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */
    function getUser() {
        $user_id = $this->logged_in();
        if ($user_id)
            return $this->ci->user_model->get_user($user_id);
        return false;
    }

    /*
     * Function Name : logout()
     * Wroking :Logout the current session.
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */
    function logout() {
        delete_cookie('email');
        delete_cookie('password_hash');

        return $this->ci->session->unset_userdata('id');
    }
/*
     * Function Name : logout()
     * Wroking :Check to see if the user has permission to do the given activity. Redirect to the no-permissions page if he don't.
     * @author:
     * @param :[]
     * @return: type: [array]
     */

    function check_permission($permission_name) {
        if ($this->get_permission($permission_name))
            return true;

        redirect('auth/no_permission');
    }
/*
     * Function Name : logout()
     * Wroking :Returns true if the current user has permission to do the action specified in the argument
     * @author:
     * @param :[]
     * @return: type: [array]
     */

    function get_permission($permission_name) {
        if ($this->ci->session->userdata('id') == 1)
            return true; //:UGLY:

        return in_array($permission_name, $this->ci->session->userdata('permissions'));
    }

    /*
     * Function Name : register()
     * Wroking :Register the user.
     * @author:
     * @param :[$data]
     * @return: type: [boolean]
     */
    function register($data) {
        $status = $this->ci->users_model->user_registration($data);

        if ($status) {
            $this->ci->load->model('settings_model');

            // Returns the email id of the HR person of the given city.
            $hr_email = $this->ci->settings_model->get_setting_value('hr_email_city_common'); // For diff city, use 'hr_email_city_'.$status['city_id']

            $new_registration_welcome_message = $this->ci->settings_model->get_setting_value('new_registration_welcome_message'); /// Returns the template of the email that should be sent to new recruites when they register on the site.
            $new_registration_notification = $this->ci->settings_model->get_setting_value('new_registration_notification'); /// Returns the template of the email that should be sent to the HR when someone registers

            $replace_these = array('%NAME%', '%CITY_HR_EMAIL%');
            $with_these = array($status['name'], $hr_email);
            $new_registration_notification = str_replace($replace_these, $with_these, $new_registration_notification);
            $new_registration_welcome_message = str_replace($replace_these, $with_these, $new_registration_welcome_message);

            // Send Email to the newbie
            $this->ci->email->from($hr_email, "Make A Difference");
            $this->ci->email->to($status['email']);
            $this->ci->email->subject('Make A Difference - Registration Details');
            $this->ci->email->message($new_registration_welcome_message);
            $this->ci->email->send();
            //echo $this->ci->email->print_debugger();
            // Send email to HR
            $this->ci->email->clear();
            $this->ci->email->from($status['email'], $status['name']);
            $this->ci->email->to($hr_email);
            $this->ci->email->subject('Make A Difference - New Registration');
            $this->ci->email->message($new_registration_notification);
            $this->ci->email->send();
            //echo $this->ci->email->print_debugger();

            return $status;
        }
        return false;
    }

    /*
     * Function Name : forgotten_password()
     * Wroking :
     * @author:
     * @param :[$identity]
     * @return: type: [boolean]
     */
    public function forgotten_password($identity) {
        $this->ci->load->model('users_model');
        $users = $this->ci->users_model->search_users(array('email' => $identity, 'city_id' => 0));

        if ($users) {
            $user = reset($users);
            $password_message = <<<END
Hey {$user->name},

MADApp password reminder...
Username: {$user->email}
Password: {$user->password}
Login At: http://makeadiff.in/madapp/

Thanks.
--
MADApp
END;

			$this->ci->email->from('madapp@makeadiff.in', "MADApp");
			  $this->ci->email->to($user->email);
			$this->ci->email->subject('MADApp Password Reminder');
			$this->ci->email->message($password_message);
			$this->ci->email->send();
			
			return true;
		}
		return false;
	}
}


