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

        // Delete existing response if any.
        $this->db->delete("IS_Response", array(
            'is_event_id' => $data['is_event_id'], 
            'student_id' => $data['student_id'], 
            'question_id' => $data['question_id'],
            'user_id' => $data['user_id']
        ));

		$this->db->insert("IS_Response", $data);
		return $this->db->insert_id();
	}

    function get_questions($vertical_id) {
    	$this->db->select('id,question')->from('IS_Question')->where('vertical_id',$vertical_id)->where('status', '1');
		$result = $this->db->get();
		return $result->result();
    }

    function get_response($is_event_id, $student_id, $user_id) {
        $this->db->select('id,user_id,student_id,question_id,response')->from("IS_Response");
        $this->db->where('is_event_id', $is_event_id)->where('student_id', $student_id)->where('user_id', $user_id);
        $result = $this->db->get();
        return $result->result();
    }

    function get_active_event($level_id, $user_id) {
    	$unentered_events = array();

    	// :TODO: Show only events that happen in the last month?
    	$active_events = $this->db->query("SELECT id, name FROM IS_Event WHERE status='1'")->result();
    	if(!$active_events) return $unentered_events;

        $this->load->model("Level_model");

        $question_count = oneFormat($this->db->query("SELECT COUNT(id) FROM IS_Question")->row());
        $students_in_level = $this->level_model->get_kids_in_level($level_id);
        $possible_responses = count($students_in_level) * $question_count;

    	foreach ($active_events as $e) {
    		// Find the count of student's data that we have for this survey.
    		$response_count = oneFormat($this->db->query("SELECT COUNT(id) FROM IS_Response 
                    WHERE is_event_id={$e->id} AND user_id=$user_id AND student_id IN (" . implode(",", array_keys($students_in_level)) . ")")->row());

    		// Do we have responses to all the qusetions for all their students? 
    		if($response_count < $possible_responses) {
    			$unentered_events[$e->id] = $e->name;
    		}
    	}

    	return $unentered_events;
    }

    function previous_event_id($current_event_id) {
        $previous_event = $this->db->query("SELECT id, name FROM IS_Event WHERE id < $current_event_id ORDER BY id DESC LIMIT 0, 1")->row();

        if($previous_event) return $previous_event->id;
        return 0;
    }

}

