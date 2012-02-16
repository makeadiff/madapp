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
class Exam_model extends Model
{
    function Exam_model()
    {
        parent::Model();
        
        $this->ci = &get_instance();
   		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
    }
    
	function insert($name, $level, $subjects) {
		$data = array('name' => $name, 'level'=>$level);
	    $this->db->insert('Exam',$data);  
        $exam_id = $this->db->insert_id();
        
        foreach($subjects as $sub) {
			if(!$sub['name']) continue;
			
			$subject_info = array(	'exam_id'  => $exam_id, 
									'name'  => $sub['name'],
									'total_mark'=> $sub['total_mark']);
			$this->db->insert('Exam_Subject', $subject_info);
        }
        
        return $exam_id;
	}
	
	function delete_event($event_id) {
		$this->db->where('exam_event_id', $event_id)->delete("Exam_Mark");
		$this->db->where('id', $event_id)->delete("Exam_Event");
	}
	
	function delete($exam_id) {
		// Remove marks...
		$this->db->where('exam_id', $exam_id)->delete("Exam_Mark");
		
		// Remove Subjects...
		$this->db->where('exam_id', $exam_id)->delete("Exam_Subject");
		
		// Exam events...
		$this->db->where('exam_id', $exam_id)->delete("Exam_Event");
		
		// Remove the exam.
		$this->db->where('id', $exam_id)->delete("Exam");
	}
	
	function get_subject_names($exam_id) {
		$this->db->select('*');
		$this->db->from('Exam_Subject');
		$this->db->where('exam_id',$exam_id);
		$result = $this->db->get();
		return $result;
	}
	
	function insert_exam($exam_id, $center_id, $exam_on) {
		$this->db->insert("Exam_Event", array(
			'exam_id'	=> $exam_id,
			'exam_on'	=> $exam_on,
			'city_id'	=> $this->city_id,
			'center_id'	=> $center_id,
		));
		
		return $this->db->insert_id();
	}
	
	function insert_mark($exam_id, $exam_event_id, $student_id, $subject_id, $mark) {
		$this->db->insert("Exam_Mark", array(
			'student_id'	=> $student_id,
			'exam_id'		=> $exam_id,
			'exam_event_id'	=> $exam_event_id,
			'subject_id'	=> $subject_id,
			'mark'			=> $mark
		));
	}
	
	function get_exam_events($exam_id=0) {
		$this->db->select('Exam.name,Exam_Event.id,Exam.level,Exam_Event.exam_on')->from('Exam_Event')->join("Exam", 'Exam.id=Exam_Event.exam_id')->where('Exam_Event.city_id',$this->city_id);
		if($exam_id) $this->db->where('Exam.id',$exam_id);
		$result = $this->db->get();
		
		if($result) return $result->result();
		return array();
	}
	
	function get_student_attending_exam($event_id) {
		$result = $this->db->select('Student.id, Student.name')->from('Student')->join('Exam_Mark', 'Exam_Mark.student_id=Student.id')->where('Exam_Mark.exam_event_id',$event_id)->get();
		if($result) return $result->result();
		return array();
	}
	
	function get_exam_event_details($event_id) {
		$result = $this->db->select('Exam.name,Exam_Event.id,Exam.level,Exam_Event.exam_on,Exam_Event.center_id,Exam.id as exam_id')
			->from('Exam_Event')->join("Exam", 'Exam.id=Exam_Event.exam_id')->where('Exam_Event.id',$event_id)->get();
		$data = array();
		if($result) $data = $result->row();
		if($data) {
			$result = $this->db->select('id,name,total_mark')->from('Exam_Subject')->where('exam_id',$data->exam_id)->orderby('id')->get();
			$data->subjects = $result->result();
		}
		
		return $data;
	}
	
	function get_marks($event_id) {
		$result = $this->db->select('*')->from('Exam_Mark')->where('exam_event_id',$event_id)->get();
		
		if($result) return $result->result();
		return array();
	}
	
	
	function get_exam_name_by_id($exam_id)
	{
		$this->db->select('*');
		$this->db->from('Exam');
		$this->db->where('id',$exam_id);
		$result=$this->db->get();
		return $result;
	}
	
	function get_all() {
		$result = $this->db->select('*')->from('Exam')->get();
		return $result->result();
	}

}
