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

class Center_model extends Model
{
    function Center_model()
    {
        parent::Model();
    }
	
	 /**
    * Function to getcenter_count
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean,int]
    **/
	function getcenter_count()
	{
			$this->db->select('*');
			$this->db->from('center');
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
	
		$this->db->select('center.*,city.name as city_name,user.name as user_name');
		$this->db->from('center');
		$this->db->join('city', 'city.id = center.city_id' ,'join');
		$this->db->join('user', 'user.id = center.center_head_id' ,'join');		
		$result=$this->db->get();
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
	$this->db->from('city');
	$result=$this->db->get();
	return $result;
	
	}
	 /**
    * Function to getheadname
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getheadname()
	{
	$this->db->select('*');
	$this->db->from('user');
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
	$data = array('city_id' => $data['city'] ,
			 		  'name' => $data['center'] ,
					  'center_head_id' => $data ['user_id'],
			 		  );
						  
	    $this->db->insert('center',$data);  
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
	$this->db->from('center');
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
	function update_center($data)
	{
			$rootId=$data['rootId'];
			$data = array('city_id' => $data['city'] ,
			 		  'name' => $data['center'] ,
					  'center_head_id' => $data ['user_id'],
			 		  );
			 $this->db->where('id', $rootId);
			 $this->db->update('center', $data);
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
		 $this->db->delete('center');
		 return ($this->db->affected_rows() > 0) ? true: false ;
	
	}
	/**
    * Function to center_name
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array]
    **/
	function center_name($center_id)
	{
	$this->db->select('name');
	$this->db->from('center');
	$this->db->where('id',$center_id);
	$result=$this->db->get();
	return $result;
	
	}
	/**
    * Function to getcenter
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array]
    **/
	function getcenter()
	{
	$this->db->select('*');
	$this->db->from('center');
	$result=$this->db->get();
	return $result;
	
	}
}