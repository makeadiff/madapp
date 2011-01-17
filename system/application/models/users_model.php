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

class Users_model extends Model
{
	
    function Users_model()
    {
        parent::Model();
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
			$memberR = $query->first_row();
   			$memberCredentials['id'] = $memberR->id;
			$memberCredentials['email'] = $memberR->email;
			$memberCredentials['name'] = $memberR->name;
			
			
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
		$this->db->delete('group');
		
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
				'password'=> $data['password'],
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
    * @return: type: [Boolean, Array()]
    **/
	function updateuser_to_group($data)
	{	
		$rootId=$data['rootId'];
		$user_array=array('group_id'=> $data['group']);
		$this->db->where('user_id', $rootId);
		$this->db->update('UserGroup', $user_array);
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
		return $this->db->where('center_id', $center_id)->where('project_id',1)->where('user_type','volunteer')->get('User')->result();
	}
	
	function set_user_batch_and_level($user_id, $batch_id, $level_id) {
    	$this->db->insert("UserBatch", array('user_id'=>$user_id, 'batch_id'=>$batch_id, 'level_id'=>$level_id));
    }
    
    function unset_user_batch_and_level($batch_id, $level_id) {
    	$this->db->delete("UserBatch", array('batch_id'=>$batch_id, 'level_id'=>$level_id));
    }
	
}