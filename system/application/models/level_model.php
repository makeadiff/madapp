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

class Level_model extends Model {
	function Level_model() {
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
		$this->year = $this->ci->session->userdata('year');
		if(!$this->project_id) $this->project_id = 1;
		if(!$this->year) $this->year = get_year();
		if(!$this->city_id and isset($_SESSION['city_id'])) $this->city_id = $_SESSION['city_id'];
    }

    function get_all_medium() {
    	return $this->db->where('status', '1')->orderby('name')->get("Medium")->result();
    }
    
	function get_all_levels_in_center($center_id) {
		return $this->db->where('center_id',$center_id)->where('year', $this->year)->where('project_id', $this->project_id)->where('status','1')->orderby('grade,name')->get('Level')->result();
	}

	function get_all_level_names_in_center($center_id) {
		return $this->db->query("SELECT id,CONCAT(grade,' ',name) AS name FROM Level 
				WHERE center_id='$center_id' AND year='{$this->year}' AND project_id='{$this->project_id}' AND status='1' 
				ORDER BY grade,name")->result();
	}

	function get_all_level_names_in_center_and_batch($center_id, $batch_id) {
		// $center_id is not used anymore.
		return $this->db->query("SELECT L.id,CONCAT(grade,' ',name) AS name FROM Level L
				INNER JOIN BatchLevel BL ON L.id=BL.level_id
				WHERE L.year='{$this->year}' AND L.project_id='{$this->project_id}' AND status='1' AND BL.batch_id=$batch_id
				ORDER BY grade,name")->result();
	}
	
	function get_level($level_id) {
		return $this->db->where('id', $level_id)->get('Level')->row();
	}
	
	function get_level_details($level_id) {
    	return $this->db->query("SELECT Center.name AS center_name, Level.grade, Level.name 
    		FROM Level INNER JOIN Center ON Center.id=Level.center_id 
    		WHERE Level.id=$level_id")->row();
    }
    
    function get_kids_in_level($level_id) {
    	$students = $this->db->query("SELECT Student.id,Student.name FROM Student 
    		INNER JOIN StudentLevel ON StudentLevel.student_id=Student.id 
    		WHERE StudentLevel.level_id=$level_id ORDER BY Student.name")->result();
    	
    	$students_ids = array();
    	foreach($students as $student) $students_ids[$student->id] = $student->name;
    	return $students_ids;
    }
    
    function create($data) {
		$this->db->insert('Level', [
				'name'		=>	$data['name'],
				'center_id'	=>	$data['center_id'],
				'medium'	=>	$data['medium'],
				'preferred_gender'	=>	$data['preferred_gender'],
				'grade'		=>  $data['grade'],
				'year'		=> 	$this->year,
				'project_id'=>	$this->project_id,
			]);
			
		$level_id = $this->db->insert_id();
		$this->db->delete("StudentLevel", array('level_id'=>$level_id));
		$selected_students = $data['students'];
		if($selected_students) {
			foreach($selected_students as $student_id) {
				$this->db->insert("StudentLevel", array(
					'level_id'	=> $level_id,
					'student_id'=> $student_id
				));
			}
		}
    }
    
    function edit($level_id, $data) {
		$this->db->where('id', $level_id)->update('Level', 
			array(
				'name'		=>	$data['name'],
				'center_id'	=>	$data['center_id'],
				'medium'	=>	$data['medium'],
				'preferred_gender'	=>	$data['preferred_gender'],
				'grade'		=>  $data['grade'],
			));
			
		$this->db->delete("StudentLevel", array('level_id'=>$level_id));
		$selected_students = $data['students'];
		foreach($selected_students as $student_id) {
			$this->db->insert("StudentLevel", array(
				'level_id'	=> $level_id,
				'student_id'=> $student_id
			));
		}
    }

    function save_student_level_mapping($student_id, $level_id) {
    	if($student_id == 0) $this->clear_existing_mapping(array('level_id'=>$level_id));
    	else if($level_id == 0) $this->clear_existing_mapping(array('student_id'=>$student_id));
    	else {
    		$this->clear_existing_mapping(array('student_id'=>$student_id));

    		$this->db->insert("StudentLevel", array(
    			'student_id'=>$student_id, 
    			'level_id' => $level_id
    		));
    		$insert_id = $this->db->insert_id();
    	}
    }

    /**
     * This is to make sure that mapping from other years are not deleted. Make sure only the current year or the given year mappings are deleted.
     */
    function clear_existing_mapping($mapping, $year = 0) {
    	if(!$year) $year = $this->year;
    	extract($mapping);
    	
    	if(isset($student_id)) {
	    	$levels = $this->db->query("SELECT level_id FROM StudentLevel SL 
	    		INNER JOIN Level L ON L.id=SL.level_id 
	    		WHERE SL.student_id=$student_id AND L.year=$year AND L.project_id={$this->project_id}")->result();

	    	$levels_associated_with_student = array();
	    	foreach ($levels as $level_info) {
	    		$level_id = $level_info->level_id;
	    		print "Deleting - Level: $level_id, Student: $student_id<br />";
	    		$this->db->query("DELETE FROM StudentLevel WHERE level_id=$level_id AND student_id=$student_id");
	    	}

	    } elseif(isset($level_id)) { // Clears the mapping for an entire level. 
	    	if(! $this->db->query("SELECT id FROM Level WHERE id=$level_id AND year=$year AND project_id={$this->project_id}")->row()) return; // If the given level_id is not of the said year, return.
	    	$this->db->query("DELETE FROM StudentLevel WHERE level_id=$level_id");
	    }
    }
	
	/// Returs all the ids of the levels the given user teachs at.
	function get_user_level($user_id) {
		$levels = $this->db->query("SELECT level_id 
			FROM UserBatch UB
			INNER JOIN Level L ON UB.level_id=L.id
			WHERE user_id=$user_id AND L.year={$this->year} AND L.project_id={$this->project_id}")->result();
		$return = array();
		foreach($levels as $l) $return[] = $l->level_id;
		
		return $return;
	}
	
	/// Returns the id of the level of the given class...
	function get_class_level($class_id) {
		$level = $this->db->query("SELECT level_id FROM Class WHERE id=$class_id")->row();
		return $level->level_id;
	}

	function get_student_level_mapping($center_id) {
		return $this->db->query("SELECT SL.student_id, SL.level_id FROM StudentLevel SL 
				INNER JOIN Level L ON L.id=SL.level_id 
				WHERE L.year={$this->year} AND L.project_id={$this->project_id} AND L.center_id=$center_id AND L.status='1'")->result();
	}

	function get_all_kids_in_level($level_id) {
		return $this->db->query("SELECT COUNT(id) AS count FROM StudentLevel WHERE level_id=$level_id")->row()->count;
	}

	function get_all_kidsname_in_level($level_id) {
		return $this->db->query("SELECT Student.id,Student.name FROM Student 
			JOIN StudentLevel ON StudentLevel.student_id = Student.id WHERE StudentLevel.level_id=$level_id")->result();
	}
	
	function get_only_levels_in_center($center_id) {
		return $this->db->query("SELECT DISTINCT(Level.id), Level.name FROM Level 
			JOIN Exam_Event ON Level.id = Exam_Event.level_id 
			WHERE Exam_Event.center_id=$center_id AND Level.year={$this->year} AND project_id='{$this->project_id}'")->result();
	}
}
