<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */

class Users_model extends Model {

    function Users_model() {
        parent::Model();
        $this->ci = &get_instance();
        $this->city_id = $this->ci->session->userdata('city_id');
        $this->project_id = $this->ci->session->userdata('project_id');
        $this->year = $this->ci->session->userdata('year');
        if(!$this->year) $this->year = get_year();

        $this->load->model('Class_model','class_model');
    }

    /**
    * Function to login
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function login($data) {
      	$username= $data['username'];
        $password = $data['password'];
        $auth_token = '';
        if(isset($data['auth_token'])) $auth_token = $data['auth_token'];

		//Check for personal email and mad email when logging in
		$where = "(`email` = '$username' or `mad_email` = '$username' or `phone` = '$username')";

		$query = $this->db->where($where)->where('status','1')->where('user_type', 'volunteer')->get("User");
		$user = $query->first_row();

		$correct_password = false;

		if($password and $user)
			$correct_password = password_verify($password, $user->password_hash);
		if($auth_token) {
			$query = $this->db->where('id', $user->id)->where('auth_token', $auth_token)->get("User");
			$correct_password = $query->first_row();
		}

        if($user and $correct_password) {
   			$user_data['id']		= $user->id;
			$user_data['email']		= $user->email;
			$user_data['name']		= $user->name;
			$user_data['project_id']= $user->project_id;
			$user_data['city_id']	= $user->city_id;
			$user_data['credit']	= $user->credit;
			$user_data['permissions']= $this->get_user_permissions($user->id);
			$user_data['groups']	= $this->get_user_groups_of_user($user->id);
			$all_positions 			= $this->get_user_groups_of_user($user->id, 'type');

			$user_data['positions'] = array_unique(array_values($all_positions));

            return $user_data;

        } else {
           return false;
        }
    }

    // Part of the attempt to take away the login logic from UPMA
    function user_info($user_id) {
    	$query = $this->db->where('id', $user_id)->get("User");
		$user = $query->first_row();

        if($user) {
   			$user_data['id']		= $user->id;
			$user_data['email']		= $user->email;
			$user_data['name']		= $user->name;
			$user_data['project_id']= $user->project_id;
			$user_data['city_id']	= $user->city_id;
			$user_data['credit']	= $user->credit;
			$user_data['permissions']= $this->get_user_permissions($user->id);
			$user_data['groups']	= $this->get_user_groups_of_user($user->id);
			$all_positions 			= $this->get_user_groups_of_user($user->id, 'type');

			$user_data['positions'] = array_unique(array_values($all_positions));

            return $user_data;
        }

        return false;
    }



    function setAuthToken($user_id, $token = false) {
    	if(!$token) {
    		$length = 20;
    		// Create a random string.
    		$token = substr(str_shuffle(str_repeat($x='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ', ceil($length/strlen($x)) )),1,$length);
    	}
    	$this->db->where('id', $user_id)->update("User", ['auth_token' => $token]);

    	return $token;
    }

    public function setZohoId($user_id, $zoho_user_id)
    {
    	$this->db->where('id', $user_id)->update("User", ['zoho_user_id' => $zoho_user_id]);
    }

	/**
    * Function to getgroup_details
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getgroup_details($show_hirachy_groups = false)
	{
		$group_type = "";
		if(!$show_hirachy_groups) $group_type = " AND group_type='normal'";

		$data = $this->db->query("SELECT * FROM `Group` WHERE status='1' $group_type
			ORDER BY type='national' DESC,type='strat' DESC,type='fellow' DESC,type='volunteer' DESC, name");

		return $data;
	}
	function get_all_groups() {
		$this->db->from('Group')->where('status','1');

		// Hide the national level groups if the current user is a volunteer
		if(	!in_array('national', $this->session->userdata('positions'))
				and !in_array('strat', $this->session->userdata('positions'))) {
			$this->db->where("(`type`='fellow' OR `type`='volunteer')");
		}
		$this->db->where('group_type','normal');

		return $this->db->order_by('type','name')->get()->result();
	}

	/**
    * Function to add_group_name
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function add_group_name($groupname)
	{
		$data = array('name'=> $groupname);
		$this->db->insert('Group',$data);
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id(): false ;
	}
	/**
    * Function to add_group_permission
    * @author:Rabeesh
    * @param :[$data]
	* @return: type: [Boolean,]
    **/
	function add_group_permission($permission,$group_id)
	{
		$count=sizeof($permission);
		for($j=0;$j<$count;$j++)
			{
				$data = array('group_id'=> $group_id, 'permission_id'=>$permission[$j]);
				$this->db->set($data);
				$this->db->insert('GroupPermission');
			}
		return ($this->db->affected_rows() > 0) ? true : false;

	}
	/**
    * Function to edit_group
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function edit_group($user_id)
	{
		$this->db->select('*');
		$this->db->from('Group');
		$this->db->where('id',$user_id);
		$result=$this->db->get();
		return $result;
	}
	/**
    * Function to update_group
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function update_group($group_id, $group_name)
	{
		$data = array('name' => $group_name);
		$this->db->where('id', $group_id);
		$this->db->update('Group', $data);
	 	return ($this->db->affected_rows() > 0) ? true: false ;
	}
	/**
    * Function to update_permission
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function update_permission($group_id, $permission)
	{
		$this->db->where('group_id',$group_id);
		$this->db->delete('GroupPermission');

		$count=count($permission);
		for($j=0;$j<$count;$j++) {
			$data = array('group_id'=> $group_id, 'permission_id'=>$permission[$j]);
			$this->db->set($data);
			$this->db->insert('GroupPermission');
		}
		return ($this->db->affected_rows() > 0) ? true : false;

	}
	/**
    * Function to delete_group
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function delete_group($data)
	{
		$id = $data['entry_id'];
		$this->db->where('id',$id);
		$this->db->update('Group', array('status' => '0'));


		return ($this->db->affected_rows() > 0) ? true: false ;
	}

	/// Returns the groups the current user belongs to...
	function get_user_groups_of_user($user_id, $data='name') {
		$groups = $this->db->query("SELECT Group.id, Group.$data AS name FROM `Group`
				INNER JOIN UserGroup ON Group.id=UserGroup.group_id
				WHERE UserGroup.user_id=$user_id AND UserGroup.year='{$this->year}' AND `Group`.status='1'")->result();
		$all_groups = idNameFormat($groups);

		return $all_groups;
	}

	/**
    * Function to getuser_details
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function getuser_details($where=array())
	{
		$this->db->select('User.*,City.name as city_name');
		$this->db->from('User');
		$this->db->where('User.status','1');
		if(!empty($where['city_id'])) $this->db->where('User.city_id', $where['city_id']);
		else $this->db->where('User.city_id', $this->city_id);

		$this->db->join('City', 'City.id = User.city_id' ,'join');
		$this->db->orderby('User.name');

		$result = $this->db->get();

		return $result;
	}

	/**
    * Function to getuser_details
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function getuser_details_csv()
	{
		$this->db->select('User.id,User.name,User.email,User.phone,User.credit,User.title,User.user_type,Center.name as center_name, City.name as city_name');
		$this->db->from('User');
		$this->db->join('Center', 'Center.id = User.center_id' ,'join');
		$this->db->join('City', 'City.id = User.city_id' ,'join');
		$this->db->where('User.status','1');
		$result = $this->db->get();
		return $result;

	}
	/**
    * Function to adduser
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean, int]
    **/
	function adduser($data)
	{
		// Check if this user is already present in the DB
		$email=$data['email'];
		$this->db->select('email');
		$this->db->from('User');
		$this->db->where('email',$email);
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return false;
		}

		$password = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 10]);

		$user_array = array(
			'name'		=> $data['name'],
			'email'		=> $data['email'],
			'mad_email'	=> $data['mad_email'],
			'phone'		=> $this->_correct_phone_number($data['phone']),
			// 'password'	=> $data['password'],
			'password_hash' => $password,
			'address'	=> $data['address'],
			'sex'		=> $data['sex'],
			'city_id'	=> $data['city'],
			'project_id'=> 1,
			'user_type' => $data['type'],
		);
		if(!empty($data['joined_on'])) $user_array['joined_on'] = $data['joined_on'];
		else $user_array['joined_on'] = date('Y-m-d H:i:s');

		if(!empty($data['left_on'])) $user_array['left_on'] = $data['left_on'];

		$this->db->insert('User',$user_array);
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
	}

	function undelete($user_id) {
		return $this->db->where('id',$user_id)->update('User', array('status'=>1));
	}

	function process_pic($data, $type='users')
    {
      	$id=$data['id'];
        //Get File Data Info
        $uploads = array($this->upload->data());
        $this->load->library('image_lib');
        $this->load->library('imageResize');

        //Move Files To User Folder
        foreach($uploads as $key[] => $value)
        {
            $newimagename = $id.$value['file_ext'];
			$image_path = "uploads/$type/$newimagename";
			rename($value['full_path'], $image_path);

            $nwidth='100';
	        $nheight='90';
			$fileSavePath= dirname(BASEPATH). "/uploads/$type/thumbnails/$newimagename";
			imagejpeg(imageResize::Resize($image_path,$nwidth,$nheight),$fileSavePath);
            $imagename = $newimagename;
            $this->db->set('photo', $imagename);
			$this->db->where('id',$id);
            if($type=='users') $this->db->update('User');
			else $this->db->update('Student');

			return ($this->db->affected_rows() > 0) ? true: false ;
        }
 	}

	function check_email_availability($insert)
	{
		$email=$insert['email'];
		$this->db->select('email');
		$this->db->from('User');
		$this->db->where('email',$email);
		$result=$this->db->get();
		if($result->num_rows() > 0) return true;
		return false;
	}

	/// Add the user given as the first argument to all the groups specified in the second argument.
	function adduser_to_group($user_id, $group_ids) {
		$existing_groups = $this->get_user_groups_of_user($user_id);
		foreach($group_ids as $group_id) {
			if(isset($existing_groups[$group_id])) continue; // User already the given group already
			$user_array=array('user_id'=>$user_id, 'group_id'=> $group_id, 'year' => $this->year);
			$this->db->insert('UserGroup',$user_array);
		}
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
	}

	/// Removes the given user from the given group.
	function remove_user_from_group($user_id, $group_id) {
		$this->db->delete('UserGroup', array('user_id'=>$user_id, 'group_id'=>$group_id, 'year' => $this->year));
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	/**
    * Function to user_details
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function user_details($user_id) {
		if(!$user_id) $user_id = $this->ci->session->userdata('user_id');
		$this->db->from('User');
		$this->db->where('User.id',$user_id);//->where('User.status','1');

		$result = $this->db->get()->row();
		$result->groups = $this->get_user_groups_of_user($user_id, 'id');
		$result->groups_name= $this->get_user_groups_of_user($user_id, 'name');
		$result->batch = $this->db->query("SELECT Batch.day, Batch.class_time, Center.name
					FROM Batch INNER JOIN UserBatch ON UserBatch.batch_id=Batch.id
					INNER JOIN Center ON Batch.center_id=Center.id
					WHERE UserBatch.user_id={$user_id} AND year='{$this->year}'")->row();

		return $result;
	}


	function updateuser($data) {
		$user_id = $data['rootId'];

		if(!empty($data['name'])) 		$user_array['name'] 		= $data['name'];
		if(!empty($data['email'])) 		$user_array['email'] 		= $data['email'];
		if(isset($data['mad_email'])) 	$user_array['mad_email'] 	= $data['mad_email'];
		if(!empty($data['phone'])) 		$user_array['phone'] 		= $this->_correct_phone_number($data['phone']);
		if(!empty($data['address'])) 	$user_array['address'] 		= $data['address'];
		if(!empty($data['sex'])) 		$user_array['sex'] 			= $data['sex'];
		if(!empty($data['city'])) 		$user_array['city_id'] 		= $data['city'];
		elseif(!empty($data['city_id']))$user_array['city_id'] 		= $data['city_id'];
		if(!empty($data['project'])) 	$user_array['project_id'] 	= $data['project'];
		if(!empty($data['joined_on'])) 	$user_array['joined_on'] 	= $data['joined_on'];
		if(!empty($data['left_on'])) 	$user_array['left_on'] 		= $data['left_on'];
		if(isset($data['password'])) 	{
			$password = password_hash($data['password'], PASSWORD_BCRYPT, ['cost' => 10]);
			$user_array['password_hash'] = $password;
			$user_array['password'] = $data['password'];
		}
		if(!empty($data['reason_for_leaving'])) $user_array['reason_for_leaving'] = $data['reason_for_leaving'];

		if(!empty($data['type'])) {
			$user_array['user_type'] = $data['type'];

			if($data['type'] == 'volunteer') {
				$old_user_data = $this->get_user($data['rootId']);
				if($old_user_data->user_type == 'applicant') { // If the volunteer was just added to the volunteer list, set the joined on date.
					 $user_array['joined_on'] = date("Y-m-d H:i:s");
				}
			}

			if($user_array['user_type'] == 'let_go' || $user_array['user_type'] == 'alumni') { // Remove user from his classes when he is let go.
				if(!$user_array['left_on'] or $user_array['left_on'] == '0000-00-00') $user_array['left_on'] = date('Y-m-d');

				$this->db->delete('UserBatch', array('user_id'=>$user_id)); // :TODO: This will delete historic data
				$this->db->delete('UserClass', array('user_id'=>$user_id, 'status'=>'projected'));
				$this->db->delete('UserClass', array('user_id'=>$user_id, 'status'=>'confirmed'));
			}
		}

		$this->db->where('id', $user_id);
		$this->db->update('User', $user_array);

		return $this->db->affected_rows();
	}

	/// Save the subject for the given user.
	function set_user_subject($user_id, $subject_id) {
		$this->db->where('id', $user_id);
		$this->db->update('User', array('subject_id' => $subject_id));
	}


	function updateuser_to_group($data)
	{
		$rootId=$data['rootId'];
		$this->db->where('user_id',$rootId);
		$this->db->delete('UserGroup');
		$group=$data['group'];
		for($i=0;$i <sizeof($group);$i++)
		{
		 	$data['group']=$group[$i];
			$user_array=array('group_id'=> $data['group'],'user_id'=>$rootId, 'year' => $this->year);
			$this->db->insert('UserGroup', $user_array);
		}
		return ($this->db->affected_rows() > 0) ? true: false ;
	}

	function delete($user_id) {
		$this->db->where('id',$user_id)->update('User',array('status'=>'0'));
		$affected = $this->db->affected_rows();

		if($affected) {
			$this->db->delete('UserBatch',array('user_id'=>$user_id));
			return true;
		}
		return false;
	}

	function get_user($user_id) {
		return $this->db->where('id', $user_id)->get('User')->row();
	}

	// Used for unique check via API.
	function find_user($user_data) {
		if(isset($user_data['id']) and $user_data['id']) $this->db->where('id', $user_data['id']);
		if(isset($user_data['user_id']) and $user_data['user_id']) $this->db->where('id', $user_data['user_id']);
		if(isset($user_data['phone']) and $user_data['phone']) $this->db->where('phone', $user_data['phone']);
		if(isset($user_data['email']) and $user_data['email']) $this->db->where('email', $user_data['email']);

		$user = $this->db->get('User')->row();

		// Find city name for this volunteer.
		$city = $this->db->query("SELECT name FROM City WHERE id=".$user->city_id)->row();
		$user->city_name = $city->name;

		return $user;
	}

	function getUsersById() {
		$this->load->helper('misc');
		return getById("SELECT id, name FROM User WHERE city_id={$this->city_id} AND project_id={$this->project_id} AND user_type='volunteer' AND status='1'", $this->db);
	}

	function get_users_in_city($city_id=false) {
		if($city_id === false) $city_id = $this->city_id;
		return $this->db->where('city_id', $city_id)->where('user_type','volunteer')->where('status','1')->orderby('name')->get('User')->result();
	}

  function get_sofs_in_city($city_id=false){
    if($city_id === false) $city_id = $this->city_id;
		return $this->db->query("SELECT User.id as id, User.name as name FROM User INNER JOIN UserGroup ON UserGroup.user_id = User.id WHERE city_id={$this->city_id} AND UserGroup.group_id=269 AND UserGroup.year={$this->year} AND user_type='volunteer' AND status='1'")->result();
  }

	function set_user_batch_and_level($user_id, $batch_id, $level_id) {
    	$this->db->insert("UserBatch", array('user_id'=>$user_id, 'batch_id'=>$batch_id, 'level_id'=>$level_id));
    	return $this->db->insert_id();
    }

	function unset_user_batch_and_level($batch_id, $level_id) {
    	$this->db->delete("UserBatch", array('batch_id'=>$batch_id, 'level_id'=>$level_id));
    	return $this->db->affected_rows();
    }

    function update_credit($user_id, $change) {
    	if(strpos($change, '-') === false) $change = "+ $change";
    	$this->db->query("UPDATE User SET credit=credit $change WHERE id=$user_id");
    }
    function set_credit($user_id, $credit) {
		$this->db->query("UPDATE User SET credit=$credit WHERE id=$user_id");
    }
    function manually_edit_credit($user_id, $credit, $comment, $credit_assigned_by_user_id) {
    	// Set a row to make sure the edited credit is used in history calculation.
    	$this->db->insert("UserCredit", array(
    							'user_id'	=> $user_id,
    							'credit'	=> $credit,
    							'credit_assigned_by_user_id' => $credit_assigned_by_user_id,
    							'comment'	=> $comment,
    							'added_on'	=> date('Y-m-d H:i:s'),
    							'year'		=> $this->year
				    		));

    	// Set the credit.
    	$this->set_credit($user_id, $credit);
    }

    function recalculate_user_credit($user_id, $update_if_wrong=false, $debug=false) {
		$this->ci->load->model('level_model');
		$this->ci->load->model('event_model');
		$this->ci->load->model('settings_model');

		$debug = true;

		$users_groups = $this->get_user_groups_of_user($user_id);
		$ed_teacher_group_id = 9;
		$fp_teacher_group_id = 376;
		$ac_wingman_group_id = 365;
		$tr_wingman_group_id = 348;
		$tr_asv_group_id = 349;

		$vertical_prefix = 'ed_';
		if(isset($users_groups[$fp_teacher_group_id])) $vertical_prefix = 'fp_';
		elseif(isset($users_groups[$ac_wingman_group_id])) $vertical_prefix = 'ac_';
		elseif(isset($users_groups[$tr_wingman_group_id])) $vertical_prefix = 'tr_wingman_';
		elseif(isset($users_groups[$tr_asv_group_id])) $vertical_prefix = 'tr_asv_';

		// Get seperate credit values depending on vertical.
		$credit_for_substituting = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'credit_for_substituting'));
		$credit_lost_for_getting_substitute = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'credit_lost_for_getting_substitute'));
		$credit_lost_for_missing_class = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'credit_lost_for_missing_class'));
		$credit_lost_for_missing_zero_hour = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'credit_lost_for_missing_zero_hour'));
		$credit_max_credit_threshold = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'max_credit_threshold'));
		$credit = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'beginning_credit'));
		$classes_so_far = $this->get_usercredits($user_id);

		$start_date = get_year() . '-04-01';
		$end_date = (get_year() + 1) . '-03-31';

		// Find the sunday after the start_date
		$current_date = date('Y-m-d', strtotime('next sunday', strtotime($start_date)));
		$this->db->query("DELETE FROM User_Credit_Archive WHERE user_id=$user_id AND credit_on>'{$this->year}-04-01 00:00:00'"); // Clear existing credit archive. We are going to re-insert it.

		// Find all the instances where this user's credit was manually edited.
		$credit_edits = $this->db->query("SELECT added_on, credit, comment FROM UserCredit
											WHERE user_id=$user_id AND year={$this->year} ORDER BY added_on")->result_array();

		foreach($classes_so_far as $row) {
			// If there is a manual credit edit entry just before this class, make sure that is factored in.
			if($credit_edits) {
				$edit = reset($credit_edits); // Get the first edit,
				if($edit['added_on'] < $row['class_on']) {
					$credit = $edit['credit'];
					if($debug) print "Credit was manually edited to $credit : " . $edit['comment'] . "\n";
					array_shift($credit_edits); // Remove the done edit.
				}
			}

			if ($row['user_id'] == $user_id and $row['substitute_id'] == 0 and $row['status'] == 'absent') {
				$credit = $credit + $credit_lost_for_missing_class;
				if($debug) print "User missed class: $credit_lost_for_missing_class<br />\n";

			} else if ($row['user_id'] == $user_id and $row['substitute_id'] != 0 and  ($row['status'] == 'absent' or $row['status'] == 'attended')) {
				$credit = $credit + $credit_lost_for_getting_substitute;
				if($debug) print "Had to get a substitute: $credit_lost_for_getting_substitute<br />\n";

			} else if($row['substitute_id'] == $user_id and $row['status'] == 'absent') {
				$credit = $credit + $credit_lost_for_missing_class;
				if($debug) print "Missed a substitution class: $credit_lost_for_missing_class<br />\n";

			} elseif ($row['substitute_id'] == $user_id and $row['status'] == 'attended') {
				$credit_sub_gets = $credit_for_substituting;

				if($credit_max_credit_threshold >= ($credit + $credit_sub_gets)) {
					$credit = $credit + $credit_sub_gets;
					if($debug) print "Credit for subbing: $credit_sub_gets<br />\n";
				} else {
					$credit = $credit_max_credit_threshold;
					if($debug) print "Credit for subbing not got - as upper limit is hit.<br />\n";
				}

				if(!$row['zero_hour_attendance']) { // Sub didn't reach in time for zero hour. Loses a credit.
					$credit = $credit + $credit_lost_for_missing_zero_hour;
					if($debug) print "Missed Zero Hour: $credit_lost_for_missing_zero_hour<br />\n";
				}
			} elseif($row['substitute_id'] == '0' and $row['status'] == 'attended') {
				if(!$row['zero_hour_attendance']) { // Sub didn't reach in time for zero hour. Loses a credit.
					$credit = $credit + $credit_lost_for_missing_zero_hour;
					if($debug) print "Missed Zero Hour: $credit_lost_for_missing_zero_hour<br />\n";
				}
			}

			// Get all sundays from last date to this class date.
			$class_date = date('Y-m-d', strtotime($row['class_on']));
			while($current_date < $class_date) {
				$current_date = date('Y-m-d', strtotime("next sunday",strtotime($current_date)));
				// print "$current_date - $class_date : $credit\n";

				// Save user's credit for that week to the DB.
				$this->db->insert("User_Credit_Archive", array(
					'user_id'	=> $user_id,
					'credit'	=> $credit,
					'credit_on'	=> $current_date,
				));
			}
		}

		// If there is a manual credit edit entry just before this class, make sure that is factored in.
		if($credit_edits) {
			$edit = end($credit_edits); // Get the last edit - since this is after all the classes, we'll just get the final one.
			$credit = $edit['credit'];
			if($debug) print "Credit was manually edited to $credit : " . $edit['comment'] . "\n";
		}

		if($update_if_wrong) {
			$user = $this->get_user($user_id);

			$existing_credits = $user->credit;
			if($debug) print "\t\t\t\tActual Credit: $credit\t\tExisting: $existing_credits";
			if($existing_credits != $credit) {
				if($debug) print "\t\tWRONG!";
				$this->set_credit($user_id, $credit);
			}
		}

		return $credit;
    }


    function get_credit_history($user_id) {
		$this->load->model('level_model');
		$this->load->model('event_model');
		$this->load->model('settings_model');

    	$details = $this->get_usercredits($user_id);
 		$users_groups = $this->get_user_groups_of_user($user_id);
		$ed_teacher_group_id = 9;
		$fp_teacher_group_id = 376;
		$ac_wingman_group_id = 365;
		$tr_wingman_group_id = 348;
		$tr_asv_group_id = 349;

		$vertical_prefix = 'ed_'; // Empty is Ed Support
		if(isset($users_groups[$fp_teacher_group_id])) $vertical_prefix = 'fp_';
		elseif(isset($users_groups[$ac_wingman_group_id])) $vertical_prefix = 'ac_';
		elseif(isset($users_groups[$tr_wingman_group_id])) $vertical_prefix = 'tr_wingman_';
		elseif(isset($users_groups[$tr_asv_group_id])) $vertical_prefix = 'tr_asv_';

		// Get seperate credit values depending on vertical.
		$credit_for_substituting = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'credit_for_substituting'));
		$credit_lost_for_getting_substitute = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'credit_lost_for_getting_substitute'));
		$credit_lost_for_missing_class = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'credit_lost_for_missing_class'));
		$credit_lost_for_missing_zero_hour = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'credit_lost_for_missing_zero_hour'));
		$credit_max_credit_threshold = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'max_credit_threshold'));
		$credit = floatval($this->ci->settings_model->get_setting_value($vertical_prefix . 'beginning_credit'));

		$i = 0;
		$credit_log = array(array(
			'class_on' 		=> get_year() . '-04-01 00:00:00',
			'action' 		=> 'Start of year',
			'change'		=> 'Started with '.$credit.' credits',
			'credit'		=> $credit,
			'i'				=> $i,
		));

		// Find all the instances where this user's credit was manually edited.
		$credit_edits = $this->db->query("SELECT added_on, credit, comment FROM UserCredit
											WHERE user_id=$user_id AND year={$this->year} ORDER BY added_on")->result_array();

		foreach($details as $row) {
			$data = array();

			// If there is a manual credit edit entry just before this class, make sure that is factored in.
			if($credit_edits) {
				$edit = reset($credit_edits); // Get the first edit,
				if($edit['added_on'] < $row['class_on']) {
					$credit = $edit['credit'];
					$data['class_on'] = $edit['added_on'];
					$data['change'] = "Credit was manually edited to $credit : " . $edit['comment'];
					$data['action'] = 'Manual Edit';
					$data['credit'] = $credit;

					array_shift($credit_edits); // Remove the done edit.

					$i++;
					$data['i'] = $i;
					$credit_log[] = $data;
					$data = array();
					// dump($credit_edits);
				}
			}
			if ($row['user_id'] == $user_id and $row['substitute_id'] == 0 and $row['status'] == 'absent') {
				$credit = $credit + $credit_lost_for_missing_class;
				$data['class_on'] = $row['class_on'];
				$data['action'] = 'Absent';
				$data['change'] = "Lost $credit_lost_for_missing_class credits";
				$data['credit'] = $credit;

			} else if ($row['user_id'] == $user_id and $row['substitute_id'] != 0 and ($row['status'] == 'absent' or $row['status'] == 'attended')) {
				$substitute_id = $row['substitute_id'];
				$name_of_substitute = $this->get_name_of_substitute($substitute_id);
				if(sizeof($name_of_substitute) >0) $name_of_substitute = $name_of_substitute->name;
				else $name_of_substitute ='No Name';

				$credit = $credit + $credit_lost_for_getting_substitute;
				$data['class_on']= $row['class_on'];
				$data['action']="Substituted by ".$name_of_substitute." ";
				$data['change'] = "Lost $credit_lost_for_getting_substitute credit";
				$data['credit'] = $credit;

			} else if($row['substitute_id'] == $user_id and $row['status'] == 'absent') {
				$credit = $credit + $credit_lost_for_missing_class;
				$data['class_on']= $row['class_on'];
				$teacher_name = $this->get_name_of_substitute($row['user_id']);
				$data['action'] = "Absent for " . $teacher_name->name . "'s substitute class";
				$data['change'] = "Lost $credit_lost_for_missing_class credit";
				$data['credit'] = $credit;

			} elseif ($row['substitute_id'] == $user_id and $row['status'] == 'attended') {
				$sub_get_credits = $credit_for_substituting;

				$data['class_on'] = $row['class_on'];
				$teacher_name = $this->get_name_of_Substitute($row['user_id']);
				$data['action'] = "Substituted for " . $teacher_name->name;
				$data['change'] = "Gained $sub_get_credits credit.";

				// Did we hit the upper limit?
				if($credit_max_credit_threshold >= ($credit + $sub_get_credits)) {
					$credit = $credit + $sub_get_credits;
				} else {
					$credit = $credit_max_credit_threshold;
					$data['change'] .= " Upper credit limit hit! You Rock!";
				}

				$data['credit'] = $credit;

				if(!$row['zero_hour_attendance']) { // Sub didn't reach in time for zero hour. Loses a credit.
					$i++;
					$data['i'] = $i;
					$credit_log[] = $data;
					$data = array();

					$credit = $credit + $credit_lost_for_missing_zero_hour;
					$data['class_on'] = $row['class_on'];
					$data['action'] = "Missed Zero Hour";
					$data['change'] = "Lost $credit_lost_for_missing_zero_hour credit";
					$data['credit'] = $credit;
				}
			}

			if ($row['substitute_id'] == 0 and $row['status'] == 'attended') {
				if(!$row['zero_hour_attendance']) { // Teacher didn't reach in time for zero hour. Loses a credit.
					$credit = $credit + $credit_lost_for_missing_zero_hour;
					$data['class_on'] = $row['class_on'];
					$data['action'] = "Missed Zero Hour";
					$data['change'] = "Lost $credit_lost_for_missing_zero_hour credit";
					$data['credit'] = $credit;
				}
			}

			if(isset($data['credit'])) {
				$i++;
				$data['i'] = $i;
				$credit_log[] = $data;
			}
		}

		// If there is a manual credit edit entry just before this class, make sure that is factored in.
		if($credit_edits) {
			$i++;
			$edit = end($credit_edits); // Get the last edit - since this is after all the classes, we'll just get the final one.
			$data = array(
				'i' 		=> $i,
				'class_on'	=> $edit['added_on'],
				'action'	=> 'Manual Credit Edit',
				'change'	=> "Credit was manually edited to $edit[credit] : " . $edit['comment'],
				'credit'	=> $edit['credit']
			);

			array_shift($credit_edits); // Remove the done edit.
			$credit_log[] = $data;
		}

		return $credit_log;
    }

    function get_user_class_history($user_id) {
    	$all_classes = $this->class_model->get_all($user_id);
    	$status_counts = array(
    			'attended'	=> 0,
    			'absent'	=> 0,
    			'projected'	=> 0,
    			'cancelled'	=> 0,
    		);
    	foreach ($all_classes as $cls) {
    		$status_counts[$cls->status]++;
    	}

    	return array('all_classes' => $all_classes, 'status_counts' => $status_counts);
    }

    /// Returns all the class connections of this user - all the classes they are a teacher at and all they are a mentor at.
	function get_class_connections($user_id) {
		$mentor_at = $this->db->query("SELECT DISTINCT B.id AS batch_id, B.day, B.class_time, C.class_on, Ctr.id AS center_id, Ctr.name AS center_name
			FROM Batch B
			INNER JOIN Class C ON C.batch_id=B.id
			INNER JOIN Center Ctr ON B.center_id=Ctr.id
			WHERE B.status='1' AND B.batch_head_id='$user_id' AND B.year='{$this->year}'
				AND C.class_on=(SELECT class_on FROM Class WHERE batch_id=B.id AND class_on < NOW() ORDER BY class_on DESC LIMIT 0,1)")->result();

		$teacher_at = $this->db->query("SELECT B.id AS batch_id, UB.level_id, CONCAT(L.grade, ' ', L.name) AS level, B.day, B.class_time, C.id AS class_id, C.class_on,
												Ctr.id AS center_id, Ctr.name AS center_name
			FROM UserBatch UB
			INNER JOIN Batch B ON B.id=UB.batch_id
			INNER JOIN Level L ON UB.level_id=L.id
			INNER JOIN Class C ON (C.batch_id=UB.batch_id AND C.level_id=UB.level_id)
			INNER JOIN UserClass UC ON UC.class_id=C.id
			INNER JOIN Center Ctr ON B.center_id=Ctr.id
			WHERE B.status='1' AND UB.user_id='$user_id' AND UC.user_id=$user_id AND B.year='{$this->year}' AND L.year={$this->year} AND C.class_on < NOW()
				AND C.class_on=(SELECT class_on FROM Class WHERE batch_id=B.id AND level_id=L.id AND class_on < NOW()
									ORDER BY class_on
									DESC LIMIT 0,1)")->result();

		// Find and put the batch name(Sunday, 4 PM) using the day and class time.
		$weekdays = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Error');
		for($i=0; $i<count($mentor_at);$i++) {
			$mentor_at[$i]->batch_name = $weekdays[$mentor_at[$i]->day] . ', ' . date('g A', strtotime('2016-07-28 ' . $mentor_at[$i]->class_time));
		}
		for($i=0; $i<count($teacher_at);$i++) {
			$teacher_at[$i]->batch_name = $weekdays[$teacher_at[$i]->day] . ', ' . date('g A', strtotime('2016-07-28 ' . $teacher_at[$i]->class_time));
		}

		return array('teacher_at' => $teacher_at, 'mentor_at' => $mentor_at);
	}


    /// Given a user id, get the batch they are mentoring. If there such a batch.
    function get_mentoring_batch($user_id) {
    	$users_batch = $this->db->query("SELECT DISTINCT B.id AS batch_id, B.day, B.class_time, C.class_on, Ctr.name AS center_name
			FROM Batch B
			INNER JOIN Class C ON C.batch_id=B.id
			INNER JOIN Center Ctr ON B.center_id=Ctr.id
			WHERE B.status='1' AND B.batch_head_id='$user_id' AND B.year='{$this->year}'
				AND C.class_on=(SELECT class_on FROM Class WHERE batch_id=B.id AND class_on < NOW() ORDER BY class_on DESC LIMIT 0,1)")->row();
		if($users_batch) return $users_batch->batch_id;
		else return 0;
    }


    /// Returns all the Users who have a class assigned them that year
    function get_assigned_teachers($city_id = 0) {
    	if(!$city_id) $city_id = $this->city_id;

    	$all_assigned_teachers = $this->db->query("SELECT UB.* FROM UserBatch UB
    		INNER JOIN User U ON U.id=UB.user_id
    		INNER JOIN Batch B ON UB.batch_id=B.id
    		WHERE U.user_type='volunteer' AND U.status='1' AND B.year='{$this->year}' AND U.city_id=$city_id")->result();

    	return $all_assigned_teachers;
    }

    function get_users_batch($user_id) {
		$users_batch = $this->db->query("SELECT UserBatch.batch_id FROM UserBatch
			INNER JOIN Batch ON Batch.id=UserBatch.batch_id
			WHERE UserBatch.user_id=$user_id AND Batch.year={$this->year}")->row();
		if($users_batch) return $users_batch->batch_id;
		else return 0;
    }

    function get_fellows($city_id=0, $vertical_id=0) {
    	$where_city = '';
    	if($city_id) $where_city = " AND U.city_id=$city_id";
    	$where_vertical = '';
    	if($vertical_id) $where_vertical = " AND G.vertical_id=$vertical_id";

    	$fellows = $this->db->query("SELECT DISTINCT U.id,U.name, G.name AS title,G.vertical_id AS title FROM User U
    		INNER JOIN UserGroup UG ON U.id=UG.user_id
    		INNER JOIN `Group` G ON UG.group_id=G.id
    		WHERE G.type='fellow' AND U.user_type='volunteer' AND UG.year='{$this->year}' AND U.status='1' $where_city $where_vertical
    		GROUP BY U.id")->result();

    	return $fellows;
    }

    function get_fellows_or_above($city_id=0, $vertical_id=0) {
    	$where_city = '';
    	if($city_id) $where_city = " AND U.city_id=$city_id";
    	$where_vertical = '';
    	if($vertical_id) $where_vertical = " AND G.vertical_id=$vertical_id";

    	$fellows = $this->db->query("SELECT U.id,U.name,G.name AS title,G.vertical_id,G.type AS group_type,U.city_id
    		FROM User U
    		INNER JOIN UserGroup UG ON U.id=UG.user_id
    		INNER JOIN `Group` G ON UG.group_id=G.id
    		WHERE (G.type='fellow' OR G.type='strat' OR G.type='national') AND U.user_type='volunteer' AND U.status='1' AND UG.year='{$this->year}' $where_city $where_vertical
    		GROUP BY U.id")->result();

    	return $fellows;
    }

	function search_users($data, $return_info = false) {
		$this->db->start_cache();
		$this->db->select('User.id,User.name,User.photo,User.email,User.mad_email,User.password,User.phone,User.credit,
							User.joined_on,User.left_on,User.user_type,User.address,User.sex,User.source,User.birthday,
							User.job_status,User.preferred_day,User.why_mad, City.name as city_name, User.subject_id, User.reason_for_leaving');
		$this->db->join('City', 'City.id = User.city_id' ,'left');

		if(!isset($data['status'])) $data['status'] = 1;
		if($data['status'] !== false) $this->db->where('User.status', $data['status']); // Setting status as 'false' gets you even the deleted users

		// if(!empty($data['project_id'])) $this->db->where('User.project_id', $data['project_id']);
		// elseif($this->project_id) $this->db->where('User.project_id', $this->project_id);

		if(isset($data['city_id']) and $data['city_id'] != 0) $this->db->where('User.city_id', $data['city_id']);
		else if(!isset($data['city_id']) and $this->city_id) $this->db->where('User.city_id', $this->city_id);

		if(!empty($data['user_type'])) $this->db->where('user_type', $data['user_type']);
		else if(!isset($data['user_type'])) $this->db->where('user_type', 'volunteer');

		if(!empty($data['not_user_type'])) $this->db->where_not_in('user_type', $data['not_user_type']);
		if(!empty($data['id'])) $this->db->where('User.id', $data['id']);
		if(!empty($data['name'])) $this->db->like('User.name', $data['name']);
		if(!empty($data['phone'])) $this->db->where('User.phone', $data['phone']);
		if(!empty($data['email'])) {
			$this->db->where('(User.email', "'" . $data['email'] . "'", false); // false prevents the fieldnames getting enclosed by ` backticks.
			$this->db->or_where('User.mad_email', "'" . $data['email'] . "')", false);
		}
		if(!empty($data['left_on'])) $this->db->where('DATE_FORMAT(User.left_on, "%Y-%m") = ', date('Y-m', strtotime($data['left_on'])));
		if(!empty($data['left_on_after'])) $this->db->where('(DATE_FORMAT(User.left_on, "%Y-%m-%d") > \'' . date('Y-m-d', strtotime($data['left_on_after'])) . "' OR User.left_on = '0000-00-00')");

		if(!empty($data['joined_on'])) $this->db->where('DATE_FORMAT(User.joined_on, "%Y-%m") = ', date('Y-m', strtotime($data['joined_on'])));
		if(!empty($data['joined_on_after'])) $this->db->where('(DATE_FORMAT(User.joined_on, "%Y-%m-%d") > \''.date('Y-m-d', strtotime($data['joined_on_after'])) . "' OR User.joined_on = '0000-00-00 00:00:00')");

		if(!empty($data['user_group'])) {
			$this->db->join('UserGroup', 'User.id = UserGroup.user_id' ,'join');
			$this->db->where_in('UserGroup.group_id', $data['user_group']);
			$this->db->where('UserGroup.year', $this->year);
		}
		if(!empty($data['user_group_type'])) {
			$this->db->join('UserGroup', 'User.id = UserGroup.user_id' ,'join');
			$this->db->join('Group', 'Group.id = UserGroup.group_id' ,'join');
			$this->db->where_in('Group.type', $data['user_group_type']);
			$this->db->where('UserGroup.year', $this->year);
		}
		if(!empty($data['user_group_group_type'])) {
			$this->db->join('UserGroup', 'User.id = UserGroup.user_id' ,'join');
			$this->db->join('Group', 'Group.id = UserGroup.group_id' ,'join');
			$this->db->where_in('Group.group_type', $data['user_group_group_type']);
			$this->db->where('UserGroup.year', $this->year);
		}
		if(!empty($data['center'])) {
			$this->db->join('UserClass', 'User.id = UserClass.user_id' ,'join');
			$this->db->join('Class', 'Class.id = UserClass.class_id' ,'join');
			$this->db->join('Level', 'Class.level_id = Level.id' ,'join');
			$this->db->where_in('Level.center_id', $data['center']);
		}

		// Sorting
		if(!empty($data['user_type'])) {
			if($data['user_type'] == 'applicant' or $data['user_type'] == 'well_wisher') {
				$this->db->orderby('User.joined_on DESC');
			} elseif($data['user_type'] == 'let_go') {
				$this->db->orderby('User.left_on DESC');
			}
		}
		$this->db->orderby('User.name');
	    $this->db->stop_cache();

		// Paging
		$current_page = isset($data['current_page']) ? $data['current_page'] : 1;
		$items_per_page = isset($data['items_per_page']) ? $data['items_per_page'] : 10000;

		if(isset($data['items_per_page'])) {
			$total_items = $this->db->count_all_results('User');
			$total_pages = ceil($total_items / $items_per_page);
			$offset = ($current_page - 1) * $items_per_page;

			$this->db->limit($items_per_page, $offset);
		}

		// Done - execute the query.
		$all_users = $this->db->get('User')->result();
		$this->db->flush_cache();
		// echo $this->db->last_query(); exit; // :DEBUG: See the query if ther are any issues.

		$return = array();
		foreach($all_users as $user) {
			// Get the batches for this User. An user can have two batches. That's why I don't do join to get this date.
			//$user->batches = colFormat($this->db->where('user_id',$user->id)->get('UserBatch')->result_array()); // :SLOW:

			// Gets the UserGroup of the users...
			if(!empty($data['get_user_groups'])) $user->groups = $this->get_user_groups_of_user($user->id);
			if(!empty($data['get_user_class'])) $user->batch = $this->db->query("SELECT Batch.day, Batch.class_time, Center.name
					FROM Batch INNER JOIN UserBatch ON UserBatch.batch_id=Batch.id
					INNER JOIN Center ON Batch.center_id=Center.id
					WHERE UserBatch.user_id={$user->id} AND Batch.year={$this->year}")->row();

			$return[$user->id] = $user;
		}

		if(!$return_info) return $return;
		else return array(
				'total_items'	=> $total_items,
				'total_pages'	=> $total_pages,
				'all_users'		=> $return
			);
	}
/////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/// Returns all the permissions for the given user as an array.
	function get_user_permissions($user_id) {
		$permissions = $this->db->query("SELECT DISTINCT(Permission.name) FROM Permission
			INNER JOIN GroupPermission ON GroupPermission.permission_id=Permission.id
			INNER JOIN UserGroup ON GroupPermission.group_id=UserGroup.group_id
			WHERE UserGroup.user_id=$user_id")->result();

		if(!count($permissions)) { // If he has no group, he is volunteer group.
			$default_group = 9; //:HARD-CODE: 9 is the teacher group.
			$permissions = $this->db->query("SELECT DISTINCT(Permission.name) FROM Permission
				INNER JOIN GroupPermission ON GroupPermission.permission_id=Permission.id
				WHERE GroupPermission.group_id=$default_group")->result();
		}

		$all_permissions = array();
		foreach($permissions as $permission) {
			$all_permissions[] = $permission->name;
		}

		return $all_permissions;
	}

	/// Returns all the groups for the given user as an associative array with group id as the key.
	function get_user_groups($user_id, $details = false) {
		$groups = $this->db->query("SELECT * FROM `Group`
			INNER JOIN `UserGroup` ON `Group`.id=`UserGroup`.group_id
			WHERE `UserGroup`.user_id=$user_id AND UserGroup.year='{$this->year}'")->result();

		if($details) return $groups;

		$all_groups = array();
		foreach($groups as $group) {
			$all_groups[$group->id] = $group->name;
		}

		return $all_groups;
	}

	/// Find the highest postion this person holds.
	function get_highest_group($user_id, $groups = false, $return_info = true) {
		if(!$groups) $groups = $this->get_user_groups($user_id, true);

		$order = array('executive'=>15,'national'=>10,'strat'=>8,'fellow'=>5,'volunteer'=>3);

		$highest_group = '';
		$highest_group_info = false;
		$highest_number = 0;

		foreach($groups as $g) {
			if($g->vertical_id == 0) continue; // We need the highest in the vertical.

			if($order[$g->type] > $highest_number) {
				$highest_number = $order[$g->type];
				$highest_group = $g->type;
				$highest_group_info = $g;
			}
		}

		// No vertical info. National someone, possibly. Check again without the NON Vertical Check.
		if(!$highest_group_info
				or (count($groups) > 1 and $highest_group == 'volunteer')) { // A peorson without a vertical who is a teacher.
			foreach($groups as $g) {
				if($order[$g->type] > $highest_number) {
					$highest_number = $order[$g->type];
					$highest_group = $g->type;
					$highest_group_info = $g;
				}
			}
		}

		if($return_info) return $highest_group_info;

		return $highest_group;
	}

	function user_registration($data)
	{
		if(!empty($data['user_id'])) { // Handle registeration of exisitng applicants(Adding more details, re-doing application, etc.)
			$user_type = $this->db->query("SELECT user_type FROM User WHERE id=$data[user_id]")->row();
			if($user_type->user_type != 'applicant') {
				$this->session->set_flashdata('error', 'Only Applicants can use this form.');
				return array(false, 'Only Applicants can use this form.');
			}
			$userdetailsArray = array(	'name'		=> $data['name'],
										'email'		=> $data['email'],
										'phone'		=> $this->_correct_phone_number($data['phone']),
										'address'	=> $data['address'],
										'sex'		=> $data['sex'],
										'city_id'	=> $data['city_id'],
										'job_status'=> $data['job_status'],
										'birthday'	=> date('Y-m-d', strtotime($data['birthday'])),
										'why_mad'	=> $data['why_mad'],
										'source'	=> $data['source'],
										);
			$this->db->where('id', $data['user_id'])->update('User', $userdetailsArray);
			$userdetailsArray['id'] = $data['user_id'];
			return array($userdetailsArray, 'Success');
		}

		$email = $data['email'];
		$debug = "";

		// Make sure there is no duplication of emails - or phone...
        $result = $this->db->query("SELECT id,email,phone,user_type,status FROM User WHERE email='$email' OR phone='{$data['phone']}'")->result();

        $debug .= print_r($result, 1);
        if(!$result) {
        	$password_hash = password_hash('pass', PASSWORD_BCRYPT, ['cost' => 10]); // 'pass' is the default password.
			$userdetailsArray = array(	'name'		=> $data['name'],
										'email'		=> $data['email'],
										'phone'		=> $this->_correct_phone_number($data['phone']),
										'address'	=> $data['address'],
										'sex'		=> $data['sex'],
										'city_id'	=> $data['city_id'],
										'job_status'=> $data['job_status'],
										'birthday'	=> date('Y-m-d', strtotime($data['birthday'])),
										'why_mad'	=> $data['why_mad'],
										'source'	=> $data['source'],
										'campaign'	=> $data['campaign'],
										'user_type'	=> 'applicant',
										'status'	=> '1',
										'password_hash'  => $password_hash,
										'joined_on' => date('Y-m-d H:i:s'),
										'project_id'=> 1
									);
			$this->db->insert('User', $userdetailsArray);
			$debug .= $this->db->last_query();

			$userdetailsArray['id'] = $this->db->insert_id();

			$debug .= print_r($userdetailsArray, 1);
			// $this->db->where('name','registeration_debug_info')->update('Setting', array('data'=>$debug)); // :DEBUG:

			return array($userdetailsArray, "Success");
		} else {
			foreach($result as $r) {
				if($r->email == $data['email']) {
					$current_status = 'Email already in database.';
				} elseif($r->phone == $data['phone']) {
					$current_status = 'Phone number already in database.';
				}

				// If a user with pre existing email id or phone number tries to register again, we check what kind of user they are - and if they are well_wisher, alumni or let_go, we make them an applicant once again.
				$more = 'You are already registered. ';
				if($r->user_type != 'volunteer') {
					$this->db->where('id', $r->id)->update('User', array('user_type'=>'applicant', 'joined_on'=>date('Y-m-d H:i:s')));
					$more = 'Your application has been bumped up. You will be informed when there is a recuitment happening in your city. Thank you.';

				}
				if($r->status == '0') {
					$this->db->where('id', $r->id)->update('User', array('status'=>'1', 'joined_on'=>date('Y-m-d H:i:s')));
					$more = 'You have been added back to the applicant list. Thank you.';
				}

				return array(false, $current_status .' ' . $more);
			}

			return array(false, "");
		}
		return array(true, '');
    }

	/**
    * Function to get password
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_password($data) {
		$email=$data['email'];
		return $this->db->where('email', $email)->get("User")->row();
	}

	function get_user_data($user_id, $name) {
		$result = $this->db->query("SELECT * FROM UserData WHERE name LIKE '$name' AND user_id=$user_id");
		return $result->result_array();
	}

	function save_user_data($user_id, $data) {
    	$this->db->delete("UserData", array('user_id'=>$user_id, 'name'=>$data['name']));
    	$this->db->insert("UserData", $data);
	}

	function get_usercredits($current_user_id) {
		$this->db->select('UserClass.*,Class.class_on');
		$this->db->from('UserClass');
		$this->db->join('Class','Class.id=UserClass.class_id','join');
		$this->db->join('Batch','Batch.id=Class.batch_id','join');
		// $this->db->where("Class.class_on BETWEEN '{$this->year}-04-01 00:00:00' AND '".($this->year + 1)."-03-31 23:59:59'");
		$this->db->where("Batch.year", $this->year);
		$this->db->where("Batch.status", '1');
		$this->db->where("(UserClass.user_id=$current_user_id OR UserClass.substitute_id=$current_user_id)");
		$this->db->orderby('Class.class_on');
		$result = $this->db->get();

		if($result) return $result->result_array();
		return array();
	}
	/**
    * Function to  get_name_of_substitute
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_name_of_substitute($substitute_id)
	{
		$this->db->select('name');
		$this->db->from('User');
		$this->db->where('id',$substitute_id);
		$result=$this->db->get();
		return $result->row();
	}

	function get_credit_leaderboard($city_id) {
		return $this->db->query("SELECT id, name, credit FROM User WHERE city_id=$city_id AND status='1' AND user_type='volunteer' ORDER BY credit DESC LIMIT 0,10")->result_array();
	}

	/// Return all the people under you - till fellow level
	function get_all_below($level='fellow', $vertical_id = 0, $region_id = 0, $city_id = 0) {
		$allowed = array(
			'executive'	=> "'executive', 'national','strat','fellow'",
			'national'	=> "'strat','fellow'",
			'strat'		=> "'fellow'",
			'fellow'	=> "'fellow'",
		);

		if($level == 'fellow' and $vertical_id != 1) return array(); // Nothing under fellow(if not CTL) as volunteers not returned.
		$where = array();
		if($vertical_id and $vertical_id != 1) $where[] = "G.vertical_id=$vertical_id";
		//if($region_id) $where[] = "City.region_id=$region_id"; // Apparently they want all the fellows to show up. Not just their region ka strat.
		if($vertical_id == 1) $where[] = "U.city_id=$city_id AND G.type='fellow'";

		$subordinates = $this->db->query("SELECT DISTINCT U.id,U.*,City.name AS city_name,City.region_id,G.vertical_id,G.name AS group_name FROM User U
			INNER JOIN UserGroup UG ON UG.user_id=U.id
			INNER JOIN `Group` G ON G.id=UG.group_id
			INNER JOIN City ON U.city_id=City.id
			WHERE G.type IN (".$allowed[$level].")
			AND U.status='1' AND U.user_type='volunteer' "
			. (($where) ? " AND " : "") . implode(" AND ", $where)
			. " GROUP BY UG.user_id ORDER BY City.region_id, City.name")->result();

		return $subordinates;
	}


	/// Returns most necessary info about the user - the entire user table + Vertical, Groups, Centers.
	function get_info($user_id) {
		$user = $this->db->query('SELECT U.*,City.region_id FROM User U INNER JOIN City ON City.id=U.city_id WHERE U.id='.$user_id)->row();

		$user->groups = $this->get_user_groups($user_id, true);
		$highest_group = $this->get_highest_group($user_id, $user->groups, true);

		$user->vertical_id = $highest_group->vertical_id;
		$user->group_type = $highest_group->type;

		// Some special case for region
		if($user->region_id == 5) $user->region_id = 0; // National Region is 0.
		// :HARDCODE:
		if($user->id == 42117)	$user->region_id = 1;	// Aswin
		if($user->id == 538)	$user->region_id = 2;	// Kaus
		if($user->id == 18269)	$user->region_id = 4;	// Shilpa
		if($user->id == 17383)	$user->region_id = 3;	// Vrishi

		$teacher_info = idNameFormat($this->db->query("SELECT Batch.id, Batch.center_id AS name
					FROM Batch INNER JOIN UserBatch ON UserBatch.batch_id=Batch.id
					WHERE UserBatch.user_id={$user_id}")->result());

		$user->batches = array_keys($teacher_info);
		$user->centers = array_unique(array_values($teacher_info));

		return $user;
	}


	function get_intern_credit($user_id) {
		$credit = $this->db->query("SELECT SUM(credit) AS credit FROM UserCredit WHERE user_id=$user_id AND year={$this->year}")->row();

		return $credit->credit;
	}

	function get_subordinates($user_id) {
		$current_user_ka_groups = implode(',', array_keys($this->get_user_groups_of_user($user_id)));

		$subordinates = $this->db->query("SELECT DISTINCT U.id,U.* FROM User U
			INNER JOIN UserGroup UG ON UG.user_id=U.id
			INNER JOIN GroupHierarchy GH ON GH.group_id=UG.group_id
			WHERE GH.reports_to_group_id IN ($current_user_ka_groups)
			AND U.status='1' AND U.user_type='volunteer'
			ORDER BY U.city_id DESC")->result();

		return $subordinates;
	}

	/// Changes the phone number format from +91976068565 to 9746068565. Remove the 91 at the starting.
	private function _correct_phone_number($phone) {
		if(strlen($phone) > 10) {
			return preg_replace('/^\+?91\D?/', '', $phone);
		}
		return $phone;
	}
}
