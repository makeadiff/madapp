<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Review_parameter_model extends Model {
	function Review_parameter_model() {
		parent::model();
		$this->ci = &get_instance();
		//$this->city_id = $this->ci->session->userdata('city_id'); // This maybe run from a cron job.
		$this->project_id = 1;//$this->ci->session->userdata('project_id');
	}

	function get($name, $timeframe, $user_id) {
		return $this->db->from('Review_Parameter')->where('name',$name)->where('timeframe',$timeframe)->where('user_id', $user_id)->get()->row();
	}
	
	function delete($name, $timeframe) {
		return $this->db->where('name',$name)->where('timeframe',$timeframe)->delete('Review_Parameter');
	}
	
	function save($data) {
		$review = $this->get($data['name'], $data['timeframe'], $data['user_id']); // Check for existance
		if($review) $this->db->update('Review_Parameter', $data, array('id'=>$review->id));
		else $this->db->insert('Review_Parameter', $data);
	}
	function save_value($parameter_id, $value) {
		$this->db->update('Review_Parameter',array('value' => $value), array('id'=> $parameter_id));
	}

	function get_reviews($user_id, $timeframe) {
		return $this->db->from('Review_Parameter')->where('user_id',$user_id)->where('timeframe',$timeframe)->get()->result();
	}
	
	function set_comment($parameter_id) {
		$comment = $this->input->post('comment');
		$this->db->update('Review_Parameter',array('comment' => $comment), array('id'=> $parameter_id));
	}
	
	function get_comment($parameter_id) {
		$data = $this->db->select('comment')->from('Review_Parameter')->where('id',$parameter_id)->get()->row();
		return $data->comment;
	}


	////////////////////////// Milestone stuff //////////////////

	function get_all_milestones($user_id, $timeframe=0) {
		$this->db->from('Review_Milestone')->where('user_id', $user_id);
		if($timeframe) $this->db->where('due_timeframe',$timeframe);
		$this->db->orderby('due_on ASC');

		return $this->db->get()->result();
	}

	function get_overdue_milestones($user_id, $timeframe) {
		$this->db->from('Review_Milestone')->where('user_id', $user_id)->where('due_timeframe < ', $timeframe)->where('status', '0');

		return $this->db->get()->result();
	}

	function get_milestone($milestone_id) {
		return $this->db->from('Review_Milestone')->where('id', $milestone_id)->get()->row();
	}

	function edit_milestone($milestone_id, $data) {
		return $this->db->update('Review_Milestone',$data, array('id'=>$milestone_id));
	}

	function delete_milestone($milestone_id) {
		$user_id = $this->db->select('user_id')->from("Review_Milestone")->where('id', $milestone_id)->get()->row();

		$this->db->where('id',$milestone_id)->delete('Review_Milestone');

		return $user_id->user_id;
	}


	function create_milestone($data) {
		return $this->db->insert('Review_Milestone',$data);
	}

	function get_timeframes_with_milestone($user_id) {
		return $this->db->query("SELECT DISTINCT due_timeframe FROM Review_Milestone WHERE user_id=$user_id ORDER BY due_timeframe")->result();
	}

	function do_milestone($milestone_id, $status = '1', $done_on = 0) {
		if(!$done_on) $done_on = date('Y-m-d H:i:s');
		else $done_on = date('Y-m-d H:i:s', time($done_on));

		$this->edit_milestone($milestone_id, array('status' => 0, 'done_on' => $done_on));
	}

	function find_timeframe($due_on) {
		return intval(date('m'));
	}
}
