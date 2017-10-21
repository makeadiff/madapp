<?php
class Impact_survey_model extends Model {
    function Impact_survey_model() {
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->year = $this->ci->session->userdata('year');
    }

	function add($name) {
		$this->db->insert("IS_Event", array(
				'name'		=> $name,
				'added_on'	=> date('Y-m-d H:i:s'),
				'status'	=> '1',
				'vertical_id' => '0'
			));
    }

    function save_response($data) {
    	$data['added_on'] = date('Y-m-d H:i:s');

		$this->db->insert("IS_Response", $data);
		return $this->db->insert_id();
	}

    function get_questions($vertical_id) {
    	$this->db->select('id,question')->from('IS_Question')->where('vertical_id',$vertical_id)->where('status', '1');
		$result = $this->db->get();
		return $result->result();
    }

    function get_active_event($user_id) {
    	$unentered_events = array();

    	// :TODO: Show only events that happen in the last month?
    	$active_events = $this->db->query("SELECT id, name FROM IS_Event WHERE status='1'")->result();
    	if(!$active_events) return $unentered_events;

    	foreach ($active_events as $e) {
    		// Find if the current user has responded to the survey event.
    		$response_count = oneFormat($this->db->query("SELECT COUNT(id) FROM IS_Response WHERE is_event_id={$e->id} AND user_id=$user_id")->row());

    		// :TODO: Should the user have responded to all the qusetions for all their students? If so, change the line to $response_count >= $possible_responses.
    		if(!$response_count) {
    			$unentered_events[$e->id] = $e->name;
    		}
    	}

    	return $unentered_events;
    }

}

