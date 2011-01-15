<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		MadApp
 * @author		Rabeesh MP
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */
class Permission_model extends Model
{
    function Permission_model()
    {
        parent::Model();
    }
	function permisssion_count()
	{
	
	
	}
	/**
    * Function to getpermission_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getpermission_details()
	{
		$this->db->select('*');
		$this->db->from('permission');
		$result=$this->db->get();
		return $result;
	
	}
	/**
    * Function to add_permission
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, int]
    **/
	function add_permission($permission)
	{
		$data = array('name'=> $permission);
		$this->db->insert('permission',$data);
		return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/**
    * Function to getedit_permission
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getedit_permission($uid)
	{
		$this->db->select('*');
		$this->db->from('permission');
		$this->db->where('id',$uid);
		$result=$this->db->get();
		return $result;
	
	}
	/**
    * Function to update_permission
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, int]
    **/
	function update_permission($data)
	{
		$rootId=$data['rootId'];
		$data = array('name'=> $data['permission']);
		$this->db->where('id',$rootId);
		$this->db->update('permission',$data);
		return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/**
    * Function to delete_permission
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, int]
    **/
	function delete_permission($data)
	{
		$id = $data['entry_id'];
		$this->db->where('id',$id);
		$this->db->delete('permission');
		return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/**
    * Function to getgroup_permission_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getgroup_permission_details($uid)
	{
		$this->db->select('permission_id');
		$this->db->from('grouppermission');
		$this->db->where('group_id',$uid);
		$result=$this->db->get();
		return $result;
	}
	
}	