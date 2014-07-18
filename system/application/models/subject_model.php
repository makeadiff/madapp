<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); 

class Subject_model extends Model {
 
	function Subject_model() {
		parent::Model();
		$this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
	}

	function get_all_subjects($city_id = 0) {
		if(!$city_id) $city_id = $this->city_id;

		$result = $this->db->select('*')->from('Subject')->where('city_id', $city_id)->where('status','1')->get();
		return $result->result();
	}


	function get_subject($subject_id) {
		$result = $this->db->select('*')->from('Subject')->where('id', $subject_id)->where('status','1')->get();
		return $result->row();
	}

	function get_name($subject_id) {
		$result = $this->db->select('*')->from('Subject')->where('id', $subject_id)->where('status','1')->get()->row();
		return $result->name;
	}


	function add($data) {
		$this->db->insert('Subject',$data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	function edit($subject_id, $data) {
		$this->db->where('id', $subject_id);
		$this->db->update('Subject', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
	}

	function delete($subject_id) {
		$this->db->where('id',$subject_id);
		$this->db->update('Subject', array('status'=>'0'));
		return ($this->db->affected_rows() > 0) ? true: false ;
	}
}
