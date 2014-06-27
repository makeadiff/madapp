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
		
		$query = $this->db->where('email', $username)->where('password',$password)->where('status','1')->where('user_type', 'volunteer')->get("User");
        if($query->num_rows() > 0) {
			$user = $query->first_row();
			
   			$memberCredentials['id'] = $user->id;
			$memberCredentials['email'] = $user->email;
			$memberCredentials['name'] = $user->name;
			$memberCredentials['project_id'] = $user->project_id;
			$memberCredentials['city_id'] = $user->city_id;
			$memberCredentials['permissions'] = $this->get_user_permissions($user->id);
			$memberCredentials['groups'] = $this->get_user_groups($user->id);
			
            return $memberCredentials;
        
        } else {
           return false;
        }
    }
    
	/**
    * Function to group_count
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function group_count()
	{
		
		
	}
	/**
    * Function to getgroup_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getgroup_details()
	{
		$this->db->select('*');
		$this->db->from('Group');
		$result=$this->db->get();
		return $result;
	}
	function get_all_groups() {
		return $this->db->from('Group')->get()->result();
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
		$this->db->delete('Group');
		
		$this->db->where('group_id',$id);
		$this->db->delete('GroupPermission');
			
		return ($this->db->affected_rows() > 0) ? true: false ;
	}
	
	/// Returns the groups the current user belongs to...
	function get_user_groups_of_user($user_id, $data='name') {
		$groups = $this->db->query("SELECT Group.id, Group.$data AS name FROM `Group` INNER JOIN UserGroup ON Group.id=UserGroup.group_id WHERE UserGroup.user_id=$user_id")->result();
		$all_groups = idNameFormat($groups);
		
		return $all_groups;
	}
	
	/**
    * Function to users_count
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function users_count()
	{
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
		$this->db->where('User.project_id',$this->project_id)->where('User.status','1');
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
		$this->db->where('User.project_id',$this->project_id)->where('User.status','1');
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
		$email=$data['email'];
		$this->db->select('email');
		$this->db->from('User');
		$this->db->where('email',$email);
		$result=$this->db->get();
		if($result->num_rows() > 0){
			return false;
		} else {
			$user_array = array(
				'name'		=>$data['name'],
				'email'		=> $data['email'],
				'phone'		=> $this->_correct_phone_number($data['phone']),
				'password'	=> $data['password'],
				'address'	=> $data['address'],
				'sex'		=> $data['sex'],
				'city_id'	=> $data['city'],
				'english_teacher'	=> isset($data['english_teacher']) ? $data['english_teacher'] : 0,
				'dream_tee'	=> isset($data['dream_tee']) ? $data['dream_tee'] : 0,
				'events'	=> isset($data['events']) ? $data['events'] : 0,
				'placements'=> isset($data['placements']) ? $data['placements'] : 0,
				'project_id'=> $data['project'],
				'user_type' => $data['type'],
			);
			if(!empty($data['joined_on'])) $user_array['joined_on'] = $data['joined_on'];
			else $user_array['joined_on'] = date('Y-m-d H:i:s');
			
			if(!empty($data['left_on'])) $user_array['left_on'] = $data['left_on'];
			
			$this->db->insert('User',$user_array);
			return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
		}
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
	function adduser_to_group($user_id, $group_ids)
	{
		foreach($group_ids as $group_id) {
			$user_array=array('user_id'=>$user_id, 'group_id'=> $group_id);
			$this->db->insert('UserGroup',$user_array);
		}
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;		
	}
	
	/// Removes the given user from the given group.
	function remove_user_from_group($user_id, $group_id) {
		$this->db->delete('UserGroup', array('user_id'=>$user_id, 'group_id'=>$group_id));
		return ($this->db->affected_rows() > 0) ? true : false;		
	}
	
	/**
    * Function to user_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function user_details($user_id)
	{
		$this->db->from('User');
		$this->db->where('User.id',$user_id);//->where('User.status','1');
		
		$result = $this->db->get()->row();
		$result->groups = $this->get_user_groups_of_user($user_id, 'id');
		$result->groups_name = $this->get_user_groups_of_user($user_id, 'name');
		$result->batch = $this->db->query("SELECT Batch.day, Batch.class_time, Center.name 
					FROM Batch INNER JOIN UserBatch ON UserBatch.batch_id=Batch.id 
					INNER JOIN Center ON Batch.center_id=Center.id WHERE UserBatch.user_id={$user_id}")->row();
		
		return $result;
	}
	
	/**
    * Function to updateuser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function updateuser($data) {
		$user_id = $data['rootId'];
		$user_array=array(
			'name'  => $data['name'],
			'email' => $data['email'],
			'phone' => $this->_correct_phone_number($data['phone']),
			'address'=>$data['address'],
			'sex'	=> $data['sex'],
			'english_teacher'	=> $data['english_teacher'],
			'dream_tee'	=> $data['dream_tee'],
			'events'	=> $data['events'],
			'placements'=> $data['placements'],
		);
		if(!empty($data['city'])) $user_array['city_id'] = $data['city'];
		if(!empty($data['project'])) $user_array['project_id'] = $data['project'];
		if(!empty($data['joined_on'])) $user_array['joined_on'] = $data['joined_on'];
		if(!empty($data['left_on'])) $user_array['left_on'] = $data['left_on'];
		if(isset($data['password'])) $user_array['password'] = $data['password'];
		if(!empty($data['reason_for_leaving'])) $user_array['reason_for_leaving'] = $data['reason_for_leaving'];
		
		if(!empty($data['type'])) {
			$user_array['user_type'] = $data['type'];
			if($user_array['user_type'] == 'let_go' || $user_array['user_type'] == 'alumni') { // Remove user from his classes when he is let go.
				if(!$user_array['left_on'] or $user_array['left_on'] == '0000-00-00') $user_array['left_on'] = date('Y-m-d');
			
				$this->db->delete('UserBatch', array('user_id'=>$user_id));
				$this->db->delete('UserClass', array('user_id'=>$user_id, 'status'=>'projected'));
				$this->db->delete('UserClass', array('user_id'=>$user_id, 'status'=>'confirmed'));
			}
		}
			
		$this->db->where('id', $user_id);
		$this->db->update('User', $user_array);
		return ($this->db->affected_rows() > 0) ? true: false ;
	}
	
	/**
    * Function to updateuser_to_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function updateuser_to_group($data)
	{	
		$rootId=$data['rootId'];
		$this->db->where('user_id',$rootId);
		$this->db->delete('UserGroup');
		$group=$data['group'];
		for($i=0;$i <sizeof($group);$i++)
		{
		 	$data['group']=$group[$i];
			$user_array=array('group_id'=> $data['group'],'user_id'=>$rootId);
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
	
	function getUsersById() {
		$this->load->helper('misc');
		return getById("SELECT id, name FROM User WHERE city_id={$this->city_id} AND project_id={$this->project_id} AND user_type='volunteer' AND status='1'", $this->db);
	}
		
	function get_users_in_city($city_id=false) {
		if($city_id === false) $city_id = $this->city_id;
		return $this->db->where('city_id', $city_id)->where('project_id',$this->project_id)->where('user_type','volunteer')->where('status','1')->orderby('name')->get('User')->result();
	}
	
	function set_user_batch_and_level($user_id, $batch_id, $level_id) {
    	$this->db->insert("UserBatch", array('user_id'=>$user_id, 'batch_id'=>$batch_id, 'level_id'=>$level_id));
    }
    
	function unset_user_batch_and_level($batch_id, $level_id) {
    	$this->db->delete("UserBatch", array('batch_id'=>$batch_id, 'level_id'=>$level_id));
    }
    
    function update_credit($user_id, $change) {
		//if($change == 0) return;
		
    	if($change == 1) $change = '+1';
    	if($change == 2) $change = '+2';
		if($change == .5) $change = '+.5';
    	$this->db->query("UPDATE User SET credit=credit $change WHERE id=$user_id");
    }
    function set_credit($user_id, $credit) {
		$this->db->query("UPDATE User SET credit=$credit WHERE id=$user_id");
    }
    
    function recalculate_user_credit($user_id, $update_if_wrong=false, $debug=false) {
		$this->ci->load->model('level_model');
		$this->ci->load->model('event_model');
		$this->ci->load->model('settings_model');
		
		$credit_for_substituting = $this->ci->settings_model->get_setting_value('credit_for_substituting');
		$credit_for_substituting_in_same_level = $this->ci->settings_model->get_setting_value('credit_for_substituting_in_same_level');
		$credit_lost_for_getting_substitute = $this->ci->settings_model->get_setting_value('credit_lost_for_getting_substitute');
		$credit_lost_for_missing_class = $this->ci->settings_model->get_setting_value('credit_lost_for_missing_class');
		$credit_lost_for_missing_avm = $this->ci->settings_model->get_setting_value('credit_lost_for_missing_avm');
		$credit_lost_for_missing_zero_hour = $this->ci->settings_model->get_setting_value('credit_lost_for_missing_zero_hour');
		$credit_max_credit_threshold = $this->ci->settings_model->get_setting_value('max_credit_threshold');
		$credit = $this->ci->settings_model->get_setting_value('beginning_credit');
		
		$classes_so_far = $this->get_usercredits($user_id);
		
		foreach($classes_so_far as $row) {
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
				// If the sub is from the same level, give him/her 1 credits. Because we are SO generous.
				$substitute_levels = $this->ci->level_model->get_user_level($row['substitute_id']);
				$current_class_level = $this->ci->level_model->get_class_level($row['class_id']);
				if(in_array($current_class_level, $substitute_levels)) {
					$credit_sub_gets = $credit_for_substituting_in_same_level;
				}
				
				if($credit_max_credit_threshold >= ($credit + $credit_sub_gets)) {
					$credit = $credit + $credit_sub_gets;
					if($debug) print "Credit for subbing: $credit_sub_gets<br />\n";
				} else {
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
		}
		
		$event_attendence = $this->ci->event_model->get_missing_user_attendance_for_event_type($user_id, 'avm');
		$avm_count = 0;
		foreach($event_attendence as $event) {
			$avm_count++; // You can miss one AVM without any credit loss.
			if($avm_count > 1);
			$credit = $credit + $credit_lost_for_missing_avm;
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

    	$fellows = $this->db->query("SELECT U.id,U.name,G.name AS title FROM User U
    		INNER JOIN UserGroup UG ON U.id=UG.user_id
    		INNER JOIN `Group` G ON UG.group_id=G.id
    		WHERE G.type='fellow' AND U.user_type='volunteer' AND U.status='1' $where_city $where_vertical")->result();

    	return $fellows;
    }
	

	function search_users($data) {
		$this->db->select('User.id,User.name,User.photo,User.email,User.password,User.phone,User.credit,
							User.joined_on,User.left_on,User.user_type,User.address,User.sex,User.source,User.birthday,
							User.job_status,User.preferred_day,User.why_mad, City.name as city_name');
		$this->db->from('User');
		$this->db->join('City', 'City.id = User.city_id' ,'left');
		
		
		if(!isset($data['status'])) $data['status'] = 1;
		if($data['status'] !== false) $this->db->where('User.status', $data['status']); // Setting status as 'false' gets you even the deleted users
		
		if(!empty($data['project_id'])) $this->db->where('User.project_id', $data['project_id']);
		elseif($this->project_id) $this->db->where('User.project_id', $this->project_id);
		
		if(isset($data['city_id']) and $data['city_id'] != 0) $this->db->where('User.city_id', $data['city_id']);
		else if(!isset($data['city_id'])) $this->db->where('User.city_id', $this->city_id);
		
		if(!empty($data['user_type'])) $this->db->where('user_type', $data['user_type']);
		if(!empty($data['not_user_type'])) $this->db->where_not_in('user_type', $data['not_user_type']);
		if(!empty($data['name'])) $this->db->like('User.name', $data['name']);
		if(!empty($data['phone'])) $this->db->where('User.phone', $data['phone']);
		if(!empty($data['email'])) $this->db->where('User.email', $data['email']);
		if(!empty($data['left_on'])) $this->db->where('DATE_FORMAT(User.left_on, "%Y-%m") = ', date('Y-m', strtotime($data['left_on'])));
		
		if(!empty($data['user_group'])) {
			$this->db->join('UserGroup', 'User.id = UserGroup.user_id' ,'join');
			$this->db->where_in('UserGroup.group_id', $data['user_group']);
		}
		if(!empty($data['center'])) {
			$this->db->join('UserClass', 'User.id = UserClass.user_id' ,'join');
			$this->db->join('Class', 'Class.id = UserClass.class_id' ,'join');
			$this->db->join('Level', 'Class.level_id = Level.id' ,'join');
			$this->db->where_in('Level.center_id', $data['center']);
		}
		
		
		if(!empty($data['user_type'])) {
			if($data['user_type'] == 'applicant') {
				$this->db->orderby('User.joined_on DESC');
			} elseif($data['user_type'] == 'let_go') {
				$this->db->orderby('User.left_on DESC');
			}
		}
		$this->db->orderby('User.name');
		
		$all_users = $this->db->get()->result();
		//echo $this->db->last_query();

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
		return $return;
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
	function get_user_groups($user_id) {
		$groups = $this->db->query("SELECT `Group`.id,`Group`.name FROM `Group`
			INNER JOIN `UserGroup` ON `Group`.id=`UserGroup`.group_id 
			WHERE `UserGroup`.user_id=$user_id")->result();
		
		$all_groups = array();
		foreach($groups as $group) {
			$all_groups[$group->id] = $group->name;
		}
		
		return $all_groups;
	}
	
	function user_registration($data)
	{
		if(!empty($data['user_id'])) {
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
										'english_teacher'	=> $data['english_teacher'],
										'dream_tee'	=> $data['dream_tee'],
										'events'	=> $data['events'],
										'placements'=> $data['placements'],
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
			$userdetailsArray = array(	'name'		=> $data['name'],
										'email'		=> $data['email'],
										'phone'		=> $this->_correct_phone_number($data['phone']),
										'address'	=> $data['address'],
										'sex'		=> $data['sex'],
										'city_id'	=> $data['city_id'],
										'job_status'=> $data['job_status'],
										'birthday'	=> date('Y-m-d', strtotime($data['birthday'])),
										'why_mad'	=> $data['why_mad'],
										'english_teacher'	=> isset($data['english_teacher']) ? 1 : 0,
										'dream_tee'	=> isset($data['dream_tee']) ? 1 : 0,
										'events'	=> isset($data['events']) ? 1 : 0,
										'placements'=> isset($data['placements']) ? 1 : 0,
										'source'	=> $data['source'],
										'user_type'	=> 'applicant',
										'status'	=> '1',
										'password'  => 'pass',
										'joined_on' => date('Y-m-d H:i:s'),
										'project_id'=> 1
										);
			$this->db->insert('User', $userdetailsArray);
			$debug .= $this->db->last_query();
			
			$userdetailsArray['id'] = $this->db->insert_id();
			
			$debug .= print_r($userdetailsArray, 1);
			$this->db->where('name','registeration_debug_info')->update('Setting', array('data'=>$debug));
			
			return array($userdetailsArray, "Success");
		} else {
			foreach($result as $r) {
				if($r->email == $data['email']) {
					$current_status = 'Email already in database.';
				} elseif($r->phone == $data['phone']) {
					$current_status = 'Phone number already in database.';
				}
				
				// If a user with pre existing email id or phone number tries to register again, we check what kind of user they are - and if they are well_wisher, alumni or let_go, we make them an applicant once again.
				$more = 'You are already registered';
				if($r->user_type == 'well_wisher' or $r->user_type == 'alumni' or $r->user_type == 'let_go') {
					$this->db->where('id', $r->id)->update('User', array('user_type'=>'applicant'));
					$more = 'You have been added back to the applicant list. Thank you.';
				}
				if($r->status == '0') {
					$this->db->where('id', $r->id)->update('User', array('status'=>'1'));
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

	function get_usercredits($current_user_id) {
		$this->db->select('UserClass.*,Class.class_on');
		$this->db->from('UserClass');
		$this->db->join('Class','Class.id=UserClass.class_id','join');
		$this->db->where("Class.class_on BETWEEN '{$this->year}-04-01 00:00:00' AND '".($this->year + 1)."-03-31 23:59:59'");
		$this->db->where("(UserClass.user_id=$current_user_id OR UserClass.substitute_id=$current_user_id)");
		$result = $this->db->get();
		
		if($result) return $result->result_array();
		return array();
	}
	/**
    * Function to  get_name_of_Substitute
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_name_of_Substitute($substitute_id)
	{
		$this->db->select('name');
		$this->db->from('User');
		$this->db->where('id',$substitute_id);
		$result=$this->db->get();
		return $result->row();
	
	}


	function get_subordinates($user_id) {
		$current_user_ka_groups = implode(',', array_keys($this->get_user_groups_of_user($user_id)));

		$subordinates = $this->db->query("SELECT U.* FROM User U
			INNER JOIN UserGroup UG ON UG.user_id=U.id
			INNER JOIN GroupHierarchy GH ON GH.group_id=UG.group_id
			WHERE GH.reports_to_group_id IN ($current_user_ka_groups)")->result();

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
