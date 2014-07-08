<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_parameter_model extends Model {
	function Review_parameter_model() {
		parent::model();
		$this->ci = &get_instance();
		//$this->city_id = $this->ci->session->userdata('city_id'); // This maybe run from a cron job.
		$this->project_id = 1;//$this->ci->session->userdata('project_id');
	}

	function get($name, $timeframe, $city_id) {
		return $this->db->from('Review')->where('name',$name)->where('timeframe',$timeframe)->where('city_id', $city_id)->get()->row();
	}
	
	function delete($name, $timeframe) {
		return $this->db->where('name',$name)->where('timeframe',$timeframe)->delete('Review');
	}
	
	function save($name, $value, $timeframe, $flag, $city_id, $comment='') {
		$data = array(
			'name'		=> $name,
			'value'		=> $value,
			'timeframe'	=> $timeframe,
			'flag'		=> $flag,
			'city_id'	=> $city_id,
			'comment'	=> $comment
		);
		
		$review = $this->get($name, $timeframe, $city_id);
		if($review) $this->db->update('Review', $data, array('id'=>$review->id));
		else $this->db->insert('Review', $data);
	}

	
	function get_monthly_review($timeframe, $city_id) {
		return $this->db->select('name,value,flag,comment,id')->from('Review')->where('timeframe', $timeframe)->where('city_id', $city_id)->get()->result();
	}
	
	function set_comment($city_id, $timeframe, $name, $comment) {
		$this->db->where('city_id',$city_id)->where('timeframe', $timeframe)->where('name', $name);
		$this->db->update('Review',array('comment' => $comment));
	}
	
	function get_comment($city_id, $timeframe, $name) {
		$data = $this->db->select('comment')->from('Review')->where('city_id',$city_id)->where('timeframe', $timeframe)->where('name', $name)->get()->row();
		return $data->comment;
	}
}