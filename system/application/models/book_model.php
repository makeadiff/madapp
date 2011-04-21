<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 
 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */
 
 class Book_model extends Model {
 
 		function Book_model()
		{
 			parent::Model();
        	$this->ci = &get_instance();
        	$this->city_id = $this->ci->session->userdata('city_id');
       	 	$this->project_id = $this->ci->session->userdata('project_id');
		}
		/**
    * Function to getbook_count
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function getbook_count()
		{
			$this->db->select('*');
			$this->db->from('book');
			$count = $this->db->get();	
			return count($count->result());
		}
		/**
    * Function to getbook_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function getbook_details()
		{
			$this->db->select('*');
			$this->db->from('book');
			$result=$this->db->get();
			return $result;
		}
		/**
    * Function to add_book
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function add_book($data)
		{
			$array_details=array('name'=>$data['bookname']);
			$this->db->insert('book',$array_details);
			return ($this->db->affected_rows() > 0) ? true : false;
		}
		/**
    * Function to getbook_name
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function getbook_name($uid)
		{
			$this->db->select('*');
			$this->db->from('book');
			$this->db->where('id',$uid);
			$result=$this->db->get();
			return $result;
			
		}
		/**
    * Function to update_bookname
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function update_bookname($data)
		{
			$root_id=$data['root_id'];
			$array_details=array('name'=>$data['bookname']);
			$this->db->where('id',$root_id);
			$this->db->update('book',$array_details);
			return ($this->db->affected_rows() > 0) ? true : false;
		
		}
		/**
    * Function to delete_bookname
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function delete_bookname($data)
		{
			$id = $data['book_id'];
			$this->db->where('id',$id);
			$this->db->delete('book');
			return ($this->db->affected_rows() > 0) ? true: false ;
		}
		/**
    * Function to getchpater_count
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function getchpater_count()
		{
			$this->db->select('*');
			$this->db->from('lesson');
			$count = $this->db->get();	
			return count($count->result());
		
		}
		/**
    * Function to getlesson_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function getlesson_details()
		{
			$this->db->select('book.name as book_name,lesson.*');
			$this->db->from('lesson');
			$this->db->join('book','book.id=lesson.book_id','join');
			return $this->db->get();
		
		}
		/**
    * Function to add_lesson
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function add_lesson($data)
		{
			$array_details=array('book_id'=>$data['book'],
								'name'=>$data['lessonname'],
							);
			$this->db->insert('lesson',$array_details);
			return ($this->db->affected_rows() > 0) ? true : false;
		
		}
		/**
    * Function to getlesson_name
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function getlesson_name($uid)
		{
			$this->db->select('book.name as book_name,book.id as book_id,lesson.*');
			$this->db->from('lesson');
			$this->db->join('book','book.id=lesson.book_id','join');
			$this->db->where('lesson.id',$uid);
			return $this->db->get();
		
		}
		/**
    * Function to update_lesson
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function update_lesson($data)
		{
			$root_id=$data['rootId'];
			$array_details=array('name' =>$data['lessonname'],
								'book_id' =>$data['book_id']);
			$this->db->where('id',$root_id);
			$this->db->update('lesson',$array_details);
			return ($this->db->affected_rows() > 0) ? true:false;
		
		}
		/**
    * Function to delete_lesson
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
		function delete_lesson($data)
		{
			$id = $data['lesson_id'];
			$this->db->where('id',$id);
			$this->db->delete('lesson');
			return ($this->db->affected_rows() > 0) ? true: false ;
		
		}
}
