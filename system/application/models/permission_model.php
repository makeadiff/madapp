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
	/*
     * Function Name : getpermission_details()
     * Wroking :This function used for return all the permission.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
	function getpermission_details()
	{
		$this->db->select('*');
		$this->db->from('Permission');
		$this->db->orderby('name');
		$result=$this->db->get();
		return $result;
	
	}
	/*
     * Function Name : add_permission()
     * Wroking :This function used for save permission.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
	function add_permission($permission)
	{
		$data = array('name'=> $permission);
		$this->db->insert('Permission',$data);
		return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/*
     * Function Name : getedit_permission()
     * Wroking :This function used for getting  permissions.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
	function getedit_permission($uid)
	{
		$this->db->select('*');
		$this->db->from('Permission');
		$this->db->where('id',$uid);
		$result=$this->db->get();
		return $result;
	
	}
	/*
     * Function Name : update_permission()
     * Wroking :This function used for updating  permissions.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
	function update_permission($data)
	{
		$rootId=$data['rootId'];
		$data = array('name'=> $data['permission']);
		$this->db->where('id',$rootId);
		$this->db->update('Permission',$data);
		return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/*
     * Function Name : delete_permission()
     * Wroking :This function used for deleting  permissions.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
	function delete_permission($data)
	{
		$id = $data['entry_id'];
		$this->db->where('id',$id);
		$this->db->delete('Permission');
		return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/*
     * Function Name : getgroup_permission_details()
     * Wroking :This function used for getting all group  permissions.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
	function getgroup_permission_details($uid)
	{
		$this->db->select('permission_id');
		$this->db->from('GroupPermission');
		$this->db->where('group_id',$uid);
		$result=$this->db->get();
		return $result;
	}
	
}	