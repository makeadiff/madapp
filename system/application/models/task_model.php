<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
class Task_model extends Model{
	
		/**
   		 * constructor 
    	**/
		function Task_model()
		{
			parent::model();
			$this->ci = &get_instance();
			$this->city_id = $this->ci->session->userdata('city_id');
		}
		function get_task()
		{
		
			return $event = $this->db->query("SELECT * FROM task order by id desc")->result();
		
		}
		function add_task($data)
		{
		$this->db->insert("Task", array(
				'name'	=> $data['name'],
				'credit'=>$data['credit'],
				'vertical'=>$data['type'],
			));
			return ($this->db->affected_rows() > 0) ? true : false;
		
		}
		function delete_task($data)
		{
			$this->db->delete('Task', array('id'=>$data['id']));
			return ($this->db->affected_rows() > 0) ? true : false;
		}
		function gettask($id)
		{
		return $this->db->where('id', $id)->get('Task')->result();
		}
		function update_task($data)
		{
			$this->db->where('id',$data['root_id'] );
			$this->db->update("Task", array(
				'name'	=> $data['name'],
				'credit'=>$data['credit'],
				'vertical'=>$data['vertical']
			));
			return ($this->db->affected_rows() > 0) ? true : false;
		}
}		