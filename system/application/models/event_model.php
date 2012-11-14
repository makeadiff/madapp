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
		$this->year = $this->ci->session->userdata('year');
	}
	/**
	* Function to getevent_list
	* @author:Rabeesh 
	* @param :[$data]
	* @return: type: [ result Array()]
	**/
	function getevent_list()
	{
		return $this->db->query("SELECT * FROM Event WHERE city_id=".$this->city_id)->result();
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
			'name'		=> $data['name'],
			'starts_on'	=> $data['startdate'],
			'ends_on'	=> $data['enddate'],
			'description'=>$data['description'],
			'place'		=> $data['place'],
			'type'		=> $data['type'],
			'city_id'	=> $this->city_id
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
		$this->db->delete('UserEvent', array('event_id'=>$data['id']));
		
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
			'name'		=> $data['name'],
			'starts_on'	=> $data['startdate'],
			'description'=>$data['description'],
			'place'		=> $data['place'],
			'type'		=> $data['type']
		));
		return ($this->db->affected_rows() > 0) ? true : false;
	}
	/**
	* Function to get_event_type
	* @author:Rabeesh 
	* @param :[$data]
	* @return: type: [ result Array()]
	**/
	function get_event_type($id) {
		return $this->db->query("SELECT * FROM Event WHERE id=$id")->row();
	}
	/**
	* Function to insert_user_event
	* @author:Rabeesh 
	* @param :[$data]
	* @return: type: [ result Array()]
	**/
	function insert_user_event($data) {
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
		if(isset($data['user_id'])) $this->db->where('user_id',$data['user_id']);
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
		return $event = $this->db->query("SELECT UserEvent.*,User.name as user_name,User.id as user_id FROM UserEvent INNER JOIN User ON UserEvent.user_id = User.id WHERE event_id=$id ORDER BY User.name")->result();
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
		if(count($result) > 0 ) {
			$present = $result->present;
		}
		
		if($present == 1){
			$status = array('present'=>'0');
		} else {
			$status = array('present'=>'1');
		}
		$this->db->where('user_id',$user_id );
		$this->db->where('event_id',$event_id );
		$this->db->update("UserEvent",$status);
		
		// If people didn't come for the AVM thats a -1 credit.
		$event = $this->getevent($event_id);
		if($event) {
			$event = $event[0];
			if($event->type == 'avm') {
				$this->ci->load->model('users_model');
				if($status['present'] == 0) {
					$this->ci->users_model->update_credit($user_id, -1);
				} else {
					$this->ci->users_model->update_credit($user_id, 1); // He was marked absent - but was marked present after that.(everone is marked present by default).
				}
			}
		}
	}
	
	function getEventUser($id,$user_id) {
		return $this->db->query("SELECT * FROM UserEvent  WHERE event_id=$id AND user_id=$user_id")->row();
		
	}
	
	function get_missing_user_attendance_for_event_type($user_id, $event_type) {
		$data = $this->db->query("SELECT Event.name, Event.starts_on, UserEvent.present FROM UserEvent 
							INNER JOIN Event ON UserEvent.event_id=Event.id 
							WHERE Event.type='$event_type' AND UserEvent.user_id=$user_id AND UserEvent.present='0'
							AND Event.starts_on > '{$this->year}-04-01 00:00:00' 
							AND Event.starts_on < '".($this->year + 1)."-03-31 23:59:59'")->result();
		return $data;
	}
		
	function get_all($event_type='', $date_range=false) {
		$city_id = $this->city_id;
		$this->db->select('*')->from('Event')->where('city_id', $city_id);
		if($event_type) $this->db->where('type',$event_type);
		if($date_range) {
			$this->db->where("DATE(starts_on) >", $date_range['from']);
			$this->db->where("DATE(starts_on) <", $date_range['to']);
		}
		$result = $this->db->get();
		
		return $result->result();
	}
	
	function get_all_event_user_attendance($event_type='') {
		$city_id = $this->city_id;
		$this->db->select('UserEvent.*')->from('UserEvent')->join('Event','Event.id=UserEvent.event_id')
			->where('Event.city_id', $city_id)->orderby('Event.starts_on DESC');
		
		if($event_type) $this->db->where('Event.type',$event_type);
		$user_events = 	$this->db->get()->result();

		$data = array();
		foreach($user_events as $ue) {
			if(!isset($data[$ue->event_id])) $data[$ue->event_id] = array($ue->user_id => $ue->present);
			else $data[$ue->event_id][$ue->user_id] = $ue->present;
		}
		
		return $data;
	}
	
	/// Returns the last event of the given event type.
	function get_last_event($event_type='', $city_id=0) {
		if(!$city_id) $city_id = $this->city_id;
		$this->db->from('Event')->where('city_id', $city_id);
		if($event_type) $this->db->where('type',$event_type);
		$this->db->orderby('starts_on DESC');
		return $this->db->get()->row();
	}
	
	/// Returns the number of months since the last event of the given type.
	function months_since_event($event_type, $year_month, $city_id) {
		$last_event = $this->event_model->get_last_event($event_type, $city_id);
		if(!$last_event) $starts_on = get_mad_year_starting_date();
		else $starts_on = $last_event->starts_on;
		$difference = date_diff(date_create($year_month.'-01'), date_create($starts_on));
		return $difference->format('%m');
	}

	function get_count_of_missing_volunteers_at_event($year_month, $city_id, $event_name='', $event_type='') {
		$where = '1';
		if($event_name) $where = "Event.name='$event_name'";
		elseif($event_type) $where = "Event.type='$event_type'";
		
		$result = $this->db->query("SELECT COUNT(id) AS count FROM Event 
									JOIN UserEvent ON Event.id=UserEvent.event_id
									WHERE Event.city_id=$city_id AND UserEvent.present='0' 
									AND DATE_FORMAT(Event.starts_on, '%Y-%m')='$year_month' 
									AND $where
									GROUP BY UserEvent.event_id");
		$data = $result->row();
		if(!$data) return 0;
		
		return $data->count;
    }
    
	function get_count_of_expected_volunteers_at_event($year_month, $city_id, $event_name='', $event_type='') {
		$where = '1';
		if($event_name) $where = "Event.name='$event_name'";
		elseif($event_type) $where = "Event.type='$event_type'";
		
		$result = $this->db->query("SELECT COUNT(id) AS count FROM Event 
									JOIN UserEvent ON Event.id=UserEvent.event_id
									WHERE Event.city_id=$city_id
									AND DATE_FORMAT(Event.starts_on, '%Y-%m')='$year_month' 
									AND $where");
		$data = $result->row();
		if(!$data) return 0;
		
		return $data->count;
    } 
    
    function get_volunteers_at_event($year_month, $city_id, $event_name='', $event_type='') {
		$where = '1';
		if($event_name) $where = "Event.name='$event_name'";
		elseif($event_type) $where = "Event.type='$event_type'";
		
		if($year_month == 0) $where_year = "Event.starts_on > '{$this->year}-04-01 00:00:00' 
											AND Event.starts_on < '".($this->year + 1)."-03-31 23:59:59'";
		else $where_year = "DATE_FORMAT(Event.starts_on, '%Y-%m')='$year_month'";
		
		$result = $this->db->query("SELECT UserEvent.user_id FROM Event 
									JOIN UserEvent ON Event.id=UserEvent.event_id
									WHERE Event.city_id=$city_id AND UserEvent.present='1' 
									AND $where_year
									AND $where")->result();

		if(!$result) return false;
		$users = array();
		foreach($result as $u) $users[] = $u->user_id;
		return $users;
    }
}