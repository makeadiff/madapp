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
class Kids_model extends Model {
    function Kids_model() {
        parent::Model();
        
		$this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
		$this->year = $this->ci->session->userdata('year');
    }
    
    /**
    * Function to getkids_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function getkids_details($city_id = 0) {
		if(!$city_id) $city_id = $this->city_id;
		
		$this->db->select('Student.*,Center.name as center_name');
		$this->db->from('Student');
		$this->db->join('Center', 'Center.id = Student.center_id' ,'join');
		$this->db->where('Center.city_id', $city_id);
		$this->db->where('Center.status', 1);
		$this->db->where('Student.status', 1);
		$this->db->orderby('Student.id');
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
	function add_kids($data) {
		$data = array('center_id' 	=> $data['center'],
					  'name' 	 	=> $data['name'],
					  'birthday'	=> $data['date'],
					  'sex'			=> $data['sex'],
				  	 'description'	=> $data['description'],
				  	 'status'		=> 1,
			   );
		$this->db->insert('Student',$data);
		$kid_id = $this->db->insert_id();
		
	 	return ($this->db->affected_rows() > 0) ? $this->db->insert_id()  : false ;
	}
	
	/**
    * Function to delete_kids
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function delete_kids($id) {
		$this->db->where('id',$id);
		$this->db->update('Student', array('satus' => '0'));
		$affected = $this->db->affected_rows();
		 
		$this->db->where('student_id',$id);
		$this->db->delete('StudentLevel');
		 
		return ($affected) ? true: false;
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
		$this->db->where('Student.status', 1);
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
		$this->db->where('Student.status', 1);
		$this->db->orderby('name');
		$result=$this->db->get();
		return $result;
	
	}
	
	/// Returns the ID and name of the Kids who are NOT assigned to any levels
	function get_free_kids($center_id) {
		$students = $this->db->query("SELECT Student.id,Student.name
			FROM StudentLevel INNER JOIN Level ON Level.id = StudentLevel.level_id AND Level.project_id={$this->project_id}
			RIGHT JOIN Student ON student_id = Student.id
			WHERE student_id IS NULL AND Student.center_id=$center_id AND Student.status='1' ORDER BY Student.name");
		return $students;
	}
	
	
	/**
    * Function to update_student
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function update_student($data) {
		$rootId=$data['rootId'];
		$data = array(	'center_id'	=> $data['center'],
						'name'		=> $data['name'],
						'birthday'	=> $data['date'],
						'sex'		=> $data ['sex'],
						'description'=> $data['description'],
					);
		$this->db->where('id', $rootId);
		$this->db->update('Student', $data);
		
		return ($this->db->affected_rows() > 0) ? 1: 0 ;
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
		$this->db->where('status', 1);
		$this->db->orderby('name');
		$result=$this->db->get();
		return $result;
	
	}

	function get_kidsby_center($center_id)
	{
		$this->db->select('*');
		$this->db->from('Student');
		$this->db->where('center_id',$center_id);
		$this->db->where('status', 1);
		return $this->db->get();
	
	}	
}
