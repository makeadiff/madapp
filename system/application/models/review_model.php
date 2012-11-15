<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_model extends Model {
	function Review_model() {
		parent::model();
		$this->ci = &get_instance();
		//$this->city_id = $this->ci->session->userdata('city_id'); // This maybe run from a cron job.
		$this->project_id = 1;//$this->ci->session->userdata('project_id');
	}

	function get($name, $date, $city_id) {
		return $this->db->from('Review')->where('name',$name)->where('review_on',$date)->where('city_id', $city_id)->get()->row();
	}
	
	function delete($name, $date) {
		return $this->db->where('name',$name)->where('review_on',$date)->delete('Review');
	}
	
	function save($name, $value, $date, $flag, $city_id, $comment='') {
		$data = array(
			'name'		=> $name,
			'value'		=> $value,
			'review_on'	=> $date."-01",
			'flag'		=> $flag,
			'city_id'	=> $city_id,
			'comment'	=> $comment
		);
		
		$review = $this->get($name, $date, $city_id);
		if($review) $this->db->update('Review', $data, array('id'=>$review->id));
		else $this->db->insert('Review', $data);
	}

	
	function get_monthly_review($year_month, $city_id) {
		return $this->db->select('name,value,flag,comment')->from('Review')->where('review_on', $year_month.'-01')->where('city_id', $city_id)->get()->result();
	}
	
	function set_comment($city_id, $year_month, $name, $comment) {
		$this->db->where('city_id',$city_id)->where('review_on', $year_month.'-01')->where('name', $name);
		$this->db->update('Review',array('comment' => $comment));
	}
	
	function get_comment($city_id, $year_month, $name) {
		$data = $this->db->select('comment')->from('Review')->where('city_id',$city_id)->where('review_on', $year_month.'-01')->where('name', $name)->get()->row();
		return $data->comment;
	}
}