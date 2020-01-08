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
		$all_projects = idNameFormat($this->db->query("SELECT id, name FROM Project WHERE status='1'")->result());
		$project_ids = array_keys($all_projects);
		$year_end_time = ($this->year+1) . '-03-31 23:59:59';

		if($debug) print "Debug Mode\n----------\n";

		foreach ($project_ids as $project_id) {
			$this->batch_model->project_id = $project_id;
			$this->class_model->project_id = $project_id;

			if($debug) print "Creating classes for project " . $all_projects[$project_id] . "\n";

			$all_batches = $this->batch_model->get_all_batches(true);
			if($debug) print "Total Batches: " . count($all_batches) . "\n";

			for($week = 0; $week < 2; $week++) {
				foreach ($all_batches as $batch) {
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
					if($date >= $year_end_time) continue; // If the classes fall on the next year, don't make them.

					foreach($teachers as $teacher) {
						// if($teacher->id != 83172) continue; // :DEBUG: Use this to localize the issue. I would recommend keeping this commented. You'll need it a lot.

						// Make sure its not already inserted.
						if(!$this->class_model->get_by_teacher_time($teacher->id, $date, $batch->id, $teacher->level_id)) {
							$debug_text .= "\tClass by {$teacher->id} at $date\n";

							$class_data = array(
								'batch_id'	=> $batch->id,
								'level_id'	=> $teacher->level_id,
								'teacher_id'=> $teacher->id,
								'project_id'=> $batch->project_id,
								'substitute_id'=>0,
								'class_on'	=> $date,
								'status'	=> 'projected'
							);
							$this->class_model->save_class($class_data);
						}

						if($debug and count($teachers) and $debug_text) {
							// dump($teachers, $date, $batch);
							print "-------------------------------\n";
							print "Current Batch: $batch->id at center $batch->center_id\n";
							print "\tTotal Teachers in this Batch: ". count($teachers) . "\n";
							print $debug_text;
						}
					}
				}
			}

			if($debug) print "======================================\n";
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
			$this->users_model->db->query("UPDATE User SET credit=3 WHERE id={$u->id}"); // :TODO: Bad idea to do it here - in case the next query doesn't work, we lose data.
			$count++;
		}
		$this->users_model->db->query("INSERT INTO Archive(user_id, name, value, year, added_on) VALUES (" . implode("),(", $data) . ")");
		print "Saved engish credits of $count people.\n";
	}

	/// In all years the center updates the date which the center started class. And there is no way to archive that before. Now, every year, we reset it to 0 - and then save it to the CenterData table.
	function archive_center_start_dates() {
		$this->load->model('Center_model','center_model');

		$last_year = $this->year - 1;

		$all_centers = $this->center_model->get_all_centers();
		foreach ($all_centers as $center) {
			if($center->class_starts_on == '0000-00-00' or !$center->class_starts_on) continue;

			$this->center_model->save_center_data($center->id, 'class_info', false, array(
					'year'	=> $last_year,
					'data'	=> json_encode(array('class_starts_on' => $center->class_starts_on, 'center_head_id'=> $center->center_head_id))
				));
			$this->center_model->update_center(array('rootId' => $center->id, 'class_starts_on'=>'0000-00-00'));

			print "Archived {$center->name} - start date - {$center->class_starts_on}\n";
		}
	}

	/// Sometimes, the credits go bad. In such cases, rebuild the credits using the credit history.
	function recalculate_credits($city_id=0, $user_id = 0) {
		$this->load->model('users_model');

		if($user_id) {
			$all_users = json_decode('[{"id":'.$user_id.', "name": "User"}]');
		} else {
			$conditions = ['user_type'=>'volunteer', 'status' => '1', 'user_group'=>[9, 376], 'city_id'=>false];
			if($city_id) $conditions['city_id'] = $city_id;
			$all_users = $this->users_model->search_users($conditions);
		}

		print "Recalculating credits of " . count($all_users) . " users.\n";
		foreach($all_users as $user) {
			print $user->id . ") " . $user->name . "\n";
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

	function find_added_date_of_kids($center_id) {
		$all_kids_in_center = $this->users_model->db->query("SELECT id,name, status FROM Student WHERE center_id=$center_id")->result();
		foreach ($all_kids_in_center as $kid) {
			$last_class_on = $this->users_model->db->query("SELECT MIN(C.class_on) AS first_class FROM Class C INNER JOIN StudentClass SC ON C.id=SC.student_id WHERE student_id=".$kid->id)->result();
			dump($last_class_on, $kid);
		}

	}

	/// When the year changes, copy over the last year's usergroup structure to the current year. That way, we'll have an archive of people's profile.
	function copy_user_groups() {
		$current_year = get_year();
		$last_year = $current_year - 1;

		// Copy all user groups other than fellow/strat ones. Those change every year.
		$fellow_strat_group_ids = colFormat($this->users_model->db->query("SELECT id FROM `Group` WHERE (type='fellow' OR type='strat') AND group_type='normal' AND status='1'")->result());

		$this->users_model->db->query("INSERT INTO UserGroup(user_id, group_id, year)
										SELECT user_id, group_id, '$current_year' FROM UserGroup
										INNER JOIN User ON User.id = UserGroup.user_id
											WHERE UserGroup.year='$last_year'
												AND UserGroup.group_id NOT IN (" . implode(",", $fellow_strat_group_ids) . ")
												AND User.user_type = 'volunteer'
												AND User.status = 1");
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
			$batch_mapping[$b->id] = $this->users_model->db->insert_id();
		}

		// Copy over levels to the new year
		$level_mapping = array();

		$levels = $this->users_model->db->query("SELECT * FROM Level WHERE year='$last_year' AND status='1'")->result();
		$uniqizer = array();
		print "Copying " . count($levels) . " levels<br />\n";
		foreach($levels as $l) {
			$status = $l->status;
			$new_grade = intval($l->grade) + 1;
			if($new_grade == 14) $status = 0; // If people over 13 is upgraded to a deleted level.

			$name = addslashes($l->name);
			if(!isset($uniqizer[$l->center_id])) {
				$uniqizer[$l->center_id] = array();
			}
			if(!isset($uniqizer[$l->center_id][$new_grade])) {
				$uniqizer[$l->center_id][$new_grade] = 0;
			}

			$name = $alphabets[$uniqizer[$l->center_id][$new_grade]];
			$uniqizer[$l->center_id][$new_grade]++;

			$this->users_model->db->query("INSERT IGNORE INTO Level (name,grade,center_id,project_id, year, status)
				VALUES('$name','$new_grade','{$l->center_id}','{$l->project_id}','$current_year', '{$status}')");

			$level_mapping[$l->id] = $this->users_model->db->insert_id();
		}

		// Copy over StudentLevel table. Just relations.
		$student_levels = $this->users_model->db->query("SELECT * FROM StudentLevel WHERE level_id IN (" . implode(",", array_keys($level_mapping)) .")")->result();
		print "Copying " . count($student_levels) . " student level connections<br />\n";
		foreach($student_levels as $sl) {
			$this->users_model->db->query("INSERT IGNORE INTO StudentLevel (student_id, level_id) VALUES('{$sl->student_id}','" . $level_mapping[$sl->level_id] . "')");
		}

		// Create BatchLevel table data using the data in UserBatch table
		$user_batch = $this->users_model->db->query("SELECT * FROM UserBatch WHERE level_id IN (" . implode(",", array_keys($level_mapping)) .")")->result();
		print "Copying " . count($user_batch) . " Batch Level connections<br />\n";
		foreach($user_batch as $ub) {
			$this->users_model->db->query("INSERT IGNORE INTO BatchLevel (batch_id, level_id, year) VALUES('" . $batch_mapping[$ub->batch_id] . "','" . $level_mapping[$ub->level_id] . "', '{$this->year}')");
		}


		/// In case you want to delete the data after you tried to insert it and something went wrong, use these queries to set it right...
		// DELETE FROM Batch WHERE year=2017
		// DELETE FROM Level WHERE year=2017
		// DELETE FROM StudentLevel WHERE level_id IN (SELECT id FROM Level WHERE year=2017)
		// DELETE FROM BatchLevel WHERE level_id IN (SELECT id FROM Level WHERE year=2017)
	}

	/// This will send an reminder to all teahers/mentors who havne't updated their madapp attendance after 2 hours of the eclass ending.
	function send_sms_reminder_to_update_madapp() {
		// Find classes that was sceduled to happen 4 hours ago - 2 hour class + 2 hours buffer
		// WHERE C.class_on BETWEEN DATE_SUB(NOW(), INTERVAL 5 HOUR) AND DATE_SUB(NOW(), INTERVAL 4 HOUR)
		$this->load->database();
		$this->load->library('sms');
		date_default_timezone_set('Asia/Calcutta');
		$this->debug = true;
		$now = new DateTime("now");
		$timestamp= time();
		//On Monday, it resets the message_sent feild to 0

		//extracting the phone number of TEACHERS who havent updated the child attendance in the next 5 hours, add center slot and class_time to this table.
		$teacher_message_record=$this->db->query("SELECT U.id as user_id, U.phone, U.name,
														CASE
														WHEN B.day= 1 THEN 'Sunday'
														WHEN B.day= 2 THEN 'Monday'
														WHEN B.day= 3 THEN 'Tuesday'
														WHEN B.day= 4 THEN 'Wednesday'
														WHEN B.day= 5 THEN 'Thursday'
														WHEN B.day= 6 THEN 'Friday'
														ELSE 'Saturday' end AS week_day,
														B.class_time, C.name as center From
														User U INNER JOIN UserBatch S on U.id = S.user_id
														INNER JOIN Batch B on S.batch_id = B.id
														INNER JOIN Center C on C.id = B.center_id WHERE user_id IN
														(SELECT user_id FROM UserClass WHERE class_id IN
														 (SELECT id FROM Class WHERE class_on < Date_Add(SYSDATE(),INTERVAL -5 HOUR) AND DATE(class_on) = CURDATE() AND class_on <= NOW() AND updated_by_teacher=0)
														);"
														                                );

		// SELECT phone, user_id, name, center, week_day, class_time FROM UserMessage
		// 						 WHERE user_id IN (SELECT user_id FROM UserClass
  //            								  WHERE class_id IN (SELECT id FROM Class
  //                           									 WHERE class_on < Date_Add(SYSDATE(),INTERVAL 5 HOUR) AND class_on >CURDATE() AND updated_by_teacher=0
  //           								  					)
  //                               			  )
		foreach($teacher_message_record->result() as $teacher_message_record_row){
			$name = ucfirst(@reset(explode(' ', $teacher_message_record_row->name)));
            $center= $teacher_message_record_row->center;
            $slot= $teacher_message_record_row->week_day;
            $time = date('h:i A', strtotime('2018-08-29 ' . $teacher_message_record_row->class_time)); // Random date. No meaning to it.

			//Message for teachers can be edited from here
			$teacher_message = "Hi $name,\n\nThe student attendance for your class in $center on $slot at $time has not been marked yet.\n\nYou can update it here:\nbit.ly/makeadiff-madapp";

			if($this->debug) echo "Message to $teacher_message_record_row->phone: $teacher_message<br>";
			else $this->sms->send($teacher_message_record_row->phone,$teacher_message);
			//Updating the message sent status to 1 so that in a week the teacher will only receive 1 message.
		}

		//extracting the phone number of MENTORS who havent updaed the teacher attendance in the next 5 hours.
		$mentor_message_record=$this->db->query("SELECT U.id as user_id, U.phone, U.name,
														CASE
														WHEN B.day= 1 THEN 'Sunday'
														WHEN B.day= 2 THEN 'Monday'
														WHEN B.day= 3 THEN 'Tuesday'
														WHEN B.day= 4 THEN 'Wednesday'
														WHEN B.day= 5 THEN 'Thursday'
														WHEN B.day= 6 THEN 'Friday'
														ELSE 'Saturday' end AS week_day,
														B.class_time, C.name as center From
														User U INNER JOIN UserBatch S on U.id = S.user_id
														INNER JOIN Batch B on S.batch_id = B.id
														INNER JOIN Center C on C.id = B.center_id WHERE user_id IN (SELECT batch_head_id FROM Batch WHERE id IN (SELECT batch_id from Class WHERE updated_by_mentor=0 AND class_on < Date_Add(CURRENT_TIMESTAMP,INTERVAL -5 HOUR) AND class_on >CURDATE() AND class_on<NOW()) );
														");

		// SELECT phone,user_id, name, center, week_day, class_time, message_sent FROM UserMessage WHERE user_id IN (SELECT batch_head_id FROM Batch WHERE id IN (SELECT batch_id from Class WHERE updated_by_mentor=0 AND class_on < Date_Add(CURRENT_TIMESTAMP,INTERVAL 5 HOUR) AND class_on >CURDATE() ) )
		foreach($mentor_message_record->result() as $mentor_message_record_row) {
            $name= ucfirst(@reset(explode(' ', $mentor_message_record_row->name)));
            $center= $mentor_message_record_row->center;
            $slot= $mentor_message_record_row->week_day;
            $time= $mentor_message_record_row->class_time;

			//Message for MENTORS can be edited from here
			// The 'your' in the message was spelled 'younr' - I fixed it. Not sure if the template will work now.
			$mentor_message = "Hi $name,\n\nThe teacher attendance for your class in $center on $slot at $time has not been marked yet.\n\nYou can update it here:\nbit.ly/link/makeadiff-madapp";

			//Updating the message sent status to 1 so that in a week the teacher will only receive 1 message.
		}
	}
}
