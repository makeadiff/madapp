<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		MadApp
 * @author		Rabeesh MP
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @license		http://orisysindia.com/licence/brilliant.html
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
	
	function insert_exam_name($name)
	{
		$data = array('name' => $name);
	    $this->db->insert('exam',$data);  
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
	}
	function insert_subject_name($choiceText,$exam_id)
	{
		//echo "ji=".$choiceText;
		$choiceText = explode(",",$choiceText);
			//echo "size=".sizeof($choiceText);	
			
			
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
	function insert_exam_mark($choiceText,$exam_id,$agents)
	{
		$choiceText = explode(",",$choiceText);
		$agents = str_replace("on,","",$agents);
		$agents = substr($agents,0,strlen($agents)-1);
		$explode_agent = explode(",",trim($agents));
		for($i=1;$i<sizeof($explode_agent);$i++)
		{
			$agent=$explode_agent[$i];
			//echo "hi=".$agent;
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
												//print_r($id->result());
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
	
}