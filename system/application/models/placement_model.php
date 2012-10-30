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

class Placement_model extends Model {

    function Placement_model() {
        parent::Model();
        $this->ci = &get_instance();
        $this->city_id = $this->ci->session->userdata('city_id');
        $this->project_id = $this->ci->session->userdata('project_id');
        $this->year = $this->ci->session->userdata('year');
    }
    
    
	/**
    * Function to getgroup_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getgroup_details()
	{
		$this->db->select('*');
		$this->db->from('Placement_Group');
                $this->db->order_by('id','DESC');
		$result=$this->db->get();
		return $result;
	}
	/**
    * Function to add_group_name
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function add_group_name($data)
	{
		$datas = array('name'=> $data['groupname'],
                              'group'=> $data['cgroup'],
                              'center_id'=> $data['centreid'],
                              'sex'=> $data['sex'],
                              'activity_frequency'=> $data['actfrq'],
                              'code'=> $data['code'],
                    
                    );
		$this->db->insert('Placement_Group',$datas);
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id(): false ;
		
	}
	/**
    * Function to edit_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function edit_group($user_id)
	{
		$this->db->select('*');
		$this->db->from('Placement_Group');
		$this->db->where('id',$user_id);
		$result=$this->db->get();
		return $result;
	}
	/**
    * Function to update_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function update_group($data)
	{
		$datas = array('name'=> $data['group_name'],
                              'group'=> $data['cgroup'],
                              'center_id'=> $data['centreid'],
                              'sex'=> $data['sex'],
                              'activity_frequency'=> $data['actfrq'],
                              'code'=> $data['code'],
                    
                    );
		$this->db->where('id', $data['group_id']);
		$this->db->update('Placement_Group', $datas);
	 	return ($this->db->affected_rows() > 0) ? true: false ;
	}

	/**
    * Function to delete_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function delete_group($data)
	{
		$id = $data['entry_id'];
		$this->db->where('id',$id);
		$this->db->delete('Placement_Group');
		
		return ($this->db->affected_rows() > 0) ? true: false ;
	}
	
        	/**
    * Function to edit_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function getcenter_details()
	{
		$this->db->select('id,name');
		$this->db->from('Center');
		$this->db->where('city_id',$this->session->userdata('city_id'));
		$this->db->where('status','1');
		$result = $this->db->get();
		return $result;
	}
        
        /**
    * Function to add_group_name
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function add_activity_name($data)
	{
		$datas = array('name'=> $data['activityname'],
                              'location'=> $data['locact'],
                              'skill'=> $data['skill'],
                              'career'=> $data['career'],
                              'sex'=> $data['sex'],
                              'generalised'=> $data['generalised'],
                              'specialised'=> $data['specialised'],
                              'field_expert'=> $data['field_expert'],
                              'created_by_city_id '=> $this->session->userdata('city_id'),
                              'file'=> $data['filename'],
                              'link'=> $data['link'],
                    );
		$this->db->insert('Placement_Activity',$datas);
		return ($this->db->affected_rows() > 0) ? $this->db->insert_id(): false ;
		
	}
        
        	/**
    * Function to getgroup_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Array()]
    **/
	function getactivity_details()
	{
		$this->db->select('*');
		$this->db->from('Placement_Activity');
                $this->db->order_by('id','DESC');
		$result=$this->db->get();
		return $result;
	}
        
        	/**
    * Function to edit_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function edit_activity($user_id)
	{
		$this->db->select('*');
		$this->db->from('Placement_Activity');
		$this->db->where('id',$user_id);
		$result=$this->db->get();
		return $result;
	}
	/**
    * Function to update_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function update_activity($data)
	{
		$datas = array('name'=> $data['activityname'],
                              'location'=> $data['locact'],
                              'skill'=> $data['skill'],
                              'career'=> $data['career'],
                              'sex'=> $data['sex'],
                              'generalised'=> $data['generalised'],
                              'specialised'=> $data['specialised'],
                              'field_expert'=> $data['field_expert'],
                              'created_by_city_id '=> $this->session->userdata('city_id'),
                              'file'=> $data['filename'],
                              'link'=> $data['link'],
                    );
		$this->db->where('id', $data['group_id']);
		$this->db->update('Placement_Activity', $datas);
	 	return ($this->db->affected_rows() > 0) ? true: false ;
	}
        
        	/**
    * Function to delete_group
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, ]
    **/
	function delete_activity($data)
	{
		$id = $data['entry_id'];
		$this->db->where('id',$id);
		$this->db->delete('Placement_Activity');
		
		return ($this->db->affected_rows() > 0) ? true: false ;
	}

}
