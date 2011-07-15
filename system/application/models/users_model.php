<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 
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
		
		$query = $this->db->where('email', $username)->where('password',$password)->where('status','1')->get("User");
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
	function edit_group($uid)
	{
		$this->db->select('*');
		$this->db->from('Group');
		$this->db->where('id',$uid);
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
		$groups = $this->db->query("SELECT Group.$data AS data FROM `Group` INNER JOIN UserGroup ON Group.id=UserGroup.group_id WHERE UserGroup.user_id=$user_id")->result();
		$all_groups = array();
		foreach($groups as $g) $all_groups[] = $g->data;
		
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
		if($where) {
			if($where['city_id']) $this->db->where('User.city_id', $where['city_id']);
		}
		$this->db->join('City', 'City.id = User.city_id' ,'join');
		
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
			'title'		=> $data['position'],
			'email'		=> $data['email'],
			'phone'		=> $this->_correct_phone_number($data['phone']),
			'password'	=> $data['password'],
			'address'	=> $data['address'],
			'city_id'	=> $data['city'],
			'project_id'=> $data['project'],
			'user_type' => $data['type']
		);
		if(!empty($data['joined_on'])) $user_array['joined_on'] = $data['joined_on'];
		if(!empty($data['left_on'])) $user_array['left_on'] = $data['left_on'];
		
		$this->db->insert('User',$user_array);
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
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
	/**
    * Function to process_pic
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,Array() ]
    **/
	function process_pic($data)
    {   
      	$id=$data['id'];
        //Get File Data Info
        $uploads = array($this->upload->data());
        $this->load->library('image_lib');
        $this->load->library('imageResize');
        
        //Move Files To User Folder
        foreach($uploads as $key[] => $value)
        {
            //Gen Random code for new file name
            $randomcode = $this->generate_code(12);
            $newimagename = $randomcode.$value['file_ext'];
			rename($value['full_path'],'pictures/'.$newimagename);
			
            $nwidth='100';
	        $nheight='90';
			$fileSavePath= dirname(BASEPATH). '/pictures/'.$newimagename;
			imagejpeg(imageResize::Resize($fileSavePath,$nwidth,$nheight),$fileSavePath);
            $imagename = $newimagename;
            $thumbnail = $randomcode.'_tn'.$value['file_ext'];
            $this->db->set('photo', $imagename);
            //$this->db->set('thumbnail', $thumbnail);
			$this->db->where('id',$id);
            $this->db->update('User');
			return ($this->db->affected_rows() > 0) ? true: false ;

        }
 	}       
	/**
    * Function to generate_code
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,Array() ]
    **/
	function generate_code($length = 10)
	{
		$this->load->library('image_lib');
		if ($length <= 0) {
			return false;
		}
		
		$code = "";
		$chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
		srand((double)microtime() * 1000000);
		
		for ($i = 0; $i < $length; $i++) {
			$code = $code . substr($chars, rand() % strlen($chars), 1);
		}
		return $code;
	
	}
 
	
	/**
    * Function to adduser_to_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function adduser_to_group($user_id, $group_ids)
	{
		foreach($group_ids as $group_id) {
			$user_array=array('user_id'=>$user_id, 'group_id'=> $group_id);
			$this->db->insert('UserGroup',$user_array);
		}
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;		
	}
	/**
    * Function to user_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function user_details($uid)
	{
		$this->db->from('User');
		$this->db->where('User.id',$uid)->where('User.status','1');
		
		$result = $this->db->get()->row();
		$result->groups = $this->get_user_groups_of_user($uid, 'id');
		
		return $result;
	}
	
	/**
    * Function to updateuser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function updateuser($data) {
		$rootId=$data['rootId'];
		$user_array=array('name'=>$data['name'],
				'title'=> $data['position'],
				'email' => $data['email'],
				'phone' => $this->_correct_phone_number($data['phone']),
				'address'=>$data['address'],
				'city_id'=> $data['city'],
				'project_id'=>$data['project'],
				'user_type' => $data['type']
			);
			if(!empty($data['joined_on'])) $user_array['joined_on'] = $data['joined_on'];
			if(!empty($data['left_on'])) $user_array['left_on'] = $data['left_on'];
			if(isset($data['password'])) $user_array['password'] = $data['password'];
				
			$this->db->where('id', $rootId);
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
    
    function update_credit($user_id, $credit) {
    	if($credit == 1) $credit = '+1';
    	if($credit == 2) $credit = '+2';
    	$this->db->query("UPDATE User SET credit=credit $credit WHERE id=$user_id");
    }
    
    function get_users_batch($user_id) {
    	return $this->db->query("SELECT batch_id FROM UserBatch WHERE user_id=$user_id")->row()->batch_id;
    }
	

	function search_users($data) {
		$this->db->select('User.id,User.name,User.photo,User.email,User.phone,User.credit,User.joined_on,User.title,User.user_type, City.name as city_name');
		$this->db->from('User');
		$this->db->join('City', 'City.id = User.city_id' ,'left');
		
		
		if(!isset($data['status'])) $data['status'] = 1;
		if($data['status'] !== false) $this->db->where('User.status', $data['status']); // Setting status as 'false' gets you even the deleted users
		
		if(!empty($data['project_id'])) $this->db->where('User.project_id', $data['project_id']);
		else $this->db->where('User.project_id', $this->project_id);
		
		if(isset($data['city_id']) and $data['city_id'] != 0) $this->db->where('User.city_id', $data['city_id']);
		else if(!isset($data['city_id'])) $this->db->where('User.city_id', $this->city_id);
		
		if(!empty($data['user_type'])) $this->db->where('user_type', $data['user_type']);
		if(!empty($data['name'])) $this->db->like('User.name', $data['name']);
		
		if(!empty($data['user_group'])) {
			$this->db->join('UserGroup', 'User.id = UserGroup.user_id' ,'join');
			$this->db->where_in('UserGroup.group_id', $data['user_group']);
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
		$email = $data['email'];
        $this->db->select('email');
        $this->db->from('User');
        $this->db->where('email',$email);
        
        $result=$this->db->get();
        if($result->num_rows() == 0) {
			$userdetailsArray = array(	'name'		=> $data['firstname'],
										'email'		=> $data['email'],
										'phone'		=> $this->_correct_phone_number($data['mobileno']),
										'city_id'	=>$data['city'],
										'user_type'	=>'applicant',
										'status'	=> '1'
										);
			$this->db->set($userdetailsArray);
			$this->db->insert('User');
			$user_id=$this->db->insert_id();
				
			$this->db->select('User.*,City.name as city_name,city.id as city_id');
			$this->db->from('User');
			$this->db->join('City', 'City.id = User.city_id' ,'join');
			$this->db->where('User.id',$user_id);
			$result=$this->db->get();
			$user=$result->first_row();
			$memberCredentials['id'] = $user->id;
			$memberCredentials['email'] = $user->email;
			$memberCredentials['name'] = $user->name;
			$memberCredentials['phone'] = $user->phone;
			$memberCredentials['city_id'] = $user->city_id;
			
			return $memberCredentials;
			
		} else {
			return false;
		}
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
	/**
    * Function to get_new_recruit_mail
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_new_recruit_mail()
	{
		$this->db->select('data');
		$this->db->from('Setting');
		$this->db->where('name','new_recruit_mail');
		$result=$this->db->get();
		return $result->row();
	}
	/**
    * Function to get_hr_email
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_hr_email($city_id)
	{
		$this->db->select('value');
		$this->db->from('Setting');
		$this->db->where('name','hr_email_city_'.$city_id);
		$result=$this->db->get();
		return $result->row();
	
	}
	/**
    * Function to get get_new_registration_notification
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_new_registration_notification()
	{
		$this->db->select('data');
		$this->db->from('Setting');
		$this->db->where('name','new_registration_notification');
		$result=$this->db->get();
		return $result->row();
	
	}
	/**
    * Function to  get_usercredits
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_usercredits()
	{
		$current_user_id=$this->session->userdata('id');
		$this->db->select('UserClass.*,Class.class_on');
		$this->db->from('UserClass');
		$this->db->join('Class','Class.id=UserClass.class_id','join');
		$this->db->where('UserClass.user_id',$current_user_id);
		$this->db->or_where('UserClass.substitute_id',$current_user_id);
		return $this->db->get();
	
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
	
	/// Changes the phone number format from +91976068565 to 9746068565. Remove the 91 at the starting.
	private function _correct_phone_number($phone) {
		if(strlen($phone) > 10) {
			return preg_replace('/^\+?91\D?/', '', $phone);
		}
		return $phone;
	}

	
}