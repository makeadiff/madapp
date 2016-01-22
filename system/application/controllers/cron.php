<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Controller  {
	public $year = false;
	
    function Cron() {
        parent::Controller();
        header("Content-type: text/plain");

        $this->load->model('Users_model', 'users_model');
        $this->load->model('Class_model','class_model', TRUE);
        $this->load->model('Batch_model','batch_model', TRUE);
        
        $this->year = get_year();
        $this->class_model->year = $this->year;
        $this->users_model->year = $this->year;
        $this->batch_model->year = $this->year;
		$this->batch_model->project_id = 1;
	}
	
	// This is one of the most improtant functions. Makes all the classes for the next two weeks using the data in the Batch table.
	function schedule_classes($debug=0) {
		$all_batches = $this->batch_model->get_all_batches(true);
		
		if($debug) {
			print "Debug Mode\n----------\n";
			print "Total Batches: " . count($all_batches) . "\n";
		}

		// We have to add all the classes for the next two weeks.
		for($week = 0; $week < 2; $week++) {
			foreach($all_batches as $batch) {
				// if($batch->id != 1187) continue; //:DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.

				$teachers = $this->batch_model->get_batch_teachers($batch->id);
				list($hour, $min, $secs) = explode(":", $batch->class_time);
				
				// This is how we find the next sunday, monday(whatever is in the $batch->day).
				$date_interval = intval($batch->day) - date('w');
				if($date_interval <= 0) $date_interval += 7;
				$day = date('d') + $date_interval;
				
				$day = $day + ($week * 7); // We have to do this for two weeks. So in the first iteration, this will be 0 and in next it will be 7.
							
				$time = mktime($hour, $min, $secs, date('m'), $day, date("Y"));
				$date = date("Y-m-d H:i:s", $time);
				
				$debug_text = '';
				foreach($teachers as $teacher) {
					// if($teacher->id != 83172) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.

					// Make sure its not already inserted.
					if(!$this->class_model->get_by_teacher_time($teacher->id, $date, $batch->id, $teacher->level_id)) {
						$debug_text .= "\tClass by {$teacher->id} at $date\n";
						
						$class_data = array(
							'batch_id'	=> $batch->id,
							'level_id'	=> $teacher->level_id,
							'teacher_id'=> $teacher->id,
							'substitute_id'=>0,
							'class_on'	=> $date,
							'status'	=> 'projected'
						);
						$this->class_model->save_class($class_data);
					}
				}

				if($debug and count($teachers) and $debug_text) {
					// dump($teachers, $date, $batch);
					print "\n\n-------------------------------\n";
					print "Current Batch: $batch->id at center $batch->center_id\n";
					print "\tTotal Teachers in this Batch: ". count($teachers) . "\n";
					print $debug_text;
				}
			}
		}
	}
	
	/// Copies all the existing credits over to the Archive table and reset credits to 3.
	function archive_credits() {
		$last_year = $this->year - 1;
		
		$users = $this->users_model->db->query("SELECT User.id,credit FROM User INNER JOIN UserGroup ON User.id=UserGroup.user_id 
				WHERE UserGroup.group_id=9 AND user_type='volunteer'")->result(); // 9 is Teacher Group
		
		$data = array();
		$count = 0;
		foreach($users as $u) {
			$data[] = "{$u->id}, 'user_english_credit', '{$u->credit}', '$last_year', NOW()";
			$this->users_model->db->query("UPDATE User SET credit=3 WHERE id={$u->id}");
			$count++;
		}
		$this->users_model->db->query("INSERT INTO Archive(user_id, name, value, year, added_on) VALUES (" . implode("),(", $data) . ")");
		print "Saved engish credits of $count people.\n";
		
		// Interns
		$admin_users = $this->users_model->db->query("SELECT User.id,admin_credit FROM User INNER JOIN UserGroup ON User.id=UserGroup.user_id 
				WHERE UserGroup.group_id=14 AND user_type='volunteer'")->result(); // 14 is Admin Group
		
		$data = array();
		$count = 0;
		foreach($admin_users as $u) {
			$data[] = "{$u->id}, 'user_admin_credit', '{$u->admin_credit}', '$last_year', NOW()";
			$this->users_model->db->query("UPDATE User SET admin_credit=0 WHERE id={$u->id}");
			$count++;
		}
		$this->users_model->db->query("INSERT INTO Archive(user_id, name, value, year, added_on) VALUES (" . implode("),(", $data) . ")");
		print "Saved admin credits of $count people.\n";
	}

	/// In all years the center updates the date which the center started class. And there is no way to archive that before. Now, every year, we reset it to 0 - and then save it to the CenterData table.
	function archive_center_start_dates() {
		$this->load->model('Center_model','center_model');

		$last_year = $this->year - 1;

		$all_centers = $this->center_model->get_all_centers();
		foreach ($all_centers as $center) {
			if($center->class_starts_on == '0000-00-00') continue;

			$this->center_model->save_center_data($center->id, 'class_info', array(
					'year'	=> $last_year,
					'data'	=> json_encode(array('class_starts_on' => $center->class_starts_on, 'center_head_id'=> $center->center_head_id))
				));
			$this->center_model->update_center(array('rootId' => $center->id, 'class_starts_on'=>'0000-00-00'));
			print $center->class_starts_on . "\n";
		}
	}
	
	/// Sometimes, the credits go bad. In such cases, rebuild the credits using the credit history.
	function recalculate_credits($city_id=0) {
		$this->load->model('users_model');
		
		$conditions = array('user_type'=>'volunteer', 'status' => '1', 'user_group'=>9, 'project_id'=>1,'city_id'=>false);
		if($city_id) $conditions['city_id'] = $city_id;
		$all_users = $this->users_model->search_users($conditions);
		
		print "Recalculating credits of " . count($all_users) . " users.\n";
		foreach($all_users as $user) {
			print $user->id . ") " . $user->name;
			$this->users_model->year = $this->year;
			$this->users_model->recalculate_user_credit($user->id, true, true);
			print "\n";
		}
	}
	
	/// Sometimes, the classes linger in the database even after the user has been removed from the batch. This function clears that.
	function delete_orphan_classes() {
		$this->load->model('Batch_model','batch_model');
		$this->load->model('Center_model','center_model');
		$this->load->model('Level_model','level_model');
		$this->load->model('City_model','city_model');
		
		
		$all_cities = $this->city_model->get_all();
		foreach($all_cities as $city) {
			$all_centers = $this->center_model->get_all($city->id);
			
			foreach($all_centers as $center) {
				$batches = $this->batch_model->get_class_days($center->id);
				$all_levels = $this->level_model->get_all_levels_in_center($center->id);
				
				foreach($batches as $batch_id => $batch_name) {
					foreach($all_levels as $level) {
						// Get people in this level.
						$actual_teachers = $this->batch_model->get_teachers_in_batch_and_level($batch_id, $level->id);
						
						// Get people shown in madsheet in this level - from the class and userclass table.
						$shown_classes = $this->class_model->get_classes_by_level_and_batch($level->id, $batch_id);
						foreach($shown_classes as $class) {
							// If the class is in the future and the teacher is not in the batch...
							if($class->class_on > date('Y-m-d H:i:s') and 
									!in_array($class->user_id, $actual_teachers)) {
								$this->class_model->delete_future_classes($class->user_id, $batch_id, $level->id);// delete the people missing from the level.
								$actual_teachers[] = $class->user_id; // So that we won't have to delete this user all over again. The parent if condition will prevent that.
								print $class->user_id . " is missing - deleted his/her future classes.\n";
							}
							
						}
					}
				}
			}
		}
	}

	/**
	 * Cron that checks if the 5 consicutive classes happened - and reassign credits based on that.
	 * How it Works: There is two fields in the User Table - credit and consecutive_credit. Credit holds the total credit of the volunteer. consecutive_credit holds the credit they got by doing consecutive classes. 
	 *	credit field is other type of credit + consecutive_credit. What the function does is basically get the list of all volunteers, go thru each one, find how many consecutive credits they deserve, then if there 
	 *	is ANY change(credits should be more that what it is currently - or should be less than what it is), then the script updates the table with the accurate number.
	 *	The rest of MADApp just have to worry about the credit field as it holds the full credit. Only the script that deals with consecutive credit will use the other field.
	 */
	function consecutive_class_credit($city_id = 0) {
		$conditions = array('user_type'=>'volunteer', 'status' => '1', 'user_group'=>9, 'project_id'=>1,'city_id'=>$city_id);
		$all_users = $this->users_model->search_users($conditions);
		
		print "Recalculating credits of " . count($all_users) . " users.\n";
		foreach($all_users as $user) {
			print $user->id . ") " . $user->name;
			$this->users_model->year = $this->year;
			$this->users_model->recalculate_user_consecutive_class_credit($user->id, true, true);
			print "\n";
		}
	}

	function find_added_date_of_kids($center_id) {
		$all_kids_in_center = $this->users_model->db->query("SELECT id,name, status FROM Student WHERE center_id=$center_id")->result();
		foreach ($all_kids_in_center as $kid) {
			$last_class_on = $this->users_model->db->query("SELECT MIN(C.class_on) AS first_class FROM Class C INNER JOIN StudentClass SC ON C.id=SC.student_id WHERE student_id=".$kid->id)->result();
			dump($last_class_on, $kid);
		}

	}

	/// Copy over last year's Class structure. Rename all fancy name to ABC, and increment their grade by one. '7 Rainbow class' becomes '8 A'
	function copy_class_sturcture() {
		$current_year = get_year();
		$last_year = $current_year - 1;

		$alphabets = array('A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');

		// Copy over batches to the new year
		$batch_mapping = array();
		$batches = $this->users_model->db->query("SELECT * FROM Batch WHERE year='$last_year' AND status='1'")->result();
		print "Copying " . count($batches) . " batches<br />\n";
		foreach($batches as $b) {
			$this->users_model->db->query("INSERT INTO Batch (day,class_time,batch_head_id,center_id,project_id, year, status) 
				VALUES('{$b->day}','{$b->class_time}','{$b->batch_head_id}','{$b->center_id}','{$b->project_id}','$current_year', '{$b->status}')");
			$batch_mapping[$b->id] = mysql_insert_id();
		}

		// Copy over levels to the new year
		$level_mapping = array();

		$levels = $this->users_model->db->query("SELECT * FROM Level WHERE year='$last_year' AND status='1'")->result();
		$uniqizer = array();
		print "Copying " . count($levels) . " levels<br />\n";
		foreach($levels as $b) {
			$status = $b->status;
			$new_grade = intval($b->grade) + 1;
			if($new_grade == 11) $status = 0;

			$name = addslashes($b->name);
			if(!isset($uniqizer[$b->center_id])) {
				$uniqizer[$b->center_id] = array();
			}
			if(!isset($uniqizer[$b->center_id][$new_grade])) {
				$uniqizer[$b->center_id][$new_grade] = 0;
			}

			$name = $alphabets[$uniqizer[$b->center_id][$new_grade]];
			$uniqizer[$b->center_id][$new_grade]++;

			$this->users_model->db->query("INSERT INTO Level (name,grade,center_id,project_id, year, status) 
				VALUES('$name','$new_grade','{$b->center_id}','{$b->project_id}','$current_year', '{$status}')");

			$level_mapping[$b->id] = mysql_insert_id();
		}

		// Copy over StudentLevel table. Just relations.
		$student_levels = $this->users_model->db->query("SELECT * FROM StudentLevel WHERE level_id IN (" . implode(",", array_keys($level_mapping)) .")")->result();
		print "Copying " . count($student_levels) . " student level connections<br />\n";
		foreach($student_levels as $sl) {
			$this->users_model->db->query("INSERT INTO StudentLevel (student_id, level_id) VALUES('{$sl->student_id}','" . $level_mapping[$sl->level_id] . "')");
		}

		// Create BatchLevel table data using the data in UserBatch table
		$user_batch = $this->users_model->db->query("SELECT * FROM UserBatch WHERE level_id IN (" . implode(",", array_keys($level_mapping)) .")")->result();
		print "Copying " . count($user_batch) . " Batch Level connections<br />\n";
		foreach($user_batch as $ub) {
			$this->users_model->db->query("INSERT INTO BatchLevel (batch_id, level_id, year) VALUES('" . $batch_mapping[$ub->batch_id] . "','" . $level_mapping[$ub->level_id] . "', '{$this->year}')");
		}

	}
               
}

