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
	/*
     * Function Name : getevent_list()
     * Wroking :This function return all the event lists.
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */
	function getevent_list()
	{
		return $this->db->query("SELECT * FROM Event WHERE city_id=".$this->city_id)->result();
	}
	/*
     * Function Name : add_event()
     * Wroking :This function save event lists.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [array]
     */
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
	/*
     * Function Name : delete_event()
     * Wroking :This function delete events.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */
	function delete_event($data)
	{
		$this->db->delete('Event', array('id'=>$data['id']));
		$this->db->delete('UserEvent', array('event_id'=>$data['id']));
		
		return ($this->db->affected_rows() > 0) ? true : false;
	}
	/*
     * Function Name : getevent()
     * Wroking :This function returns events details of given event id.
     * @author:Rabeesh
     * @param :[$id]
     * @return: type: [boolean]
     */
	function getevent($id)
	{
	return $this->db->where('id', $id)->get('Event')->result();
	}
	/*
     * Function Name : update_event()
     * Wroking :This function update events derails
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */
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
	/*
     * Function Name : get_event_type()
     * Wroking :This function return events.
     * @author:Rabeesh
     * @param :[$id]
     * @return: type: [array]
     */
	function get_event_type($id) {
		return $this->db->query("SELECT * FROM Event WHERE id=$id")->row();
	}
	/*
     * Function Name : insert_user_event()
     * Wroking :This function save events of users.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */
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
	
	/*
     * Function Name : get_user_event()
     * Wroking :This function save all the events of users.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */
	function get_user_event($data)
	{
		$event_id= $data['event_id'];
		$this->db->where('event_id',$event_id);
		if(isset($data['user_id'])) $this->db->where('user_id',$data['user_id']);
		$this->db->from('UserEvent');
		$result = $this->db->get();
		return $result;
	}
	/*
     * Function Name : delete_user_event()
     * Wroking :This function delete perticuler  the events of users.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */
	function delete_user_event($data)
	{
		$this->db->delete('UserEvent', array('user_id'=>$data['user_id']));
	}
	/*
     * Function Name : deletefull_user_event()
     * Wroking :This function delete all the events of users.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */
	function deletefull_user_event($data)
	{
		$this->db->delete('UserEvent', array('event_id'=>$data['event_id']));
		return ($this->db->affected_rows() > 0) ? true : false;
		
	}
	/*
     * Function Name : get_event_users()
     * Wroking :This function returns  all the users deatisl of given events.
     * @author:Rabeesh
     * @param :[$id]
     * @return: type: [boolean]
     */
	function get_event_users($id)
	{
		return $event = $this->db->query("SELECT UserEvent.*,User.name as user_name,User.id as user_id FROM UserEvent INNER JOIN User ON UserEvent.user_id = User.id WHERE event_id=$id")->result();
	}
	/*
     * Function Name : update_user_status()
     * Wroking :This function update user status
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */
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
		if($event->type == 'avm') {
			$this->ci->load->model('user_model');
			if($status['present'] == 0) {
				$this->ci->user_model->update_credit($user_id, -1);
			} else {
				$this->ci->user_model->update_credit($user_id, 1); // He was marked absent - but was marked present after that.(everone is marked present by default).
			}
			
		}
	}
	/*
     * Function Name : getEventUser()
     * Wroking :This function return userdetails of given user id an devent id
     * @author:Rabeesh
     * @param :[$id,$user_id]
     * @return: type: [boolean]
     */
	function getEventUser($id,$user_id) {
		return $this->db->query("SELECT * FROM UserEvent  WHERE event_id=$id AND user_id=$user_id")->row();
		
	}
	/*
     * Function Name : get_missing_user_attendance_for_event_type()
     * Wroking :This function return missing user attendance.
     * @author:Rabeesh
     * @param :[$user_id, $event_type]
     * @return: type: [boolean]
     */
	function get_missing_user_attendance_for_event_type($user_id, $event_type) {
		$data = $this->db->query("SELECT Event.name, Event.starts_on, UserEvent.present FROM UserEvent INNER JOIN Event ON UserEvent.event_id=Event.id 
							WHERE Event.type='$event_type' AND UserEvent.user_id=$user_id AND UserEvent.present='0'
							AND Event.starts_on > '{$this->year}-04-01 00:00:00' 
							AND Event.starts_on < '".($this->year + 1)."-03-31 23:59:59'")->result();
		return $data;
	}
	/*
     * Function Name : get_all()
     * Wroking :This function return all events details.
     * @author:Rabeesh
     * @param :[$event_type]
     * @return: type: [boolean]
     */
	function get_all($event_type='') {
		$city_id = $this->city_id;
		$this->db->select('*')->from('Event')->where('city_id', $city_id);
		if($event_type) $this->db->where('type',$event_type);
		
		return $this->db->get()->result();
	}
	/*
     * Function Name : get_all_event_user_attendance()
     * Wroking :This function return all events user attendance.
     * @author:Rabeesh
     * @param :[$event_type]
     * @return: type: [boolean]
     */
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
        
    /*
     * Function Name : get_last_event()
     * Wroking :Returns the last event of the given event type.
     * @author:
     * @param :[$event_type,$city_id]
     * @return: type: [boolean]
     */

	function get_last_event($event_type='', $city_id=0) {
		if(!$city_id) $city_id = $this->city_id;
		$this->db->from('Event')->where('city_id', $city_id);
		if($event_type) $this->db->where('type',$event_type);
		$this->db->orderby('starts_on DESC');
		return $this->db->get()->row();
	}
	/*
     * Function Name : months_since_event()
     * Wroking :Returns the number of months since the last event of the given type.
     * @author:
     * @param :[$event_type,$year_month, $city_id]
     * @return: type: [boolean]
     */

	function months_since_event($event_type, $year_month, $city_id) {
		$last_event = $this->event_model->get_last_event($event_type, $city_id);
		if(!$last_event) $starts_on = get_mad_year_starting_date();
		else $starts_on = $last_event->starts_on;
		$difference = date_diff(date_create($year_month.'-01'), date_create($starts_on));
		return $difference->format('%m');
	}
        function get_volunteers_to_attend_training_1($year_month, $city_id ,$teacher_training1)
        {
           
            return $this->db->query("SELECT COUNT(id) AS count FROM Event JOIN Userevent ON Event.id=Userevent.event_id
                                WHERE Userevent.present='0' AND Event.name='$teacher_training1'")->row()->count;
           
        }
}