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
		
		$query = $this->db->where('email', $username)->where('password',$password)->get("User");
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
	function update_group($data)
	{
		$rootId=$data['rootId'];
		$data = array('name' => $data['groupname']);
		$this->db->where('id', $rootId);
		$this->db->update('Group', $data);
	 	return ($this->db->affected_rows() > 0) ? true: false ;
	}
	/**
    * Function to update_permission
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function update_permission($data)
	{
			$rootId=$data['rootId'];
			$group_id=$data['groupname'];
			$permission=$data['permission'];
			$this->db->where('group_id',$rootId);
			$this->db->delete('GroupPermission');
			$count=sizeof($permission);
			for($j=0;$j<$count;$j++) {
				$data = array('group_id'=> $rootId, 'permission_id'=>$permission[$j]);
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
	function getuser_details()
	{
		$this->db->select('User.*,Center.name as center_name,City.name as city_name');
		$this->db->from('User');
		$this->db->join('Center', 'Center.id = User.center_id' ,'join');
		$this->db->join('City', 'City.id = User.city_id' ,'join');
		$result=$this->db->get();
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
		$this->db->select('User.id,user.name,user.email,user.phone,user.title,user.user_type,Center.name as center_name,
		City.name as city_name');
		$this->db->from('User');
		$this->db->join('Center', 'Center.id = User.center_id' ,'join');
		$this->db->join('City', 'City.id = User.city_id' ,'join');
		$result=$this->db->get();
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
		$user_array=array('name'=>$data['name'],
					'title'=> $data['position'],
					'email' => $data['email'],
					'phone' => $data['phone'],
					'password'=> $data['password'],
					'center_id'=> $data['center'],
					'city_id'=> $data['city'],
					'project_id' => $data['project'],
					'user_type' => $data['type']
					);
		$this->db->insert('User',$user_array);
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
	}
	/**
    * Function to adduser_to_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function adduser_to_group($data)
	{
		$user_array=array('user_id'=>$data['insert_id'],
					'group_id'=> $data['group']);
		$this->db->insert('UserGroup',$user_array);
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
		$this->db->select('User.*,UserGroup.group_id');
		$this->db->from('User');
		$this->db->join('UserGroup', 'UserGroup.user_id = User.id' ,'left');
		$this->db->where('User.id',$uid);
		$result=$this->db->get();
		//print_r($result);
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
				'phone' => $data['phone'],
				'center_id'=> $data['center'],
				'city_id'=> $data['city'],
				'project_id'=>$data['project'],
				'user_type' => $data['type']
			);
				
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
	/**
    * Function to delete_groupby_userid
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function delete_groupby_userid($data)
	{
		$id = $data['entry_id'];
		$this->db->where('id',$id);
		$this->db->delete('User');
		$this->db->where('user_id',$id);
		$this->db->delete('Group');
		return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	
	function get_user($user_id) {
		return $this->db->where('id', $user_id)->get('User')->row();
	}
	
	function getUsersById() {
		$this->load->helper('misc');
		return getById("SELECT id, name FROM User WHERE city_id=1 AND project_id=1 AND user_type='volunteer'", $this->db);
	}
	
	function get_users_in_center($center_id) {
		return $this->db->where('center_id', $center_id)->where('project_id',$this->project_id)->where('user_type','volunteer')->get('User')->result();
	}
	
	function get_users_in_city($city_id) {
		return $this->db->where('city_id', $city_id)->where('project_id',1)->where('user_type','volunteer')->get('User')->result();
	}
	
	function set_user_batch_and_level($user_id, $batch_id, $level_id) {
    	$this->db->insert("UserBatch", array('user_id'=>$user_id, 'batch_id'=>$batch_id, 'level_id'=>$level_id));
    }
    
    function unset_user_batch_and_level($batch_id, $level_id) {
    	$this->db->delete("UserBatch", array('batch_id'=>$batch_id, 'level_id'=>$level_id));
    }
    
    function update_credit($user_id, $credit) {
    	if($credit == 1) $credit = '+1';
    	$this->db->query("UPDATE User SET credit=credit $credit WHERE id=$user_id");
    }
    
    function get_users_batch($user_id) {
    	return $this->db->query("SELECT batch_id FROM UserBatch WHERE user_id=$user_id")->row()->batch_id;
    }
	
	/**
    * Function to searchuser_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function searchuser_details($data)
	{
		$city=$data['city'];
		$group=$data['group'];
		$name=$data['name'];
		
		$this->db->select('User.id,User.name,User.email,User.phone,User.title,User.user_type,Center.name as center_name,
		City.name as city_name');
		$this->db->from('User');
		$this->db->join('Center', 'Center.id = User.center_id' ,'join');
		$this->db->join('City', 'City.id = User.city_id' ,'join');
		$this->db->join('UserGroup', 'UserGroup.user_id = User.id' ,'join');
		$this->db->where('UserGroup.group_id',$group);
		$this->db->where('City.id',$city);
		$this->db->where('user.name',$name);
		$result=$this->db->get();
		return $result;
	
	}
	function searchuser_details_by_grp_city($data)
	{
		$city=$data['city'];
		$group=$data['group'];
		//$this->db->select('user.*,center.name as center_name,city.name as city_name,usergroup.id 
		//as g_id,usergroup.user_id,usergroup.group_id');
		$this->db->select('User.id,user.name,user.email,user.phone,user.title,user.user_type,Center.name as center_name,
		City.name as city_name');
		$this->db->from('user');
		$this->db->join('center', 'center.id = user.center_id' ,'join');
		$this->db->join('city', 'city.id = user.city_id' ,'join');
		$this->db->join('usergroup', 'usergroup.user_id = user.id' ,'join');
		$this->db->where('usergroup.group_id',$group);
		$this->db->where('city.id',$city);
		$result=$this->db->get();
		return $result;
	
	}
	/**
    * Function to search_by_city
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function search_by_city($data)
	{
		$city=$data['city'];
		//$this->db->select('user.*,center.name as center_name,city.name as city_name');
		$this->db->select('User.id,user.name,user.email,user.phone,user.title,user.user_type,Center.name as center_name,
		City.name as city_name');
		$this->db->from('user');
		$this->db->join('center', 'center.id = user.center_id' ,'join');
		$this->db->join('city', 'city.id = user.city_id' ,'join');
		$this->db->where('city.id',$city);
		$result=$this->db->get();
		return $result;
	
	}
	/**
    * Function to searchuser_by_anycity
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function searchuser_by_anycity($data)
	{
	
		$group=$data['group'];
		$this->db->select('User.id,user.name,user.email,user.phone,user.title,user.user_type,Center.name as center_name,
		City.name as city_name');
		$this->db->from('user');
		$this->db->join('center', 'center.id = user.center_id' ,'join');
		$this->db->join('city', 'city.id = user.city_id' ,'join');
		$this->db->join('usergroup', 'usergroup.user_id = user.id' ,'join');
		$this->db->where('usergroup.group_id',$group);
		$result=$this->db->get();
		return $result;
	
	}
	/**
    * Function to searchuser_by_anycity_grp_name
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function searchuser_by_anycity_grp_name($data)
	{
		$group=$data['group'];
		$name=$data['name'];
		//$this->db->select('user.*,center.name as center_name,city.name as city_name,usergroup.id 
		//as g_id,usergroup.user_id,usergroup.group_id');
		$this->db->select('User.id,user.name,user.email,user.phone,user.title,user.user_type,Center.name as center_name,
		City.name as city_name');
		$this->db->from('user');
		$this->db->join('center', 'center.id = user.center_id' ,'join');
		$this->db->join('city', 'city.id = user.city_id' ,'join');
		$this->db->join('usergroup', 'usergroup.user_id = user.id' ,'join');
		$this->db->where('usergroup.group_id',$group);
		$this->db->where('user.name',$name);
		$result=$this->db->get();
		return $result;
	
	}
	/**
    * Function to search_by_city_name
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function search_by_city_name($data)
	{
		$city=$data['city'];
		$name=$data['name'];
		//$this->db->select('user.*,center.name as center_name,city.name as city_name');
		$this->db->select('User.id,user.name,user.email,user.phone,user.title,user.user_type,Center.name as center_name,
		City.name as city_name');
		$this->db->from('user');
		$this->db->join('center', 'center.id = user.center_id' ,'join');
		$this->db->join('city', 'city.id = user.city_id' ,'join');
		$this->db->where('city.id',$city);
		$this->db->where('user.name',$name);
		$result=$this->db->get();
		return $result;
	
	}	
	
	function search_users($data) {
		$this->db->from('User');
		if(!empty($data['project_id'])) $this->db->where('project_id', $data['project_id']);
		else $this->db->where('project_id', $this->project_id);
		if(!empty($data['city_id'])) $this->db->where('city_id', $data['city_id']);
		else $this->db->where('city_id', $this->city_id);
		
		if(!empty($data['center_id'])) $this->db->where('center_id', $data['center_id']);
		if(!empty($data['user_type'])) $this->db->where('user_type', $data['user_type']);
		
		$all_users = $this->db->get()->result();
		$return = array();
		foreach($all_users as $user) {
			// Get the batches for this user. An user can have two batches. That's why I don't do join to get this date.
			$user->batches = colFormat($this->db->where('user_id',$user->id)->get('UserBatch')->result_array()); // :SLOW:
			
			$return[$user->id] = $user;
		}
		return $return;
	}
	
	
	/// Returns all the permissions for the given user as an array.
	function get_user_permissions($user_id) {
		$permissions = $this->db->query("SELECT DISTINCT(Permission.name) FROM Permission 
			INNER JOIN GroupPermission ON GroupPermission.permission_id=Permission.id  
			INNER JOIN UserGroup ON GroupPermission.group_id=UserGroup.group_id 
			WHERE UserGroup.user_id=$user_id")->result();
		
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
        $password  = $data['password'];
        $this->db->select('email');
        $this->db->from('user');
        $this->db->where('email',$email);
        $result=$this->db->get();
        if($result->num_rows() == 0) 
        {
         		
                
                $userdetailsArray = array('name' => $data['firstname'],
                                          'title' => $data['position'],
                                          'email' => $data['email'],
                                          'phone' => $data['mobileno'],
                                          'password' => $data['password'],
                                          'city_id'=>$data['city'],
                                          'center_id'=>$data['center'],
										  'user_type'=>'applicant',
                                          );
                 $this->db->set($userdetailsArray);
                 $this->db->insert('user');
				 $user_id=$this->db->insert_id();
				 
                       	$this->db->select('User.*,Center.name as center_name,City.name as city_name');
						$this->db->from('User');
						$this->db->join('Center', 'Center.id = User.center_id' ,'join');
						$this->db->join('City', 'City.id = User.city_id' ,'join');
						$this->db->where('user.id',$user_id);
						$result=$this->db->get();
						$user=$result->first_row();
						$memberCredentials['id'] = $user->id;
						$memberCredentials['email'] = $user->email;
						$memberCredentials['name'] = $user->name;
						$memberCredentials['permissions'] = $this->get_user_permissions($user->id);
						$memberCredentials['groups'] = $this->get_user_groups($user->id);
						
						return $memberCredentials;
            }
            else
            {
                    return false;
            }
    }
	
	
	
	
}