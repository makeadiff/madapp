<?php
class Report_model extends Model {
    function Report_model() {
        // Call the Model constructor
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
		$this->year = $this->ci->session->userdata('year');
    }
    
    
	function get_users_with_low_credits($credit=0, $sign='<', $city_id=-1, $project_id=-1) {
		if($sign != '<' and $sign != '>') $sign = '<';
		if($city_id == -1) $city_id = $this->city_id;
		if($project_id == -1) $project_id = $this->project_id;
        $teacher_group_id = 9;
		
		$this->db->select("User.id AS user_id,name,credit");
		$this->db->join('UserGroup', 'User.id=UserGroup.user_id');
		$this->db->where('group_id', $teacher_group_id); 
		if($city_id) $this->db->where('city_id', $city_id);
		//$this->db->where('project_id',$project_id);
		$this->db->where("credit $sign $credit");
		$this->db->where('user_type','volunteer');
        $this->db->where('status','1');
		$this->db->order_by('credit');
		$data = $this->db->get('User')->result();

        return $data;
    }
    
    function get_users_absent_without_substitute() {
    	return $this->db->query("SELECT UserClass.user_id,User.name,Class.class_on,Center.name AS center_name
    		FROM UserClass INNER JOIN User ON User.id=UserClass.user_id 
    			INNER JOIN Class ON UserClass.class_id=Class.id INNER JOIN Batch ON Batch.id=Class.batch_id
    			INNER JOIN Center ON Batch.center_id=Center.id
    		WHERE User.city_id={$this->city_id} AND User.user_type='volunteer' AND Class.class_on >='{$this->year}-04-01'
    			AND UserClass.substitute_id=0 AND UserClass.status='absent'")->result();
    }

    function unassigned_teachers($city_id=0) {
        if(!$city_id) $city_id = $this->city_id;
        $teacher_group_id = 9;
        $assigned_teachers = idNameFormat($this->db->query("SELECT U.id,U.name FROM User U 
                INNER JOIN UserGroup UG ON U.id=UG.user_id
                INNER JOIN UserBatch UB ON U.id=UB.user_id
                INNER JOIN Batch B ON UB.batch_id=B.id
                WHERE UG.group_id=$teacher_group_id AND U.city_id=$city_id AND U.status='1' AND U.user_type='volunteer' AND B.year={$this->year} ORDER BY U.id")->result());

        $all_teachers = idNameFormat($this->db->query("SELECT U.id,U.name FROM User U 
                INNER JOIN UserGroup UG ON U.id=UG.user_id
                WHERE UG.group_id=$teacher_group_id AND U.city_id=$city_id AND U.status='1' AND U.user_type='volunteer' ORDER BY U.id")->result());

        return array_diff($all_teachers, $assigned_teachers);
    }
    
    function get_volunteer_requirements($city_id=0) {
		if(!$city_id) $city_id = $this->city_id;
    	return $this->db->query("SELECT Center.name, SUM(requirement) AS requirement
    		FROM UserBatch 
    		INNER JOIN `Level` ON UserBatch.level_id = Level.id 
    		INNER JOIN Center ON Center.id=Level.center_id 
    		WHERE requirement > 0 AND Center.city_id=$city_id 
    		AND Level.year={$this->year}
    		GROUP BY Level.center_id")->result();
    }

    function get_volunteer_count() {
        $vc_data = $this->db->query('SELECT City.name as city_name, City.id as city_id, COUNT( User.id ) as volunteer_count,  `Group`.name as group_name, `Group`.id as group_id
                            FROM User
                            INNER JOIN City ON City.id = User.city_id
                            INNER JOIN UserGroup ON UserGroup.user_id = User.id
                            INNER JOIN  `Group` ON  `Group`.id = UserGroup.group_id
                            WHERE User.status =1
                            AND User.user_type =  "volunteer"
                            GROUP BY  `Group`.id, City.id
                            ORDER BY City.name, Group.name')->result();

        $cities = $this->db->query('SELECT id,name FROM City ORDER BY name')->result();
        $groups = $this->db->query('SELECT id,name FROM `Group` WHERE `type` <> "national" AND `type` <> "strat" AND group_type = "normal" ORDER BY name')->result();

        foreach($groups as $group) {
            $group->total = 0;
        }



        foreach($cities as $city) {
            $report_data[$city->id] = new stdClass();
            $report_data[$city->id]->city_name = $city->name;
            $total = 0;
            foreach($groups as $group) {

                foreach($vc_data as $vc) {
                    if($vc->city_id == $city->id && $vc->group_id == $group->id) {
                        $report_data[$city->id]->{$group->name} = (int)$vc->volunteer_count;
                        $total += (int)$vc->volunteer_count;
                        $group->total += (int)$vc->volunteer_count;
                    }
                }
            }
            $report_data[$city->id]->total = $total;
        }

        $report_data[0] = new stdClass();
        $report_data[0]->city_name = "Total";
        $total_volunteers = 0;
        foreach($groups as $group) {
            $report_data[0]->{$group->name} = $group->total;
            $total_volunteers += $group->total;
        }

        $report_data[0]->total = $total_volunteers;



        /*var_dump($report_data);
        die();*/

        $data = array('report_data' => $report_data, 'groups' => $groups);

        return $data;
    }


    function get_child_count() {

        return $this->db->query('SELECT City.name as City, Center.name as Center, SUM(CASE Student.sex WHEN "m" THEN 1 ELSE 0 END) AS Male,
                            SUM(CASE Student.sex WHEN "f" THEN 1 ELSE 0 END) AS Female,
                            SUM(CASE Student.sex WHEN "u" THEN 1 ELSE 0 END) AS NotSpecified,
                            COUNT(Student.id) as Total FROM Student
                            INNER JOIN Center
                            ON Student.center_id = Center.id
                            INNER JOIN City
                            ON City.id = Center.city_id
                            WHERE Student.status = 1 AND Center.status = 1
                            GROUP BY City.name , Center.name WITH ROLLUP')->result();

    }
	
	function get_volunteer_admin_credits() {
		$data = array();
		$intern_user_group_id = 14; // 14 is the intern group
		$interns = $this->db->query("SELECT SUM(Task.credit) AS credit, User.name, User.id
    		FROM User INNER JOIN AdminCredit ON AdminCredit.user_id=User.id 
					INNER JOIN Task ON AdminCredit.task_id=Task.id
					INNER JOIN UserGroup ON UserGroup.user_id=User.id
			WHERE UserGroup.group_id=14
			AND User.user_type='volunteer'
			AND User.city_id={$this->city_id} GROUP BY AdminCredit.user_id")->result();
		
		$months = get_month_list();
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
