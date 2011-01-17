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

class Project_model extends Model
{
	
    function Project_model() {
        parent::Model();
    }
    
	function getproject() {
		return $this->db->get('Project');
	}
	function project_count()
	{
	
	}
	function add_project($data)
	{
		$date=date("Y-m-d H:i:s");
		$data_array=array('name'=>$data['name'],'added_on'=>$date);
		$this->db->insert('Project',$data_array);
		return ($this->db->affected_rows() >0)?true: false;
	}
	function get_project_byid($uid)
	{
		$this->db->select('*');
		$this->db->from('Project');
		$this->db->where('id',$uid);
		$result=$this->db->get();
		return $result;
	}
	function update_project($data)
	{
		$rootId=$data['rootId'];
		$date=date("Y-m-d H:i:s");
		$data_array=array('name'=>$data['name'],'added_on'=>$date);
		$this->db->where('id',$rootId);
		$this->db->update('Project',$data_array);
		return ($this->db->affected_rows() >0) ? true: false;
	}
	function delete_project($data)
	{
		echo 	$entry_id=$data['entry_id'];
		$this->db->where('id',$entry_id);
		$this->db->delete('Project');
	}
}	