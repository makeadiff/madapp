<?php
class Report_model extends Model {
    function Report_model() {
        // Call the Model constructor
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
    }
    
     /*
     * Function Name : get_users_with_low_credits()
     * Wroking :This function used for return users with low credit
     * @author:Rabeesh
     * @param :[$credit=0, $sign='<', $city_id=-1, $project_id=-1]
     * @return: type: [array]
     */
	function get_users_with_low_credits($credit=0, $sign='<', $city_id=-1, $project_id=-1) {
		if($sign != '<' and $sign != '>') $sign = '<';
		if($city_id == -1) $city_id = $this->city_id;
		if($project_id == -1) $project_id = $this->project_id;
		
		$this->db->select("id AS user_id,name,credit");
		if($city_id) $this->db->where('city_id', $city_id);
		$this->db->where('project_id',$project_id);
		$this->db->where("credit $sign $credit");
		$this->db->where('user_type','volunteer');
		$this->db->order_by('credit');
		return $this->db->get('User')->result();
    }
    /*
     * Function Name : get_users_absent_without_substitute()
     * Wroking :This function used for return absent users without substitute.
     * @author:
     * @param :[]
     * @return: type: [array]
     */
    function get_users_absent_without_substitute() {
    	return $this->db->query("SELECT UserClass.user_id,User.name,Class.class_on,Center.name AS center_name
    		FROM UserClass INNER JOIN User ON User.id=UserClass.user_id 
    			INNER JOIN Class ON UserClass.class_id=Class.id INNER JOIN Batch ON Batch.id=Class.batch_id
    			INNER JOIN Center ON Batch.center_id=Center.id
    		WHERE User.city_id={$this->city_id} AND User.project_id={$this->project_id} 
    			AND UserClass.substitute_id=0 AND UserClass.status='absent'")->result();
    }
    /*
     * Function Name : get_volunteer_requirements()
     * Wroking :This function  return all volunteer requirments.
     * @author:
     * @param :[]
     * @return: type: [array]
     */
    function get_volunteer_requirements($city_id=0) {
		if(!$city_id) $city_id = $this->city_id;
    	return $this->db->query("SELECT Center.name, SUM(requirement) AS requirement
    		FROM UserBatch 
    		INNER JOIN `Level` ON UserBatch.level_id = Level.id 
    		INNER JOIN Center ON Center.id=Level.center_id 
    		WHERE requirement > 0 AND Center.city_id=$city_id GROUP BY Level.center_id")->result();
    }
	/*
     * Function Name : get_volunteer_admin_credits()
     * Wroking :This function  return all volunteer admin credits.
     * @author:
     * @param :[]
     * @return: type: [array]
     */
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
