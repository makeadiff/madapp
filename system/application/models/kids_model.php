<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @license		http://orisysindia.com/licence/brilliant.html
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */

class Kids_model extends Model
{
	
    function Kids_model()
    {
        parent::Model();
    }
    /**
    * Function to getkids_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	
	function getkids_details()
	{
		$this->db->select('Student.*,Center.name as center_name,Level.name as lavel_name');
		$this->db->from('Student');
		$this->db->join('Center', 'Center.id = Student.center_id' ,'join');
		$this->db->join('StudentLevel', 'StudentLevel.student_id = Student.id' ,'join');
		$this->db->join('Level', 'Level.id = StudentLevel.level_id' ,'join');
		$result=$this->db->get();
		return $result;
		
	
	}
	/**
    * Function to add_kids
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function add_kids($data)
	{
		$data = array('center_id' 	 => $data['center'],
			 		  'level_id' => $data['level'],
					  'name' 	 => $data ['name'],
					  'birthday' => $data ['date'],
				   'description' => $data ['description'],
			 		  );
		$this->db->insert('Student',$data);
	 	return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/**
    * Function to delete_kids
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function delete_kids($data)
	{
		 $id = $data['entry_id'];
		 $this->db->where('id',$id);
		 $this->db->delete('Student');
		 return ($this->db->affected_rows() > 0) ? true: false;
	
	}
	/**
    * Function to get_kids_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function get_kids_details($uid)
	{
		$this->db->select('*');
		$this->db->from('Student');
		$this->db->where('id',$uid);
		$result=$this->db->get();
		return $result;
	
	}
	/**
    * Function to get_kids_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function get_kids_name($uid)
	{
		$this->db->select('id,name');
		$this->db->from('Student');
		$this->db->where('center_id',$uid);
		$this->db->where('level_id',0);
		$result=$this->db->get();
		return $result;
	
	}
	/**
    * Function to update_student
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function update_student($data)
	{
			$rootId=$data['rootId'];
			$data = array('center_id' 	 => $data['center'],
			 		  'level_id' => $data['level'],
					  'name' 	 => $data ['name'],
					  'birthday' => $data ['date'],
				   'description' => $data ['description'],
			 		  );
			 $this->db->where('id', $rootId);
			 $this->db->update('Student', $data);
	 		 return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/**
    * Function to kids_level_update
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function kids_level_update($agent_id,$level)
	{
		$data = array('level_id' => $level);
		$this->db->where('id', $agent_id);
		$this->db->update('Student', $data);
	}
	function getkids_name_incenter($uid)
	{
		$this->db->select('id,name');
		$this->db->from('Student');
		$this->db->where('center_id',$uid);
		$result=$this->db->get();
		return $result;
	
	}
	
}