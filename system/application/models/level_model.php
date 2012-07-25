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
    }
    /*
     * Function Name : get_all_levels_in_center()
     * Wroking :This function used for getting all levels in the center.
     * @author:
     * @param :[$uid]
     * @return: type: [array]
     */
	function get_all_levels_in_center($center_id) {
		return $this->db->where('project_id', $this->project_id)->where('center_id',$center_id)->where('year', $this->year)->get('Level')->result();
	}
	/*
     * Function Name : get_level()
     * Wroking :This function used for getting level.
     * @author:
     * @param :[$level_id]
     * @return: type: [array]
     */
	function get_level($level_id) {
		return $this->db->where('id', $level_id)->get('Level')->row();
	}
	/*
     * Function Name : get_level_details()
     * Wroking :This function used for getting level details for given level.
     * @author:
     * @param :[$level_id]
     * @return: type: [array]
     */
	function get_level_details($level_id) {
    	return $this->db->query("SELECT Center.name AS center_name, Level.name 
    		FROM Level INNER JOIN Center ON Center.id=Level.center_id 
    		WHERE Level.id=$level_id")->row();
    }
    /*
     * Function Name : get_kids_in_level()
     * Wroking :This function used for getting all kids in a level.
     * @author:
     * @param :[$level_id]
     * @return: type: [array]
     */
    function get_kids_in_level($level_id) {
    	$students = $this->db->query("SELECT Student.id,Student.name FROM Student 
    		INNER JOIN StudentLevel ON StudentLevel.student_id=Student.id 
    		WHERE StudentLevel.level_id=$level_id ORDER BY Student.name")->result();
    	
    	$students_ids = array();
    	foreach($students as $student) $students_ids[$student->id] = $student->name;
    	return $students_ids;
    }
    /*
     * Function Name : create()
     * Wroking :This function used for saving level details
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [array]
     */
    function create($data) {
		$this->db->insert('Level', 
			array(
				'name'		=>	$data['name'],
				'center_id'	=>	$data['center_id'],
				'project_id'=>	$data['project_id'],
				'book_id'	=>	$data['book_id'],
				'year'		=> 	$this->year
			));
			
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
    /*
     * Function Name : edit()
     * Wroking :This function used for updating level details
     * @author:Rabeesh
     * @param :[$level_id, $data]
     * @return: type: [array]
     */
    function edit($level_id, $data) {
		$this->db->where('id', $level_id)->update('Level', 
			array(
				'name'		=>	$data['name'],
				'center_id'	=>	$data['center_id'],
				'project_id'=>	$data['project_id'],
				'book_id'	=>	$data['book_id'],
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
	/*
     * Function Name : get_user_level()
     * Wroking :Returs all the ids of the levels the given user teachs at.
     * @author:
     * @param :[$user_id]
     * @return: type: [array]
     */
	
	function get_user_level($user_id) {
		$levels = $this->db->query("SELECT level_id FROM UserBatch WHERE user_id=$user_id")->result();
		$return = array();
		foreach($levels as $l) $return[] = $l->level_id;
		
		return $return;
	}
	/*
     * Function Name : get_class_level()
     * Wroking :Returns the id of the level of the given class...
     * @author:
     * @param :[$class_id]
     * @return: type: [array]
     */

	function get_class_level($class_id) {
		$level = $this->db->query("SELECT level_id FROM Class WHERE id=$class_id")->row();
		return $level->level_id;
	}
/*
     * Function Name : get_all_kids_in_level()
     * Wroking :Getting all the kids in a level
     * @author:
     * @param :[$level_id]
     * @return: type: [array]
     */
	function get_all_kids_in_level($level_id) {
		return $this->db->query("SELECT COUNT(id) AS count FROM StudentLevel WHERE level_id=$level_id")->row()->count;
	}
/*
     * Function Name : get_all_kidsname_in_level()
     * Wroking :Getting all the kids name in a level
     * @author:
     * @param :[$level_id]
     * @return: type: [array]
     */
	function get_all_kidsname_in_level($level_id) {
		return $this->db->query("SELECT Student.id,Student.name FROM Student JOIN 
			StudentLevel ON StudentLevel.student_id = Student.id WHERE StudentLevel.level_id=$level_id")->result();
	}
	/*
     * Function Name : get_only_levels_in_center()
     * Wroking :Getting levels only from the current center.
     * @author:
     * @param :[$center_id]
     * @return: type: [array]
     */
	function get_only_levels_in_center($center_id) {

		return $this->db->query("SELECT DISTINCT(Level.id), Level.name FROM Level JOIN 
			Exam_Event ON Level.id = Exam_Event.level_id WHERE Exam_Event.center_id=$center_id AND Level.year={$this->year}")->result();

	}
}
