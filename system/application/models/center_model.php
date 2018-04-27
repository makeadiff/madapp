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
    
	
    /// Return all centers in given city with information about it.
    function get_all_info($city_id = 0) {
    	if(!$city_id) $city_id = $this->city_id;

		$this->ci->load->model('city_model');
		
		$this->db->select("Center.*, User.name as user_name");
		$this->db->from('Center');
		$this->db->where('Center.city_id',$city_id)->where('Center.status','1');
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
    function getcenter_details() { return $this->get_all(); } // :ALIAS: :DEPRICIATED:


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
			'class_starts_on'=> $data['class_starts_on'],
			'medium'	=> $data['medium'],
			'preferred_gender' => $data['preferred_gender']
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
		
		$new_data = array();
		if(!empty($data['center'])) $new_data['name'] = $data['center'];
		if(!empty($data['user_id'])) $new_data['center_head_id'] = $data['user_id'];
		if(!empty($data['class_starts_on'])) $new_data['class_starts_on'] = $data['class_starts_on'];
		if(!empty($data['medium'])) $new_data['medium'] = $data['medium'];
		if(!empty($data['preferred_gender'])) $new_data['preferred_gender'] = $data['preferred_gender'];

		$this->db->where('id', $center_id);
		$this->db->update('Center', $new_data);
		$affected_rows = ($this->db->affected_rows() > 0) ? true: false ;

		if($affected_rows) { // If center has been update, 
			if($new_data['medium'] != 'english')
				$this->db->where('center_id', $center_id)->where('medium', 'english')->update('Level', ['medium' => $new_data['medium']]);

			if($new_data['preferred_gender'] != 'any')
				$this->db->where('center_id', $center_id)->where('preferred_gender', 'any')->update('Level', ['preferred_gender' => $new_data['preferred_gender']]);
		}
		
		if(!empty($data['center_head_id']) and $data['center_head_id'] > 0) {
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
 		 $this->db->where('center_id',$center_id)->update('Level', array('status'=>'0'));
 		 $this->db->where('center_id',$center_id)->update('Batch', array('status'=>'0'));

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

	function get_center_head_id($center_id) {
		$center = $this->db->where('id', $center_id)->get('Center')->row();
		if($center) return $center->center_head_id;
		return 0;
	}
	
	function get_center_of_head_id($user_id) {
		$center = $this->db->where('center_head_id', $user_id)->get('Center')->row();
		if($center) return $center->id;
		return 0;
	}

	// Get all the centers in the current city
	function get_all($city_id=0) {
		if(!$city_id) $city_id = $this->city_id;
		return $this->db->where('city_id',$city_id)->where('status','1')->orderby('name')->get('Center')->result();
	}

	// Get info on the current center id
	function get_info($center_id) {
		return $this->db->where('id',$center_id)->where('status','1')->orderby('name')->get('Center')->result();
	}
	
	// Get all the centers. No matter what city
	function get_all_centers() {
		return $this->db->where('status','1')->get('Center')->result();
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
		$assigned_student_count = $this->db->query("SELECT COUNT(DISTINCT S.id) AS student_count FROM Student S INNER JOIN StudentLevel SL ON SL.student_id=S.id INNER JOIN Level L ON L.id=SL.level_id WHERE L.year={$this->year} AND SL.level_id!=0 AND S.status='1' AND S.center_id=$center_id")->row()->student_count;

		if($total_volunteer_count) {
			$teacher_count = $this->db->query("SELECT COUNT(UserBatch.id) AS count FROM UserBatch INNER JOIN Batch ON UserBatch.batch_id=Batch.id WHERE Batch.center_id=$center_id AND Batch.project_id={$this->project_id} AND Batch.year={$this->year}")->row()->count;
		}

		if($assigned_student_count < $kids_count) {
			$information[] = "Not all kids are assigned. <span class='warning icon'>!</span>";
			$problem_flag++;
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
			'assigned_student_count' => $assigned_student_count,
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


	function get_center_data($center_id, $name) {
		$result = $this->db->query("SELECT * FROM CenterData WHERE name LIKE '$name' AND center_id=$center_id");
		return $result->result_array();
	}

	function save_center_data($center_id, $name, $data) {
    	$this->db->delete("CenterData", array('center_id'=>$center_id, 'name'=>$name));
    	$data['center_id'] = $center_id;
    	$data['name'] = $name;
    	$this->db->insert("CenterData", $data);
	}
}

