<?php
class Class_model extends Model {
    function Class_model() {
        // Call the Model constructor
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
    }
    
    function get_all($user_id) {
    	return $this->db->query("SELECT Class.id AS class_id, UserClass.user_id, UserClass.substitute_id, UserClass.status, Class.batch_id, Class.level_id, Class.class_on
    		FROM Class INNER JOIN UserClass ON UserClass.class_id=Class.id 
    		WHERE Class.project_id={$this->project_id} AND UserClass.user_id=$user_id")->result();
    }
    
    function get_all_by_batch($batch_id) {
    	return $this->db->query("SELECT Class.id AS class_id, UserClass.user_id, UserClass.substitute_id, UserClass.status, Class.batch_id, Class.level_id, Class.class_on
    		FROM Class INNER JOIN UserClass ON UserClass.class_id=Class.id 
    		WHERE Class.project_id={$this->project_id} AND Class.batch_id=$batch_id")->result();
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
    
    /// Get just the class information for the current level
    function get_classes_by_level($level_id) {
    	$classes = $this->db->query("SELECT * FROM Class WHERE level_id=$level_id ORDER BY class_on")->result();
    	return $classes;
    }
    
    /// Get just the class information for the current level/batch
    function get_classes_by_level_and_batch($level_id, $batch_id) {
    	$classes = $this->db->query("SELECT Class.*,UserClass.user_id,UserClass.substitute_id,UserClass.status 
    		FROM Class INNER JOIN UserClass ON Class.id=UserClass.class_id 
    		WHERE Class.level_id=$level_id AND Class.batch_id=$batch_id ORDER BY Class.class_on")->result();
    	return $classes;
    }
    
    /// Get both teacher information and class information together.
    function get_by_level($level_id) {
    	$classes = $this->db->query("SELECT Class.*,UserClass.user_id,UserClass.substitute_id,UserClass.status 
    		FROM Class INNER JOIN UserClass ON Class.id=UserClass.class_id 
    		WHERE Class.level_id=$level_id ORDER BY class_on")->result();
    	return $classes;
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
    
    function get_class($class_id) {
    	$class_details = $this->db->where('id',$class_id)->get('Class')->row_array();
    	$class_details['teachers'] = $this->db->where('class_id',$class_id)->get("UserClass")->result_array();
    	
    	return $class_details;
    }
    
    function save_class_teachers($user_class_id, $data) {
    	// When editing the class info, make sure that the credits asigned durring the last edit is removed...
    	$previous_class_data = $this->db->where('id',$user_class_id)->get('UserClass')->row_array();
    	$this->revert_user_class_credit($user_class_id, $previous_class_data);
    	
    	
    	$this->db->update('UserClass', $data, array('id'=>$user_class_id));
    	$this->calculate_users_class_credit($user_class_id, $data);

    	return $this->db->affected_rows();
    }
    
    /// Calculates the credit that should be given to the user for the given class.
    /// Argument: $user_class_id - the id of a row in the UserClass table.
    function calculate_users_class_credit($user_class_id, $data = array()) {
    	if(!$data) $data = $this->db->where('id',$user_class_id)->get('UserClass')->row_array();
    	$this->load->model('user_model','user_model');
    	
    	extract($data);
    	if($status == 'attended') {
    		if($substitute_id) {
    			// A substitute has attended the class. Substitute gets one credit, Original teacher loses one credit.
    			$this->user_model->update_credit($substitute_id, 1);
    			$this->user_model->update_credit($user_id, -1);
    		}
    	} elseif($status == 'absent') {
    		if($substitute_id) {
    			// A substitute was supposed to come - but didn't. Substitute loses two credit, Original teacher loses one credit.
    			$this->user_model->update_credit($substitute_id, -2);
    			$this->user_model->update_credit($user_id, -1);
    		} else {
    			// Absent without substitute. Teacher loses two credit.
    			$this->user_model->update_credit($user_id, -2);
    		}
    	}
    }
    
    
    /// When editing the class info, we have to make sure that the credits asigned durring the last edit is removed. Thats what this function is for
    /// Argument: $user_class_id - the id of a row in the UserClass table.
    function revert_user_class_credit($user_class_id, $data = array()) {
    	if(!$data) $data = $this->db->where('id',$user_class_id)->get('UserClass')->row_array();
    	$this->load->model('user_model','user_model');
    	
    	extract($data);
    	
    	// Note - S = Substitute Teacher, OT= Original Teacher.
    	if($status == 'attended') {
    		if($substitute_id) {
    			// A substitute had attended the class. That would have changed the credits like S = +1, OT = -1. So we make S = -1 and OT = +1 - to make the changed credits 0
    			$this->user_model->update_credit($substitute_id, -1);
    			$this->user_model->update_credit($user_id, 1);
    		}
    	} elseif($status == 'absent') {
    		if($substitute_id) {
    			// A substitute was supposed to come - but didn't. S=-2, OT=-1. So in revert, S=+2,OT=+1
    			$this->user_model->update_credit($substitute_id, +2);
    			$this->user_model->update_credit($user_id, +1);
    		} else {
    			// Absent without substitute. Teacher lost two credit. So, give them +2.
    			$this->user_model->update_credit($user_id, +2);
    		}
    	}
    	
    	
    }
}
