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
class Event_model extends Model{
	
		/**
   		 * constructor 
    	**/
		function Event_model()
		{
			parent::model();
			$this->ci = &get_instance();
			$this->city_id = $this->ci->session->userdata('city_id');
        	$this->project_id = $this->ci->session->userdata('project_id');
		}
		/**
   		* Function to getevent_list
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function getevent_list()
		{
			return $event = $this->db->query("SELECT Event.*,City.name as city_name FROM Event INNER JOIN City ON Event.city_id = City.id order by Event.id desc")->result();
		}
		/**
   		* Function to add_event
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function add_event($data)
		{
			$this->db->insert("Event", array(
				'name'	=> $data['name'],
				'starts_on'	=> $data['startdate'],
				'ends_on'=>$data['enddate'],
				'place'=>$data['place'],
				'type'=>$data['type'],
				//'city_id'=>$this->city_id
			));
			return ($this->db->affected_rows() > 0) ? true : false;
		}
		/**
   		* Function to delete_event
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function delete_event($data)
		{
			$this->db->delete('Event', array('id'=>$data['id']));
			return ($this->db->affected_rows() > 0) ? true : false;
		}
		/**
   		* Function to getevent
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function getevent($id)
		{
		return $this->db->where('id', $id)->get('Event')->result();
		}
		/**
   		* Function to update_event
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function update_event($data)
		{
			$this->db->where('id',$data['root_id'] );
			$this->db->update("Event", array(
				'name'	=> $data['name'],
				'starts_on'	=> $data['startdate'],
				'ends_on'=>$data['enddate'],
				'place'=>$data['place'],
				'type'=>$data['type']
			));
			return ($this->db->affected_rows() > 0) ? true : false;
		}
		/**
   		* Function to get_event_type
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function get_event_type($id)
		{
			return $event = $this->db->query("SELECT * FROM Event WHERE id=$id")->result();
		}
		/**
   		* Function to insert_user_event
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function insert_user_event($data)
		{
			$user_id=$data['user_id'];
			$event_id= $data['event_id'];
			$this->db->where('user_id',$user_id );
			$this->db->where('event_id',$event_id);
        	$this->db->from('UserEvent');
			$result = $this->db->get()->row();
			if(count($result) == 0) {
			$this->db->insert("UserEvent", array(
			'user_id'	=> $data['user_id'],
			'event_id'	=> $data['event_id'],
			'present'	=> '1'
			));
			}
			
		}
		/**
   		* Function to get_user_event
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function get_user_event($data)
		{
			$event_id= $data['event_id'];
			$this->db->where('event_id',$event_id);
        	$this->db->from('UserEvent');
			$result = $this->db->get();
			return $result;
		}
		/**
   		* Function to delete_user_event
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function delete_user_event($data)
		{
			$this->db->delete('UserEvent', array('user_id'=>$data['user_id']));
		}
		/**
   		* Function to deletefull_user_event
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function deletefull_user_event($data)
		{
			$this->db->delete('UserEvent', array('event_id'=>$data['event_id']));
			return ($this->db->affected_rows() > 0) ? true : false;
			
		}
		/**
   		* Function to get_event_users
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function get_event_users($id)
		{
			return $event = $this->db->query("SELECT UserEvent.*,User.name as user_name,User.id as user_id FROM UserEvent INNER JOIN User ON UserEvent.user_id = User.id WHERE event_id=$id")->result();
		}
		/**
   		* Function to update_user_status
    	* @author:Rabeesh 
   		* @param :[$data]
    	* @return: type: [ result Array()]
    	**/
		function update_user_status($data)
		{
			$user_id=$data['user_id'];
			$event_id=$data['event_id'];
			$this->db->where('user_id', $user_id);
			$this->db->where('event_id', $event_id);
        	$this->db->from('UserEvent');
       	    $result = $this->db->get()->row();
			if(count($result) > 0 ){$present=$result->present;}
			if($present == 1){
				$status = array('present'=>'0');
			} else {
				$status=array('present'=>'1');
			}
			$this->db->where('user_id',$user_id );
			$this->db->where('event_id',$event_id );
			$this->db->update("UserEvent",$status);
		}
		function getEventUser($id,$user_id)
		{
		
		//return $event = $this->db->query("SELECT user.*,userevent.* FROM user LEFT JOIN userevent ON user.id=userevent.user_id order by User.name");
		return  $event = $this->db->query("SELECT * FROM userevent  WHERE event_id=$id AND user_id=$user_id")->row();
		/// print_r($event->result());
		echo "SELECT * FROM userevent  WHERE event_id=$id AND user_id=$user_id";
	 
		}
		
	

}