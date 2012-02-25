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
	
	function get_volunteer_admin_credits() {
		$data = array();
		$intern_user_group_id = 14; // 14 is the intern group
		$interns = $this->db->query("SELECT SUM(Task.credit) AS credit, User.name, User.id
    		FROM User INNER JOIN AdminCredit ON AdminCredit.user_id=User.id 
					INNER JOIN Task ON AdminCredit.task_id=Task.id
					INNER JOIN UserGroup ON UserGroup.user_id=User.id
			WHERE UserGroup.group_id=14
			AND User.city_id={$this->city_id} GROUP BY AdminCredit.user_id")->result();
		
		// Our Year starts on April - so get the list of months.
		$this_month = date('m');
		$months = array();
		$start_month = 4; // April
		$start_year = date('Y');
		if($this_month <= 3) $start_year = date('Y')-1;
		for($i = 0; $i < 12; $i++) {
			$months[] = date('Y-m', mktime(0,0,0, $start_month + $i, 1, $start_year));
		}
		$month_names = array('april','may','june','july','august','september','october','november','december','january','february','march');

		$index = 0;
		foreach($interns as $i) {
			$data[$index]->name = $i->name;
			$data[$index]->credit = $i->credit;
			
			for($month_index = 0; $month_index < 12; $month_index++) {
				$month_credit = $this->db->query("SELECT SUM(Task.credit) AS credit FROM AdminCredit
							INNER JOIN Task ON AdminCredit.task_id=Task.id
					WHERE AdminCredit.user_id={$i->id}
					AND DATE_FORMAT(added_on, '%Y-%m')='{$months[$month_index]}'")->row();
				$data[$index]->{$month_names[$month_index]} = $month_credit->credit;
			}
			
			$index++;
		}
		return $data;
	}

}
