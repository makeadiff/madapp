<?php
class Batch_model extends Model {
    function Batch_model() {
        // Call the Model constructor
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
		$this->year = $this->ci->session->userdata('year');
    }
    
    function get_batch($batch_id) {
    	$data = $this->db->where('id', $batch_id)->get("Batch")->row();
    	$data->name = $this->create_batch_name($data->day, $data->class_time);
    	return $data;
    }
    function get_batch_as_array($batch_id) {
    	return $this->db->where('id', $batch_id)->get("Batch")->row_array();
    }
    
    function get_batch_teachers($batch_id) {
    	return $this->db->query("SELECT User.id, User.name, UserBatch.level_id FROM User 
    				INNER JOIN UserBatch ON User.id=UserBatch.user_id 
    				WHERE UserBatch.batch_id={$batch_id} AND User.project_id={$this->project_id}")->result();
    }
    
    function get_teachers_in_batch_and_level($batch_id, $level_id) {
    	return colFormat($this->db->query("SELECT user_id FROM UserBatch WHERE batch_id={$batch_id} AND level_id={$level_id} AND user_id!=0")->result());
    }
    function get_teachers_in_batch($batch_id) {
    	return $this->db->query("SELECT user_id AS id FROM UserBatch WHERE batch_id={$batch_id}")->result();
    }
    
    function get_all_batches($class_starts_check=false) {
		$start_check = '';
		if($class_starts_check) $start_check = " AND Center.class_starts_on<CURDATE()"; // AND Center.class_starts_on!='0000-00-00'";
		
		return $this->db->query("SELECT Batch.* FROM Batch INNER JOIN Center ON Batch.center_id=Center.id 
			WHERE Batch.project_id={$this->project_id} AND Batch.year={$this->year} AND Center.status='1' $start_check")->result();
    }
    
    function get_batches_in_level($level_id) {
    	return $this->db->query("SELECT Batch.* FROM Batch INNER JOIN UserBatch ON Batch.id=UserBatch.batch_id 
			WHERE UserBatch.level_id=$level_id AND Batch.project_id={$this->project_id} AND Batch.year={$this->year}")->result();
    }
    
    function get_volunteer_requirement_in_batch($batch_id) {
    	return $this->db->query("SELECT level_id AS id, requirement AS name FROM UserBatch WHERE batch_id=$batch_id AND user_id=0")->result();
    }
    
    function get_levels_in_batch($batch_id) {
    	return $this->db->query("SELECT Level.id,Level.name FROM Level INNER JOIN UserBatch ON Level.id=UserBatch.level_id 
			WHERE UserBatch.batch_id=$batch_id AND Level.project_id={$this->project_id}")->result();
    }
    
    function get_batches_in_center($center_id) {
    	return $this->db->where('center_id',$center_id)->where('project_id', $this->project_id)->where('year', $this->year)->orderby('day')->get('Batch')->result();
    }
    
    function create_batch_name($day, $time) {
    	$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		return $day_list[$day] . ' ' . date('h:i A', strtotime('2011-01-01 '.$time));
    }
	
	function get_center_of_batch($batch_id) {
		return $this->db->where('id',$batch_id)->get('Batch')->row()->center_id;
	}

    /// :DEPRECIATED:
    function get_subjects_in_batch($batch_id) {
        $students = $this->db->query("SELECT Subject.id,Subject.name FROM Subject 
            INNER JOIN BatchSubject ON BatchSubject.Subject_id=Subject.id 
            WHERE BatchSubject.batch_id=$batch_id ORDER BY Subject.name")->result();
        
        $students_ids = array();
        foreach($students as $student) $students_ids[$student->id] = $student->name;
        return $students_ids;
    }

    
    /// Get the details about the batch head of a given batch.
    function get_batch_head($batch_id) {
		return $this->db->query("SELECT User.id, User.name, User.phone FROM User INNER JOIN Batch ON User.id=Batch.batch_head_id WHERE Batch.id=$batch_id")->row();
    }
  	
  	function get_class_days($center_id) {
		$class_days = $this->db->query("SELECT id,day,class_time FROM Batch WHERE center_id=$center_id AND project_id={$this->project_id} AND year={$this->year} ORDER BY day")->result();
		$return = array();
		foreach($class_days as $batch) {
			$return[$batch->id] = $this->create_batch_name($batch->day, $batch->class_time);
		}
		return $return;
	}
	
	function set_volunteer_requirement($batch_id, $level_id, $requirement) {
		if(!$requirement) return;
		
		$this->db->insert("UserBatch", array(
			'batch_id'	=> $batch_id,
			'level_id'	=> $level_id,
			'requirement'=>$requirement,
			'user_id'	=> 0
		));
	}
	
    function create($data) {
    	$data['project_id'] = $this->project_id;
    	$data['year'] = $this->year;
        $selected_subjects = $data['subjects'];

        unset($data['subjects']);
    	$this->db->insert("Batch", $data);
        $batch_id = $this->db->insert_id();

        if($selected_subjects) {
            foreach($selected_subjects as $subject_id) {
                $this->db->insert("BatchSubject", array(
                    'batch_id'  => $batch_id,
                    'subject_id'=> $subject_id
                ));
            }
        }
    	
    	if($data['batch_head_id'] > 0) {
			$this->load->model('users_model');
			$this->users_model->adduser_to_group($data['batch_head_id'], array(8));// Add the batch head to Batch Head group.
		}

        return $batch_id;
    }
    
    function edit($batch_id, $data) {
		$old_batch_head = $this->get_batch_head($batch_id);

        $selected_subjects = $data['subjects'];
        unset($data['subjects']);

		$this->db->where('id', $batch_id)->update('Batch',$data);
		if($data['batch_head_id'] > 0) {
			$this->load->model('users_model');
			$this->users_model->remove_user_from_group($old_batch_head->id,8);// Remove old batch head from Batch Head Group.
			$this->users_model->adduser_to_group($data['batch_head_id'], array(8));// Add the batch head to Batch Head group.
		}

        $this->db->delete("BatchSubject", array('batch_id'=>$batch_id));
        foreach($selected_subjects as $subject_id) {
            $this->db->insert("BatchSubject", array(
                'batch_id'  => $batch_id,
                'subject_id'=> $subject_id
            ));
        }
    }
    
    /// Delete the batch, the user batch connection, the batch subject connection - and the batch level connection.
    function delete($batch_id) {
    	$this->db->delete('Batch', array('id'=>$batch_id));
    	$this->db->delete('UserBatch', array('batch_id'=>$batch_id));
        $this->db->delete('BatchSubject', array('batch_id'=>$batch_id));
        $this->db->delete('BatchLevel', array('batch_id'=>$batch_id));
    }

    /// Delet all the level connection that this batch has. Important to do that before insterting the connection.
    function delete_batch_level_connection($batch_id) {
        $this->db->delete('BatchLevel', array('batch_id'=>$batch_id, 'year'=>$this->year));
    }
    /// Insert the batch level connection with the current year.
    function save_batch_level_connection($batch_id, $level_id) {
        $this->db->insert("BatchLevel", array(
                'batch_id'  => $batch_id,
                'level_id'  => $level_id,
                'year'      => $this->year,
            ));
    }

    /// Find out all the batch level connection in the given center.
    function get_batch_level_connections($center_id) {
        $all_batchs = $this->get_batches_in_center($center_id);
        $batch_ids = array_map(function($x) {
            return $x->id;
        },$all_batchs);

        $batch_level_connections = $this->db->query("SELECT batch_id,level_id FROM BatchLevel WHERE year='{$this->year}' AND batch_id IN (".implode(",", $batch_ids).")")->result();

        return $batch_level_connections;
    }

    function get_level_connection($batch_id) {
        $levels = $this->db->query("SELECT level_id FROM BatchLevel WHERE year='{$this->year}' AND batch_id=$batch_id")->result();
        return colFormat($levels);
    }
}
