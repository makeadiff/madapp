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
		$this->year = $this->ci->session->userdata('year');
    }
	
	 /**
    * Function to getcenter_count
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean,int]
    **/
	function getcenter_count()
	{
		$this->db->select('*')->where('Center.city_id',$this->city_id)->where('Center.status','1')->from('Center');
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
		$this->ci->load->model('city_model');
		
		$this->db->select("Center.*, User.name as user_name");
		$this->db->from('Center');
		$this->db->where('Center.city_id',$this->city_id)->where('Center.status','1');
		$this->db->join('User', 'Center.center_head_id = User.id' ,'left');
		
		$result = $this->db->get()->result();
		
		$city_name = $this->ci->city_model->getCity($this->city_id);
		// Highlight the errors in the center - if any.
		for($i=0; $i<count($result); $i++) {
			$center_id = $result[$i]->id;
			
			$details = $this->find_issues($center_id);
			$result[$i]->city_name = $city_name['name'];
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
	function getcity() {
		$this->db->select('*');
		$this->db->from('City');
		$result=$this->db->get();
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
		$data = array(
			'city_id' => $data['city'],
			'name' => $data['center'],
			'center_head_id' => $data['user_id'],
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
	function edit_center($uid) {
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
		$center_id = $data['rootId'];
		$center_details = $this->edit_center($center_id)->row();
		if(!$center_details) return false;
		
		$data = array(
				'name' => $data['center'] ,
				'center_head_id' => $data['user_id'],
				);
		$this->db->where('id', $center_id);
		$this->db->update('Center', $data);
		$affected_rows = ($this->db->affected_rows() > 0) ? true: false ;
		
		if($data['center_head_id'] > 0) {
			$this->load->model('users_model');
			$this->users_model->remove_user_from_group($center_details->center_head_id, 7); // Remove the old center head from the group 'Center Head'
			$this->users_model->adduser_to_group($data['center_head_id'], array(7));// Add the center head to Center Head group.
		}
		
		return $affected_rows;
	}
	
	/**
    * Function to delete_center
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function delete_center($center_id)
	{
 		 $this->db->where('id',$center_id)->update('Center', array('status'=>'0'));

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
	
	// Get all the centers in the current city
	function get_all($city_id=0) {
		if(!$city_id) $city_id = $this->city_id;
		return $this->db->where('city_id',$city_id)->where('status','1')->orderby('name')->get('Center')->result();
	}
	
	// Get all the centers. No matter what city
	function get_all_centers() {
		return $this->db->select(array('id','name'))->where('status','1')->get('Center')->result();
	}
	
	// Find the errors in the center - if any.
	function find_issues($center_id) {
		$teacher_count = 0;
		$problem_flag = 0;
		$information = array();
		
		$center_head_id = $this->db->query("SELECT center_head_id FROM Center WHERE id=$center_id")->row()->center_head_id;
		$level_count = $this->db->query("SELECT COUNT(id) AS level_count FROM Level WHERE center_id=$center_id AND project_id={$this->project_id} AND year={$this->year}")->row()->level_count;
		$batch_count = $this->db->query("SELECT COUNT(id) AS batch_count FROM Batch WHERE center_id=$center_id AND project_id={$this->project_id} AND year={$this->year}")->row()->batch_count;
		$requirement_count = $this->db->query("SELECT SUM(UserBatch.requirement) AS count FROM UserBatch INNER JOIN Batch ON UserBatch.batch_id=Batch.id WHERE Batch.center_id=$center_id AND Batch.project_id={$this->project_id} AND Batch.year={$this->year}")->row()->count;
		$kids_count = $this->db->query("SELECT COUNT(id) AS count FROM Student WHERE center_id=$center_id AND status='1'")->row()->count;
		$total_volunteer_count = $this->db->query("SELECT COUNT(id) AS count FROM User WHERE city_id={$this->city_id} AND user_type='volunteer' AND project_id={$this->project_id} AND status='1'")->row()->count;
		if($total_volunteer_count) {
			$teacher_count = $this->db->query("SELECT COUNT(UserBatch.id) AS count FROM UserBatch INNER JOIN Batch ON UserBatch.batch_id=Batch.id WHERE Batch.center_id=$center_id AND Batch.project_id={$this->project_id} AND Batch.year={$this->year}")->row()->count;
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
		
		if($kids_count < 12) {
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
	
	
	function get_exam_centers()
	{
		return $this->db->query("SELECT DISTINCT(Center.name),Center.id FROM Center JOIN Exam_Event ON Center.id = Exam_Event.center_id WHERE Center.city_id={$this->city_id}")->result();
	}
}
