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
    	return $this->db->get('City')->result();
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
