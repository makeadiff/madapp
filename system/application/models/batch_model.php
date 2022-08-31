<?php
class Batch_model extends Model {
	function Batch_model() {
		// Call the Model constructor
		parent::Model();
		
		$this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
		$this->year = $this->ci->session->userdata('year');
		if(!$this->project_id) $this->project_id = 1;
		if(!$this->year) $this->year = get_year();
		if(!$this->city_id and isset($_SESSION['city_id'])) $this->city_id = $_SESSION['city_id'];
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
				WHERE UserBatch.batch_id={$batch_id} AND UserBatch.role='teacher'")->result();
	}
	
	function get_teachers_in_batch_and_level($batch_id, $level_id) {
		return colFormat($this->db->query("SELECT DISTINCT UB.user_id FROM UserBatch UB
				INNER JOIN Batch B ON B.id=UB.batch_id
				WHERE batch_id={$batch_id} AND level_id={$level_id} AND UB.user_id!=0 AND B.year={$this->year}")->result());
	}
	function get_teachers_in_batch($batch_id, $teacher_group_id = 9) {
		return $this->db->query("SELECT UserBatch.user_id AS id FROM UserBatch 
				INNER JOIN UserGroup ON UserBatch.user_id=UserGroup.user_id 
				WHERE UserBatch.batch_id={$batch_id} AND UserBatch.role='teacher' AND UserBatch.user_id != 0 AND UserGroup.group_id=$teacher_group_id")->result();
	}

	function get_batches_connected_to_user($user_id) {
		return colFormat($this->db->query("SELECT batch_id FROM UserBatch WHERE user_id=$user_id AND role='mentor'")->result());
	}
	
	function get_all_batches($class_starts_check=false) {
		$start_check = '';
		if($class_starts_check) $start_check = " AND Center.class_starts_on<CURDATE() AND Center.class_starts_on!='0000-00-00'";
		
		return $this->db->query("SELECT Batch.* FROM Batch INNER JOIN Center ON Batch.center_id=Center.id 
				WHERE Batch.project_id={$this->project_id} AND Batch.status='1' AND Batch.year={$this->year} AND Center.status='1' $start_check")->result();
	}
	
	function get_batches_in_level($level_id) {
		// return $this->db->query("SELECT Batch.* FROM Batch
		// 		INNER JOIN BatchLevel BL ON BL.batch_id=Batch.id
		// 		INNER JOIN UserBatch ON Batch.id=UserBatch.batch_id
		// 	 	WHERE UserBatch.level_id=$level_id AND BL.level_id=$level_id AND Batch.year={$this->year}")->result();

		return $this->db->query("SELECT Batch.* FROM Batch
				INNER JOIN UserBatch ON Batch.id=UserBatch.batch_id
			 	WHERE UserBatch.level_id=$level_id AND Batch.year={$this->year} AND Batch.status='1'")->result();
	}
	function get_batches_connected_to_level($level_id) {
		// return $this->db->query("SELECT Batch.* FROM Batch INNER JOIN BatchLevel ON Batch.id=BatchLevel.batch_id 
		// 		WHERE BatchLevel.level_id=$level_id AND Batch.year={$this->year}")->result();

		return $this->db->query("SELECT Batch.* FROM Batch INNER JOIN UserBatch ON Batch.id=UserBatch.batch_id 
				WHERE UserBatch.level_id=$level_id AND Batch.year={$this->year} AND Batch.status='1'")->result();
	}

	// :DEPRICATED:	
	function get_volunteer_requirement_in_batch($batch_id) {
		return $this->db->query("SELECT level_id AS id, requirement AS name FROM UserBatch WHERE batch_id=$batch_id AND user_id=0")->result();
	}
	
	function get_levels_in_batch($batch_id) {
		// :DEPRICATED:	Pre Madnet logic.
		// return $this->db->query("SELECT DISTINCT Level.id,CONCAT(Level.grade, ' ', Level.name) AS name FROM Level 
		// 		INNER JOIN BatchLevel ON Level.id=BatchLevel.level_id 
		// 		WHERE BatchLevel.batch_id=$batch_id AND BatchLevel.year={$this->year}")->result();

		return $this->db->query("SELECT DISTINCT Level.id,CONCAT(Level.grade, ' ', Level.name) AS name FROM Level 
		 		INNER JOIN UserBatch ON Level.id=UserBatch.level_id 
		 		WHERE UserBatch.batch_id=$batch_id")->result();
	}
	
	function get_batches_in_center($center_id, $project_id = false) {
		if(!$project_id) $project_id = $this->project_id;

		$batches = $this->db->where('status','1')->where('center_id',$center_id)->where('project_id', $project_id)->where('year', $this->year)
					->orderby('day')->get('Batch')->result();
		
		foreach ($batches as $index => $batch) {
			$batches[$index]->name = $this->create_batch_name($batch->day, $batch->class_time);
		}

		return $batches;
	}
	
	function create_batch_name($day, $time) {
		$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		return $day_list[$day] . ' ' . date('h:i A', strtotime('2011-01-01 '.$time));
	}
	
	function get_center_of_batch($batch_id) {
		return $this->db->where('id',$batch_id)->get('Batch')->row()->center_id;
	}

	/// Get the details about the batch head of a given batch.
	function get_batch_head($batch_id) {
		return $this->db->query("SELECT User.id, User.name, User.phone 
				FROM User 
				INNER JOIN UserBatch ON User.id=UserBatch.user_id
				WHERE UserBatch.role='mentor' AND UserBatch.batch_id=$batch_id")->row();
	}
	
	function get_class_days($center_id) {
		$class_days = $this->db->query("SELECT id,day,class_time FROM Batch 
			WHERE center_id=$center_id AND project_id={$this->project_id} AND year={$this->year} AND status='1' 
			ORDER BY day")->result();
		$return = array();
		foreach($class_days as $batch) {
			$return[$batch->id] = $this->create_batch_name($batch->day, $batch->class_time);
		}
		return $return;
	}
	
	// :DEPRICATED:
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

		$this->db->insert("Batch", $data);
		$batch_id = $this->db->insert_id();

		if($data['batch_head_id'] > 0) {
			$this->db->insert("UserBatch", [
				'user_id' => $data['batch_head_id'],
				'batch_id' => $batch_id,
				'level_id' => '0',
				'role'=>'mentor',
				'added_on' => date('Y-m-d H:i:s')
			]);

			$this->load->model('users_model');
			$mentor_group_id = 8; // Ed Support Mentor
			if($this->project_id == 2) $mentor_group_id = 375; // Foundational Fellow - this is the mentor for FP.
			
			$this->users_model->adduser_to_group($data['batch_head_id'], array($mentor_group_id));// Add the batch head to Batch Head group.
		}

		return $batch_id;
	}
	
	function edit($batch_id, $data) {
		$old_batch_head = $this->get_batch_head($batch_id);

		$this->db->where('id', $batch_id)->update('Batch',$data);
		if($data['batch_head_id'] and $old_batch_head->id != $data['batch_head_id']) { // If new mentor assigned
			$this->load->model('users_model');
			$this->users_model->remove_user_from_group($old_batch_head->id,8);// Remove old batch head from Batch Head Group.
			$this->users_model->adduser_to_group($data['batch_head_id'], array(8));// Add the batch head to Batch Head group.


			if($old_batch_head->id) {
				$this->db->delete('UserBatch', ['user_id' => $old_batch_head->id, 'batch_id' => $batch_id, 'role' => 'mentor']);
			}

			$this->db->insert("UserBatch", [
				'user_id'  => $data['batch_head_id'],
				'batch_id' => $batch_id,
				'level_id' => '0',
				'role'	   => 'mentor',
				'added_on' => date('Y-m-d H:i:s')
			]);
		}

	}
	
	/// Delete the batch, the user batch connection, the batch subject connection - and the batch level connection.
	function delete($batch_id) {
		// :TODO: Soft delete
		$this->db->delete('Batch', array('id'=>$batch_id));
		$this->db->delete('UserBatch', array('batch_id'=>$batch_id));
		$this->db->delete('BatchSubject', array('batch_id'=>$batch_id));
		$this->db->delete('BatchLevel', array('batch_id'=>$batch_id));

		// :TODO: Should we delete the future classes of this batch? Class.batch_id = $batch_id 
	}

	/// Delete all the level connection that this batch has. Important to do that before insterting the connection.
	function delete_batch_level_connection($batch_id) {
		$this->db->delete('BatchLevel', array('batch_id'=>$batch_id, 'year'=>$this->year));
	}
	/// Delete all the level connection that this batch has from the UserBatch Table.
	function delete_user_batch_level_connection($batch_id, $level_id) {
		$this->db->delete('UserBatch', array('batch_id'=>$batch_id, 'level_id' => $level_id));
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
	function get_batch_level_connections($center_id, $project_id = false, $connector = 'userbatch') {
		if(!$project_id) $project_id = $this->project_id;

		$all_batchs = $this->get_batches_in_center($center_id, $project_id);
		$batch_ids = array_map(function($x) {
			return $x->id;
		},$all_batchs);

		$batch_level_connections = array();

		if($batch_ids) {
			if(strtolower($connector) == 'batchlevel') { // Need this for legacy mode. 
				$batch_level_connections = $this->db->query("SELECT batch_id,level_id FROM BatchLevel 
						WHERE year='{$this->year}' AND batch_id IN (".implode(",", $batch_ids).")")->result();
			
			} else {
				$batch_level_connections = $this->db->query("SELECT batch_id,level_id FROM UserBatch 
			 			WHERE batch_id IN (".implode(",", $batch_ids).")")->result();
			}
		}

		return $batch_level_connections;
	}

	function get_level_connection($batch_id) {
		// $levels = $this->db->query("SELECT level_id FROM BatchLevel WHERE year='{$this->year}' AND batch_id=$batch_id")->result();

		$levels = $this->db->query("SELECT DISTINCT level_id FROM UserBatch WHERE batch_id=$batch_id")->result();
		return colFormat($levels);
	}
}
