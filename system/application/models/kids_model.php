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
    }
    
     /*
     * Function Name : getkids_details()
     * Wroking :This function used for return all kids details of current city
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
	function getkids_details($city_id = 0) {
		if(!$city_id) $city_id = $this->city_id;
		
		$this->db->select('Student.*,Center.name as center_name');
		$this->db->from('Student');
		$this->db->join('Center', 'Center.id = Student.center_id' ,'join');
		$this->db->where('Center.city_id', $city_id);
		$this->db->orderby('Student.id');
		$result=$this->db->get();
		return $result;
	}
	
	function kids_count()
	{
	
	}
	
	/*
     * Function Name : add_kids()
     * Wroking :This function used for add kids
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [array]
     */
	function add_kids($data) {
		$data = array('center_id' 	=> $data['center'],
					  'name' 	 	=> $data ['name'],
					  'birthday'	=> $data ['date'],
				  	 'description'	=> $data ['description'],
			   );
		$this->db->insert('Student',$data);
		$kid_id = $this->db->insert_id();
		
	 	return ($this->db->affected_rows() > 0) ? $this->db->insert_id()  : false ;
	}
	
	/*
     * Function Name : delete_kids()
     * Wroking :This function used for delete kids
     * @author:Rabeesh
     * @param :[$id]
     * @return: type: [array]
     */
	function delete_kids($id) {
		 $this->db->where('id',$id);
		 $this->db->delete('Student');
		 $affected = $this->db->affected_rows();
		 
		 $this->db->where('student_id',$id);
		 $this->db->delete('StudentLevel');
		 
		 return ($affected) ? true: false;
	}
	/*
     * Function Name : delete_kids()
     * Wroking :This function used for return kids details for given kid
     * @author:Rabeesh
     * @param :[$id]
     * @return: type: [array]
     */
	function get_kids_details($uid)
	{
		$this->db->select('*');
		$this->db->from('Student');
		$this->db->where('id',$uid);
		$result=$this->db->get();
		return $result;
	
	}
	/*
     * Function Name : get_kids_name()
     * Wroking :This function used for return kids name for given kid
     * @author:Rabeesh
     * @param :[$id]
     * @return: type: [array]
     */
	function get_kids_name($uid)
	{
		$this->db->select('id,name');
		$this->db->from('Student');
		$this->db->where('center_id',$uid);
		$this->db->orderby('name');
		$result=$this->db->get();
		return $result;
	
	}
	/*
     * Function Name : get_free_kids()
     * Wroking :Returns the ID and name of the Kids who are NOT assigned to any levels
     * @author:Rabeesh
     * @param :[$center_id]
     * @return: type: [array]
     */

	function get_free_kids($center_id) {
		$students = $this->db->query("SELECT Student.id,Student.name
			FROM StudentLevel INNER JOIN Level ON Level.id = StudentLevel.level_id AND Level.project_id={$this->project_id}
			RIGHT JOIN Student ON student_id = Student.id
			WHERE student_id IS NULL AND Student.center_id=$center_id ORDER BY Student.name");
		return $students;
	}
	
	/*
     * Function Name : update_student()
     * Wroking :This function used for update student details.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [array]
     */
	function update_student($data) {
		$rootId=$data['rootId'];
		$data = array(	'center_id'	=> $data['center'],
						'name'		=> $data['name'],
						'birthday'	=> $data['date'],
						'description'=> $data['description'],
					);
		$this->db->where('id', $rootId);
		$this->db->update('Student', $data);
		
		return ($this->db->affected_rows() > 0) ? 1: 0 ;
	}
	/*
     * Function Name : kids_level_update()
     * Wroking :This function used for update student level details.
     * @author:Rabeesh
     * @param :[$student_id,$level]
     * @return: type: [array]
     */
	function kids_level_update($student_id,$level)
	{
		$this->db->where('student_id',$student_id);
		$this->db->update('StudentLevel', array('level_id'=>$level));
	}
	/*
     * Function Name : getkids_name_incenter()
     * Wroking :This function used for getting kids name
     * @author:Rabeesh
     * @param :[$uid]
     * @return: type: [array]
     */
	function getkids_name_incenter($uid)
	{
		$this->db->select('id,name');
		$this->db->from('Student');
		$this->db->where('center_id',$uid);
		$result=$this->db->get();
		return $result;
	
	}
        /*
     * Function Name : get_kidsby_center()
     * Wroking :This function used for getting all kids by center.
     * @author:Rabeesh
     * @param :[$uid]
     * @return: type: [array]
     */
	function get_kidsby_center($center_id)
	{
		$this->db->select('*');
		$this->db->from('Student');
		$this->db->where('center_id',$center_id);
		return $this->db->get();
	
	}
	
	
}