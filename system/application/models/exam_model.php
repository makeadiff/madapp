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
    }
	/**
    *
    * Function to insert_exam_name
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function insert_exam_name($name)
	{
		$data = array('name' => $name);
	    $this->db->insert('exam',$data);  
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
	}
	/**
    *
    * Function to insert_subject_name
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function insert_subject_name($choiceText,$exam_id)
	{
		$choiceText = explode(",",$choiceText);
		for($i=0;$i<sizeof($choiceText);$i++)
		   {
		   		if($choiceText[$i] != 'nil')
				  {
				  		$choiceList = array('exam_id'  => $exam_id,
                      					   			'name'  => $choiceText[$i],
                      								                  
                   					 );
				   
						$this->db->set($choiceList);
						$this->db->insert('exam_subject');
				  }
		   }
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
	
	}
	/**
    *
    * Function to insert_exam_mark
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function insert_exam_mark($choiceText,$exam_id,$agents)
	{
		$choiceText = explode(",",$choiceText);
		$agents = str_replace("on,","",$agents);
		$agents = substr($agents,0,strlen($agents)-1);
		$explode_agent = explode(",",trim($agents));
		for($i=1;$i<sizeof($explode_agent);$i++)
		{
			$agent=$explode_agent[$i];
									for($j=0;$j<sizeof($choiceText);$j++)
								   	{
										if($choiceText[$j] != 'nil')
										  {
												$name=$choiceText[$j];
												$this->db->select('id');
												$this->db->from('exam_subject');
												$this->db->where('name',$name);
												$id=$this->db->get();
												$ids=$id->result_array();
													foreach($ids as $row)
													{
													$id=$row['id'];
													}
												$choiceList = array('exam_id'  => $exam_id,
																		'student_id'=>$agent,
																			'subject_id'  => $id,
															 		);
												$this->db->set($choiceList);
												$this->db->insert('exam_mark');
								 		}
								 } 
					
							   
		 
		} 
		return ($this->db->affected_rows() > 0) ? true : false;
		
		  
	}
	function exam_count()
	{
	}
	
	/**
    *
    * Function to get_exam
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get_exam()
	{
		$this->db->select('*');
		$this->db->from('exam');
		$result=$this->db->get();
		return $result;
	
	}
	function get_exam_name_by_id($exam_id)
	{
		$this->db->select('*');
		$this->db->from('exam');
		$this->db->where('id',$exam_id);
		$result=$this->db->get();
		return $result;
	
	}
	function get_exam_details($exam_id)
	{
		$this->db->select('student.name');
		$this->db->from('exam_mark');
		$this->db->distinct('student.name');
		$this->db->join('student', 'student.id = exam_mark.student_id' ,'join');
		$this->db->where('exam_mark.exam_id',$exam_id);
		$result=$this->db->get();
		//print_r($result->result());
		return $result;
	
	
	
	}
	/**
    *
    * Function to get_subject_names
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get_subject_names($exam_id)
	{
	$this->db->select('*');
	$this->db->from('exam_subject');
	$this->db->where('exam_id',$exam_id);
	$result=$this->db->get();
	return $result;
	}
	/**
    *
    * Function to get_mark_details
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get_mark_details($exam_id,$student_id)
	{
		
		$this->db->select('exam_mark.*,exam_subject.name');
		$this->db->from('exam_mark');
		$this->db->join('exam_subject', 'exam_subject.id = exam_mark.subject_id' ,'join');
		$this->db->where('exam_mark.student_id',$student_id);
		$this->db->where('exam_mark.exam_id',$exam_id);
		$result=$this->db->get();
		return $result;
	}
	/**
    *
    * Function to get_student_names
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get_student_names($exam_id)
	{
		$this->db->select('student.*,exam_mark.student_id');
		$this->db->from('exam_mark');
		$this->db->distinct('exam_mark.student_id');
		$this->db->join('student', 'student.id = exam_mark.student_id' ,'join');
		$this->db->where('exam_mark.exam_id',$exam_id);
		$result=$this->db->get();
		return $result;
	}
	/**
    *
    * Function to store_marks
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function store_marks($data)
	{
	$student=$data['student'];
	$mark=$data['marks'];
				$subject=$data['subject'];
				$exam_id=$data['exam_id'];
		 $data = array( 'mark' => $data['marks']);
		 $this->db->where('student_id',$student);
		 $this->db->where('subject_id',$subject);
		 $this->db->where('exam_id',$exam_id);	
	     $this->db->update('exam_mark',$data);  
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
	}
}