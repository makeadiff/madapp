<?php
class City_model extends Model {
    function City_model() {
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->year = $this->ci->session->userdata('year');

		$this->load->model('Users_model','user_model');
    }

    /// Function returns all the critical info about the center given as ID
    function get_info($city_id = 0) {
    	if(!$city_id) $city_id = $this->city_id;

    	$center_count = $this->db->query("SELECT COUNT(id) AS count FROM Center WHERE city_id=$city_id AND Center.status='1'")->row()->count;
		$kids_count = 0;
		$alloted_kids_count = 0;

		if(!$center_count) return array();

		$kids_count = $this->db->query("SELECT COUNT(Student.id) AS count 
			FROM Student INNER JOIN Center ON Center.id=Student.center_id 
			WHERE Center.city_id=$city_id AND Student.status='1' AND Center.status='1'")->row()->count;

		$alloted_kids_count = $this->db->query("SELECT COUNT(DISTINCT S.id) AS count FROM Student S
			INNER JOIN StudentLevel SL ON SL.student_id=S.id
			INNER JOIN Level L ON L.id=SL.level_id
			INNER JOIN Center C ON C.id=S.center_id
			WHERE L.year='{$this->year}' AND C.city_id='$city_id' AND S.status='1' AND L.status='1' AND C.status='1'" )->row()->count;

		$five_to_ten_kids_count = $this->db->query("SELECT COUNT(DISTINCT S.id) AS count FROM Student S
			INNER JOIN StudentLevel SL ON SL.student_id=S.id
			INNER JOIN Level L ON L.id=SL.level_id
			INNER JOIN Center C ON C.id=S.center_id
			WHERE L.year='{$this->year}' AND C.city_id='$city_id' AND S.status='1' AND L.status='1' AND C.status='1' 
				AND (L.grade='5' OR L.grade='6' OR L.grade='7' OR L.grade='8' OR L.grade='9' OR L.grade='10')")->row()->count;

		$eleven_twelve_kids_count = $this->db->query("SELECT COUNT(DISTINCT S.id) AS count FROM Student S
			INNER JOIN StudentLevel SL ON SL.student_id=S.id
			INNER JOIN Level L ON L.id=SL.level_id
			INNER JOIN Center C ON C.id=S.center_id
			WHERE L.year='{$this->year}' AND C.city_id='$city_id' AND S.status='1' AND L.status='1' AND C.status='1' 
				AND (L.grade='11' OR L.grade='12')" )->row()->count;

		$all_users = $this->user_model->search_users(array(
				'user_type'			=> 'volunteer',
				'city_id'			=> $city_id,
				'get_user_class'	=> true,
				'get_user_groups'	=> true
			));
		$volunteer_count = count($all_users);
		$teacher_count = 0;
		$mentor_count = 0;
		$mapped_teachers_count = 0;

		define("TEACHER_GROUP_ID",9);
		define("MENTOR_GROUP_ID", 8);

		foreach ($all_users as $u) {
			$groups = array_keys($u->groups);
			if(in_array(TEACHER_GROUP_ID, $groups)) $teacher_count++; 
			if(in_array(MENTOR_GROUP_ID, $groups)) $mentor_count++; 
			if($u->batch) $mapped_teachers_count++;
		}

		return array(
			'center_count' 			=> $center_count, 
			'kids_count' 			=> $kids_count, 
			'volunteer_count' 		=> $volunteer_count,
			'teacher_count' 		=> $teacher_count,
			'mentor_count'			=> $mentor_count,
			'mapped_teachers_count' => $mapped_teachers_count,
			'alloted_kids_count'	=> $alloted_kids_count,
			'five_to_ten_kids_count'=> $five_to_ten_kids_count,
			'eleven_twelve_kids_count' => $eleven_twelve_kids_count
		);
    }
    
    /// Get all cites - with information.
    function getCities() {
    	$cities = $this->db->orderby('name')->get('City')->result();
    	
    	// Highlight the errors in the center - if any.
		for($i=0; $i<count($cities); $i++) {
			$city_id = $cities[$i]->id;
			
			list($information, $problem_count) = $this->find_issuse($city_id);
			$cities[$i]->problem_count = $problem_count;
			$cities[$i]->information = $information;
		}
    	
    	return $cities;
    }
    
    // Find the errors in the city. - if any.
	function find_issuse($city_id) {
		$center_count = $this->db->query("SELECT COUNT(id) AS count FROM Center WHERE city_id=$city_id AND Center.status='1'")->row()->count;
		$kids_count = 0;
		if($center_count) {
			$kids_count = $this->db->query("SELECT COUNT(Student.id) AS count 
				FROM Student INNER JOIN Center ON Center.id=Student.center_id 
				WHERE Center.city_id=$city_id AND Student.status='1'")->row()->count;
		}
		$teacher_count = $this->db->query("SELECT COUNT(id) AS count FROM User WHERE city_id=$city_id AND user_type='volunteer'")->row()->count;
		
		$problem_flag = 0;
		$information = array();
		
		if(!$teacher_count) {
			$information[] = "No volunteers added to the city <span class='warning icon'>!</span>";
			$problem_flag++;
		} else {
			$information[] = "Volunteers in this city: $teacher_count";
		}
		
		if(!$kids_count) {
			$information[] = "No students added to the city <span class='warning icon'>!</span>";
			$problem_flag++;
		} else {
			$information[] = "Students/Youth in this city: $kids_count";
		}
		
		return array($information, $problem_flag);
	}
    
    function createCity($data) {
		$success = $this->db->insert('City', 
			array(
				'name'			=>	$data['name'], 
				'added_on'		=>	date('Y-m-d H:i:s')
			));
		$city_id = $this->db->insert_id();
				
		return $city_id;
    }
    
    function editCity($data) {
    	$this->db->where('id', $this->input->post('id'))->update('City', $data);
		return ($this->db->affected_rows() > 0) ? true : false;
    }
    
    function getCity($city_id) {
    	return $this->db->where('id',$city_id)->get('City')->row_array();
    }
    
    
	function get_all($happenning = 1) {
		$city_data = $this->db->order_by('name')->get('City')->result();
		$data = idNameFormat($city_data);

		return $data;
	}
	
	function get_unique_cities() {
		$cities = $this->get_all();

		foreach($cities as $id => $name) {
			if(preg_match('/^(.+) (\d+)$/', $name, $matches)) {
				if($matches[2] == '1') $cities[$id] = $matches[1];
				else unset($cities[$id]);
			}
		}
		return $cities;
	}

	function get_all_verticals() {
		return idNameFormat($this->db->query("SELECT id,name FROM Vertical")->result());
	}
	function get_all_regions() {
		return idNameFormat($this->db->query("SELECT id,name FROM Region")->result());
	}
}
