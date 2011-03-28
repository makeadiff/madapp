<?php
class Report_model extends Model {
    function Report_model() {
        // Call the Model constructor
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
    }
    
    
	function get_users_with_low_credits() {
		return $this->db->query("SELECT id AS user_id,name,credit FROM User WHERE city_id={$this->city_id} AND project_id={$this->project_id} AND credit<1")->result();
    }
    
    function get_users_absent_without_substitute() {
    	return $this->db->query("SELECT UserClass.user_id,User.name,Class.class_on,Center.name AS center_name
    		FROM UserClass INNER JOIN User ON User.id=UserClass.user_id 
    			INNER JOIN Class ON UserClass.class_id=Class.id INNER JOIN Batch ON Batch.id=Class.batch_id
    			INNER JOIN Center ON Batch.center_id=Center.id
    		WHERE User.city_id={$this->city_id} AND User.project_id={$this->project_id} 
    			AND UserClass.substitute_id=0 AND UserClass.status='absent'")->result();
    }
    
    function get_volunteer_requirements() {
    	return $this->db->query("SELECT Center.name, SUM(requirement) AS requirement
    		FROM UserBatch 
    		INNER JOIN `Level` ON UserBatch.level_id = Level.id 
    		INNER JOIN Center ON Center.id=Level.center_id 
    		WHERE requirement > 0 AND Center.city_id={$this->city_id} GROUP BY Level.center_id")->result();
    }

}
