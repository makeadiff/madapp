<?php
class Class_model extends Model {
    function Class_model() {
        // Call the Model constructor
        parent::Model();
    }
    
    function get_all($user_id) {
    	return $this->db->query("SELECT Class.id AS class_id, UserClass.substitute_id, UserClass.status, Class.batch_id, Class.level_id, Class.class_on
    		FROM Class INNER JOIN UserClass ON UserClass.class_id=Class.id WHERE Class.project_id=1 AND UserClass.user_id=$user_id")->result();
    }
    
    function save_class($data) {
    	// Try to find the class if the necessay data. Any class can be identified with the batch_id, level_id and the time of the class.
    	$class_id = $this->get_by_batch_level_time($data['batch_id'], $data['level_id'], $data['class_on']);
    	
    	// If the class is not found, create one.
    	if(!$class_id) {
			$this->db->insert('Class', array(
					'batch_id'	=> $data['batch_id'],
					'level_id'	=> $data['level_id'],
					'project_id'=> 1,
					'class_on'	=> $data['class_on']
				));
			$class_id = $this->db->insert_id();
		}
		
		// Add the given user to the class.
	    $this->db->insert('UserClass', array(
	    		'user_id'	=> $data['teacher_id'],
	    		'class_id'	=> $class_id,
	    		'substitute_id'=>0,
	    		'status'	=> 'projected'
	    	));
	    
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
    }
    
    // Returns the class id.
    function get_by_batch_level_time($batch_id, $level_id, $class_on) {
    	$class = $this->db->where('batch_id', $batch_id)->where('level_id', $level_id)->where('class_on',$class_on)->get("Class")->row();
    	if($class) return $class->id;
    	return 0;
    }
    
    function get_by_teacher_time($teacher_id, $time) {
    	return $this->db->query("SELECT Class.id FROM Class 
    		INNER JOIN UserClass on UserClass.class_id=Class.id 
    		WHERE UserClass.user_id=$teacher_id AND Class.class_on='$time'")->result();
    }
}
