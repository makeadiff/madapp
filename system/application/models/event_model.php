<?php
class Event_model extends Model{
	
		function Event_model()
		{
			parent::model();
			$this->ci = &get_instance();
			$this->city_id = $this->ci->session->userdata('city_id');
		}
		function getevent_list()
		{
			return $event = $this->db->query("SELECT event.*,city.name as city_name FROM event INNER JOIN city ON event.city_id = city.id order by event.id desc ")->result();
		}
		function add_event($data)
		{
			$this->db->insert("event", array(
			'name'	=> $data['name'],
			'starts_on'	=> $data['startdate'],
			'ends_on'=>$data['enddate'],
			'place'=>$data['place'],
			'type'=>$data['type'],
			'city_id'=>$data['city']
			));
			return ($this->db->affected_rows() > 0) ? true : false;
		
		}
		function delete_event($data)
		{
			$this->db->delete('event', array('id'=>$data['id']));
			return ($this->db->affected_rows() > 0) ? true : false;
		}
		function getevent($id)
		{
		return $this->db->where('id', $id)->get('event')->result();
		}
		function update_event($data)
		{
			$this->db->where('id',$data['root_id'] );
			$this->db->update("event", array(
			'name'	=> $data['name'],
			'starts_on'	=> $data['startdate'],
			'ends_on'=>$data['enddate'],
			'place'=>$data['place'],
			'type'=>$data['type'],
			'city_id'=>$data['city']
			));
			return ($this->db->affected_rows() > 0) ? true : false;
		}
		function get_event_type()
		{
			return $event = $this->db->query("SELECT  *  FROM  event WHERE FIELD(type, 'process', 'curriculam', 'teacher')")->result();

		}
		
		function insert_user_event($data)
		{
			$this->db->insert("userevent", array(
			'user_id'	=> $data['user_id'],
			'event_id'	=> $data['event_id'],
			'present'=>0
			));
		}

}