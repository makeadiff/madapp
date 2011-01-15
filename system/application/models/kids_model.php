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

		$this->db->select('student.*,center.name as center_name');
		$this->db->from('student');
		$this->db->join('center', 'center.id = student.center_id' ,'join');
		$result=$this->db->get();
		return $result;
	}
	function kids_count()
	{
	
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
					  'name' 	 => $data ['name'],
					  'birthday' => $data ['date'],
				   'description' => $data ['description'],
			 		  );
		$this->db->insert('Student',$data);
		$kid_id = $this->db->insert_id();
		
		//$this->db->insert('StudentLevel', array(
				//'student_id'	=> $kid_id,
				//'level_id'		=> $data['level'],
			//));
		
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
		 
		 $this->db->where('student_id',$id);
		 $this->db->delete('StudentLevel');
		 
		 
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
					  'name' 	 => $data ['name'],
					  'birthday' => $data ['date'],
				   'description' => $data ['description'],
			 		  );
			 $this->db->where('id', $rootId);
			 $this->db->update('Student', $data);
			 
			 
			 //$this->db->where('student_id',$rootId);
			 //$this->db->update('StudentLevel', array('level_id'=>$data['level']));
			 
	 		 return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/**
    * Function to kids_level_update
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function kids_level_update($student_id,$level)
	{
		$this->db->where('student_id',$student_id);
		$this->db->update('StudentLevel', array('level_id'=>$level));
	}
	/**
    * Function to getkids_name_incenter
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,Array() ]
    **/
	function getkids_name_incenter($uid)
	{
		$this->db->select('id,name');
		$this->db->from('Student');
		$this->db->where('center_id',$uid);
		$result=$this->db->get();
		return $result;
	
	}
	
	
}