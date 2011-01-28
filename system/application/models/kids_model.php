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
class Kids_model extends Model
{
	
    function Kids_model()
    {
        parent::Model();
		
    }
    /**
    * Function to getkids_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function getkids_details()
	{

		$this->db->select('Student.*,Center.name as center_name');
		$this->db->from('Student');
		$this->db->join('Center', 'Center.id = Student.center_id' ,'join');
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
	function add_kids($data)
	{
		$data = array('center_id' 	 => $data['center'],
					  'name' 	 => $data ['name'],
					  'birthday' => $data ['date'],
				   'description' => $data ['description'],
			 		  );
		$this->db->insert('Student',$data);
		$kid_id = $this->db->insert_id();
		
		//$this->db->insert('StudentLevel', array(
				//'Student_id'	=> $kid_id,
				//'level_id'		=> $data['level'],
			//));
		
	 	return ($this->db->affected_rows() > 0) ? $this->db->insert_id()  : false ;
	
	}
	/**
    * Function to delete_kids
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean]
    **/
	function delete_kids($data)
	{
		 $id = $data['entry_id'];
		 $this->db->where('id',$id);
		 $this->db->delete('Student');
		 
		 $this->db->where('student_id',$id);
		 $this->db->delete('StudentLevel');
		 
		 
		 return ($this->db->affected_rows() > 0) ? true: false;
	
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
		$result=$this->db->get();
		return $result;
	
	}
	/**
    * Function to update_student
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function update_student($data)
	{
			$rootId=$data['rootId'];
			$data = array('center_id' 	 => $data['center'],
					  'name' 	 => $data ['name'],
					  'birthday' => $data ['date'],
				   'description' => $data ['description'],
			 		  );
			 $this->db->where('id', $rootId);
			 $this->db->update('Student', $data);
			 
			 
			 //$this->db->where('student_id',$rootId);
			 //$this->db->update('StudentLevel', array('level_id'=>$data['level']));
			 
	 		 return ($this->db->affected_rows() > 0) ? true: false ;
	
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
		$result=$this->db->get();
		return $result;
	
	}
	
	function generate_code($length = 10)
	{
    
		$this->load->library('image_lib');
                if ($length <= 0)
                {
                    return false;
                }
            
                $code = "";
                $chars = "abcdefghijklmnpqrstuvwxyzABCDEFGHIJKLMNPQRSTUVWXYZ123456789";
                srand((double)microtime() * 1000000);
                for ($i = 0; $i < $length; $i++)
                {
                    $code = $code . substr($chars, rand() % strlen($chars), 1);
                }
                return $code;
            
                }
 
    
	
	
	
	function process_pic($data)
    {   
       $id=$data['id'];
        //Get File Data Info
        $uploads = array($this->upload->data());
        $this->load->library('image_lib');
        //Move Files To User Folder
        foreach($uploads as $key[] => $value)
        {
            //Gen Random code for new file name
            $randomcode = $this->generate_code(12);
            $newimagename = $randomcode.$value['file_ext'];
			rename($value['full_path'],'pictures/'.$newimagename);
			$this->load->library('imageResize');
            $nwidth='100';
	        $nheight='90';
			$fileSavePath='C:/xampp/htdocs/maddaplycation/madapp/trunk/pictures/'.$newimagename;
			imagejpeg(imageResize::Resize($fileSavePath,$nwidth,$nheight),$fileSavePath);
			
			
			
            $imagename = $newimagename;
            $thumbnail = $randomcode.'_tn'.$value['file_ext'];
            $this->db->set('photo', $imagename);
            $this->db->set('thumbnail', $thumbnail);
			$this->db->where('id',$id);
            $this->db->update('student');
			return ($this->db->affected_rows() > 0) ? true: false ;

        }
 	}       
	
	
}