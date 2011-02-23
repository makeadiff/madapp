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
    }
	
	 /**
    * Function to getcenter_count
    * @author:Rabeesh
    * @param :[$data]
    * @return: type: [Boolean,int]
    **/
	function getcenter_count()
	{
			$this->db->select('*')->where('Center.city_id',$this->city_id)->from('Center');
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
	
		$this->db->select('Center.*,City.name as city_name,User.name as user_name');
		$this->db->from('Center');
		$this->db->where('Center.city_id',$this->city_id);
		$this->db->join('City', 'City.id = Center.city_id' ,'join');
		$this->db->join('User', 'Center.center_head_id = User.id' ,'left');
		
		$result = $this->db->get();
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
	$this->db->from('City');
	$result=$this->db->get();
	return $result;
	
	}
	 /**
    * Function to getheadname
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getheadname() {
		$this->db->select('*')->where('city_id', $this->city_id)->where('project_id',$this->project_id)->where('center_id',0);
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
	$data = array(	'city_id' => $data['city'] ,
			 		'name' => $data['center'] ,
					'center_head_id' => $data ['user_id'],
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
	function edit_center($uid)
	{
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
		$rootId=$data['rootId'];
		$data = array('city_id' => $data['city'] ,
				'name' => $data['center'] ,
				'center_head_id' => $data ['user_id'],
				);
		$this->db->where('id', $rootId);
		$this->db->update('Center', $data);
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
		 $this->db->delete('Center');
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
	
	function get_all() {
		return $this->db->where('city_id',$this->city_id)->get('Center')->result();
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
}