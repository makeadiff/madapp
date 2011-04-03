<?php
class City_model extends Model {
    function City_model() {
        // Call the Model constructor
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
    }
    
    function getCities() {
    	$cities = $this->db->get('City')->result();
    	
    	// Highlight the errors in the center - if any.
		for($i=0; $i<count($cities); $i++) {
			$center_id = $cities[$i]->id;
			
			list($information, $problem_count) = $this->find_issuse($center_id);
			$cities[$i]->problem_count = $problem_count;
			$cities[$i]->information = $information;
		}
    	
    	return $cities;
    }
    
    // Find the errors in the city. - if any.
	function find_issuse($city_id) {
		$president_id = $this->db->query("SELECT president_id FROM City WHERE id=$city_id")->row()->president_id;
		$center_count = $this->db->query("SELECT COUNT(id) AS count FROM Center WHERE city_id=$city_id")->row()->count;
		$kids_count = 0;
		if($center_count) {
			$kids_count = $this->db->query("SELECT COUNT(Student.id) AS count 
				FROM Student INNER JOIN Center ON Center.id=Student.center_id 
				WHERE Center.city_id=$city_id")->row()->count;
		}
		$teacher_count = $this->db->query("SELECT COUNT(id) AS count FROM User WHERE city_id=$city_id AND project_id={$this->project_id}")->row()->count;
		
		$problem_flag = 0;
		$information = array();
		
		if(!$president_id) {
			$information[] = "City does not have a president. <span class='warning icon'>!</span>";
			$problem_flag++;
		}
		
		if(!$teacher_count) {
			$information[] = "No teachers added to the city <span class='warning icon'>!</span>";
			$problem_flag++;
		} else {
			$information[] = "Teachers in this city: $teacher_count";
		}
		
		if(!$kids_count) {
			$information[] = "No students added to the city <span class='warning icon'>!</span>";
			$problem_flag++;
		} else {
			$information[] = "Kids in this city: $kids_count";
		}
		
		return array($information, $problem_flag);
	}
    
    function createCity($data) {
		$success = $this->db->insert('City', 
			array(
				'name'			=>	$data['name'], 
				'president_id'	=>	$data['president_id'],
				'added_on'		=>	date('Y-m-d H:i:s')
			));
		
		//  If the City was just created, the president don't belong to that city yet. Make sure s/he belongs to it.
		if($success) {
			$this->db->where('id',$data['president_id'])->update('User', 
				array(
					'city_id'=>$this->db->insert_id()
				)
			);
		}
    }
    
    function editCity($data) {
    	$this->db->where('id', $this->input->post('id'))->update('City', $data);
    }
    
    function getCity($city_id) {
    	return $this->db->where('id',$city_id)->get('City')->row_array();
    }
    
    
	function get_city() {
		return $this->db->get('City');
	}
}
