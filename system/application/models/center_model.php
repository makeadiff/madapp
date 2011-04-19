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

class Center_model extends Model
{
    function Center_model()
    {
        parent::Model();
        
		$this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
    }
	
	 /**
    * Function to getcenter_count
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean,int]
    **/
	function getcenter_count()
	{
			$this->db->select('*')->where('Center.city_id',$this->city_id)->from('Center');
			$count = $this->db->get();	
			return count($count->result());
	}
    
	
    /**
    * Function to getcenter_details
    * @author : Rabeesh
    * @param  : [$data]
    * @return : type: [Array()]
    **/
    function getcenter_details()
    {
	
		$this->db->select('Center.*,City.name as city_name,User.name as user_name');
		$this->db->from('Center');
		$this->db->where('Center.city_id',$this->city_id);
		$this->db->join('City', 'City.id = Center.city_id' ,'join');
		$this->db->join('User', 'Center.center_head_id = User.id' ,'left');
		
		$result = $this->db->get()->result();
		
		// Highlight the errors in the center - if any.
		for($i=0; $i<count($result); $i++) {
			$center_id = $result[$i]->id;
			
			$details = $this->find_issues($center_id);
			$result[$i]->problem_count = $details['problem_count'];
			$result[$i]->information = $details['information'];
		}
		
		return $result;	
    }
	 /**
    * Function to getcity
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function getcity()
	{
	$this->db->select('*');
	$this->db->from('City');
	$result=$this->db->get();
	return $result;
	
	}
	 /**
    * Function to getheadname
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getheadname() {
		$this->db->select('*')->where('city_id', $this->city_id)->where('project_id',$this->project_id);
		$result=$this->db->get('User');
		return $result;
	
	}
	 /**
    * Function to add_center
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function add_center($data)
	{
	$data = array(	'city_id' => $data['city'] ,
			 		'name' => $data['center'] ,
					'center_head_id' => $data ['user_id'],
			 	);
						  
	    $this->db->insert('Center',$data);  
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
	
	}
	 /**
    * Function to edit_center
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function edit_center($uid)
	{
	$this->db->select('*');
	$this->db->from('Center');
	$this->db->where('id',$uid);
	$result=$this->db->get();
	return $result;
	}
	 /**
    * Function to update_center
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function update_center($data) {
		$rootId=$data['rootId'];
		$data = array('city_id' => $data['city'] ,
				'name' => $data['center'] ,
				'center_head_id' => $data ['user_id'],
				);
		$this->db->where('id', $rootId);
		$this->db->update('Center', $data);
		return ($this->db->affected_rows() > 0) ? true: false ;
	}
	
	/**
    * Function to delete_center
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function delete_center($data)
	{
		 $id = $data['entry_id'];
		 $this->db->where('id',$id);
		 $this->db->delete('Center');
		 return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/**
    * Function to center_name
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array]
    **/
	function center_name($center_id) {
		$this->db->select('name');
		$this->db->from('Center');
		$this->db->where('id',$center_id);
		$result=$this->db->get();
		return $result;
	
	}
	
	function get_center_name($center_id) {
		$center = $this->db->where('id', $center_id)->get('Center')->row();
		return $center->name;
	}
	
	function get_all() {
		return $this->db->where('city_id',$this->city_id)->orderby('name')->get('Center')->result();
	}
	
	// Find the errors in the center - if any.
	function find_issues($center_id) {
		$teacher_count = 0;
		$problem_flag = 0;
		$information = array();
		
		$center_head_id = $this->db->query("SELECT center_head_id FROM Center WHERE id=$center_id")->row()->center_head_id;
		$level_count = $this->db->query("SELECT COUNT(id) AS level_count FROM Level WHERE center_id=$center_id AND project_id={$this->project_id}")->row()->level_count;
		$batch_count = $this->db->query("SELECT COUNT(id) AS batch_count FROM Batch WHERE center_id=$center_id AND project_id={$this->project_id}")->row()->batch_count;
		$requirement_count = $this->db->query("SELECT SUM(UserBatch.requirement) AS count FROM UserBatch INNER JOIN Batch ON UserBatch.batch_id=Batch.id WHERE Batch.center_id=$center_id AND Batch.project_id={$this->project_id}")->row()->count;
		$kids_count = $this->db->query("SELECT COUNT(id) AS count FROM Student WHERE center_id=$center_id")->row()->count;
		$total_volunteer_count = $this->db->query("SELECT COUNT(id) AS count FROM User WHERE city_id={$this->city_id} AND project_id={$this->project_id}")->row()->count;
		if($total_volunteer_count) {
			$teacher_count = $this->db->query("SELECT COUNT(UserBatch.id) AS count FROM UserBatch INNER JOIN Batch ON UserBatch.batch_id=Batch.id WHERE Batch.center_id=$center_id AND Batch.project_id={$this->project_id}")->row()->count;
		}
		
		if(!$center_head_id) {
			$information[] = "Center does not have a center head. <span class='warning icon'>!</span>";
			$problem_flag++;
		}
		if(!$level_count) {
			$information[] = "No levels added to the center <span class='warning icon'>!</span>";
			$problem_flag++;
		} else {
			$information[] = "Levels in this center: $level_count";
		}
		
		if(!$batch_count) {
			$information[] = "No batches added to the center <span class='warning icon'>!</span>";
			$problem_flag++;
		} else {
			$information[] = "Batch in this center: $batch_count";
		}
		
		if($teacher_count < 30) {
			$information[] = "Too few teachers added to the center <span class='warning icon'>!</span>";
			$problem_flag++;
		} else {
			$information[] = "Teachers in this center: $teacher_count";
		}
		
		if($requirement_count) {
			$information[] = "More volunteers needed for this center <span class='warning icon'>!</span>";
			$problem_flag++;
		}
		
		if($kids_count < 30) {
			$information[] = "Too few kids added to the center <span class='warning icon'>!</span>";
			$problem_flag++;
		} else {
			$information[] = "Kids in this center: $kids_count";
		}
		
		$details = array(
			'center_head_id'	=> $center_head_id,
			'level_count'		=> $level_count,
			'batch_count'		=> $batch_count,
			'teacher_count'		=> $teacher_count,
			'requirement_count'	=> $requirement_count,
			'kids_count'		=> $kids_count,
			'total_volunteer_count'=>$total_volunteer_count,
		);
		
		return array('information'=>$information, 'problem_count'=>$problem_flag, 'details'=>$details);
	}
	
	
	/**
    * Function to getcenter
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array]
    **/
	function getcenter() {
		$this->db->select('*');
		$this->db->from('Center');
		$this->db->where('city_id', $this->city_id);
		$result=$this->db->get();
		return $result;
	}
	
	
}