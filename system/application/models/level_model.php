<?php
class Level_model extends Model {
    function Level_model() {
        // Call the Model constructor
        parent::Model();
    }
    
	/**
    * Function to getlevel
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function get_all_levels() {
		return $this->db->where('project_id', 1)->get('Level')->result();
	}
	
	function get_level($level_id) {
		return $this->db->where('id', $level_id)->get('Level')->row();
	}
	
}
