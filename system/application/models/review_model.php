<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_model extends Model {
	function Review_model() {
		parent::model();
		$this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
	}

	function get($name, $date) {
		return $this->db->from('Review')->where('name',$name)->where('review_on',$date)->get()->row();
	}
	
	function delete($name, $date) {
		return $this->db->where('name',$name)->where('review_on',$date)->delete('Review');
	}
	
	function save($name, $value, $date, $flag) {
		$data = array(
			'name'		=> $name,
			'value'		=> $value,
			'review_on'	=> $date,
			'flag'		=> $flag,
		);
		
		$review = $this->get($name, $date);
		if($review) $this->db->update('Review', $data, array('id'=>$review->id));
		else $this->db->insert('Review', $data);
	}

	
	function get_monthly_review($year_month) {
		return $this->db->select('name,value,flag,comment')->from('Review')->where('review_on', $year_month.'-01')->get()->result();
	}
}