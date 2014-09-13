<?php

/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 * @package         MaddApp
 * @author          Rabeesh
 * @since           Version 1.0
 * @filesource
 */
class National_model extends Model {

    function National_model() {
        // Call the Model constructor
        parent::Model();

        $this->ci = &get_instance();
        $this->city_id = $this->ci->session->userdata('city_id');
        $this->project_id = $this->ci->session->userdata('project_id');
        $this->year = $this->ci->session->userdata('year');
    }

    /*
     * Function Name : get_city_details()
     * Wroking :This function used for return city details
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */

    public function get_city_details() {
        return $this->db->query("SELECT id,name FROM City ORDER BY name")->result();
    }

    /*
     * Function Name : get_center_count()
     * Wroking :This function used for return total city count
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_center_count($city_id) {
        return $this->db->query("SELECT COUNT(id) AS count FROM Center WHERE city_id=$city_id AND status='1'")->row()->count;
    }

    /*
     * Function Name : get_classes_p()
     * Wroking :This function used for return total class count of level P
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_classes_p($city_id) {
        return $this->db->query("SELECT COUNT(Class.id) AS count
			FROM Class INNER JOIN Level ON Level.id = Class.level_id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%p%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_classes_s()
     * Wroking :This function used for return total class count of level S
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_classes_s($city_id) {
        return $this->db->query("SELECT COUNT(Class.id) AS count
			FROM Class INNER JOIN Level ON Level.id = Class.level_id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%s%' AND Center.city_id=$city_id  AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_classes_L1()
     * Wroking :This function used for return total class count of level L1
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_classes_L1($city_id) {
        return $this->db->query("SELECT COUNT(Class.id) AS count
			FROM Class INNER JOIN Level ON Level.id = Class.level_id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L1%' AND Center.city_id=$city_id  AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_classes_L2()
     * Wroking :This function used for return total class count of level L2
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_classes_L2($city_id) {
        return $this->db->query("SELECT COUNT(Class.id) AS count
			FROM Class INNER JOIN Level ON Level.id = Class.level_id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L2%' AND Center.city_id=$city_id  AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_classes_L3()
     * Wroking :This function used for return total class count of level L3
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_classes_L3($city_id) {
        return $this->db->query("SELECT COUNT(Class.id) AS count
			FROM Class INNER JOIN Level ON Level.id = Class.level_id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L3%' AND Center.city_id=$city_id  AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_children_P()
     * Wroking :This function used for return total children count of level P
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_children_P($city_id) {
        return $this->db->query("SELECT COUNT(StudentLevel.id) AS count
		 	FROM StudentLevel INNER JOIN Level ON StudentLevel.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%P%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_children_S()
     * Wroking :This function used for return total children count of level S
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_children_S($city_id) {
        return $this->db->query("SELECT COUNT(StudentLevel.id) AS count
		 	FROM StudentLevel INNER JOIN Level ON StudentLevel.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%S%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_children_L1()
     * Wroking :This function used for return total children count of level L1
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_children_L1($city_id) {
        return $this->db->query("SELECT COUNT(StudentLevel.id) AS count
		 	FROM StudentLevel INNER JOIN Level ON StudentLevel.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L1%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

     /*
     * Function Name : get_children_L2()
     * Wroking :This function used for return total children count of level L2
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_children_L2($city_id) {
        return $this->db->query("SELECT COUNT(StudentLevel.id) AS count
		 	FROM StudentLevel INNER JOIN Level ON StudentLevel.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L2%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_children_L3()
     * Wroking :This function used for return total children count of level L3
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_children_L3($city_id) {
        return $this->db->query("SELECT COUNT(StudentLevel.id) AS count
		 	FROM StudentLevel INNER JOIN Level ON StudentLevel.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L3%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_Volunteers_P()
     * Wroking :This function used for return total volunteers count of level P
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_Volunteers_P($city_id) {
        return $this->db->query("SELECT COUNT(UserBatch.id) AS count
		 	FROM UserBatch INNER JOIN Level ON UserBatch.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%P%' AND Center.city_id=$city_id  AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_Volunteers_S()
     * Wroking :This function used for return total volunteers count of level S
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_Volunteers_S($city_id) {
        return $this->db->query("SELECT COUNT(UserBatch.id) AS count
		 	FROM UserBatch INNER JOIN Level ON UserBatch.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%S%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : get_Volunteers_L1()
     * Wroking :This function used for return total volunteers count of level L1
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_Volunteers_L1($city_id) {
        return $this->db->query("SELECT COUNT(UserBatch.id) AS count
		 	FROM UserBatch INNER JOIN Level ON UserBatch.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L1%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

     /*
     * Function Name : get_Volunteers_L2()
     * Wroking :This function used for return total volunteers count of level L2
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_Volunteers_L2($city_id) {
        return $this->db->query("SELECT COUNT(UserBatch.id) AS count
		 	FROM UserBatch INNER JOIN Level ON UserBatch.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L2%' AND Center.city_id=$city_id AND Level.year='{$this->year}' ")->row()->count;
    }
/*
     * Function Name : get_Volunteers_L3()
     * Wroking :This function used for return total volunteers count of level L3
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function get_Volunteers_L3($city_id) {
        return $this->db->query("SELECT COUNT(UserBatch.id) AS count
		 	FROM UserBatch INNER JOIN Level ON UserBatch.level_id = Level.id INNER JOIN Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L3%' AND Center.city_id=$city_id  AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : class_children_count()
     * Wroking :This function used for return total childrens count of a given city.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_children_count($city_id) {
        return $this->db->query("SELECT COUNT(Student.id ) AS count FROM Student JOIN Center ON Center.id = Student.center_id WHERE Center.city_id={$city_id} AND Center.status='1'")->row()->count;
    }

    /*
     * Function Name : class_level_count()
     * Wroking :This function used for return total level count of a given city.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_level_count($city_id) {
        return $this->db->query("SELECT COUNT(Level.id) AS count FROM Level JOIN Center ON Center.id=Level.center_id WHERE Center.city_id={$city_id} AND Level.year='{$this->year}'")->row()->count;
    }

     /*
     * Function Name : class_volunteers_count()
     * Wroking :This function used for return total volunteers count of a given city.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_volunteers_count($city_id) {
        return $this->db->query("SELECT COUNT(id) AS count FROM User  WHERE city_id={$city_id} AND user_type='volunteer' AND status='1'")->row()->count;
    }

    /*
     * Function Name : class_volunteers_in_letgo()
     * Wroking :This function used for return total let go users count of a given city.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_volunteers_in_letgo($city_id) {
        return $this->db->query("SELECT COUNT(id) AS count FROM User WHERE city_id={$city_id} AND user_type='let_go'")->row()->count;
    }

    /*
     * Function Name : class_avg_attendance()
     * Wroking :This function used for return average class attendance.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_avg_attendance($city_id) {
        $class_count = $this->class_class_count($city_id);
        // Sum of all Attendance Percentage / $class_count

        return 0;
    }

    /*
     * Function Name : class_class_count()
     * Wroking :This function used for return class count.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_class_count($city_id) {
        return $this->db->query("SELECT COUNT(Class.id) AS count FROM Class  JOIN Level ON Level.id=Class.level_id  JOIN 
					Center ON Center.id=Level.center_id WHERE Center.city_id={$city_id} AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : class_substitute_count()
     * Wroking :This function used for return class substitute count.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_substitute_count($city_id) {
        return $this->db->query("SELECT COUNT(UserClass.id) AS count 
					FROM UserClass JOIN User ON User.id = UserClass.user_id 
					INNER JOIN Class ON UserClass.class_id=Class.id
					INNER JOIN Level ON Class.level_id=Level.id
					WHERE User.city_id={$city_id} AND UserClass.substitute_id!=0 AND UserClass.status='attended' AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : class_missed_count()
     * Wroking :This function used for return class missed count.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_missed_count($city_id) {
        return $this->db->query("SELECT COUNT(UserClass.id ) AS count 
					FROM UserClass JOIN User ON User.id = UserClass.user_id
					INNER JOIN Class ON UserClass.class_id=Class.id
					INNER JOIN Level ON Class.level_id=Level.id
					WHERE User.city_id = {$city_id} AND UserClass.status='absent' AND UserClass.substitute_id=0 AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : class_cancelled_count()
     * Wroking :This function used for return class cancelled count.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_cancelled_count($city_id) {

//        return $this->db->query("SELECT COUNT(DISTINCT UserClass.id) AS count FROM UserClass JOIN User ON User.id=UserClass.user_id 
//		WHERE User.city_id={$city_id} AND UserClass.status='cancelled'")->row()->count;
		$year_month = date('Y-m');
        return $this->db->query("SELECT DISTINCT(Class.id) AS count FROM Class
                                INNER JOIN UserClass ON Class.id=UserClass.class_id
                                INNER JOIN Level ON Class.level_id=Level.id
                                INNER JOIN Center ON Level.center_id=Center.id
                        WHERE DATE_FORMAT(Class.class_on, '%Y-%m')='$year_month'
                                AND Class.project_id=$this->project_id
                                AND UserClass.status='cancelled'
                                AND Center.city_id=$city_id
                        GROUP BY UserClass.class_id");
    }

    /*
     * Function Name : class_number_of_level_p()
     * Wroking :This function used for return number of  P levels.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function class_number_of_level_p($city_id) {
        return $this->db->query("SELECT COUNT(Level.id) AS count FROM Level INNER JOIN  Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%p%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : class_number_of_level_s()
     * Wroking :This function used for return number of  S levels.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function class_number_of_level_s($city_id) {
        return $this->db->query("SELECT COUNT(Level.id) AS count FROM Level INNER JOIN  Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%S%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

    /*
     * Function Name : class_number_of_level_l1()
     * Wroking :This function used for return number of  L1 levels.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function class_number_of_level_l1($city_id) {
        return $this->db->query("SELECT COUNT(Level.id) AS count FROM Level INNER JOIN  Center ON Center.id = Level.center_id
			WHERE Level.name LIKE '%L1%' AND Center.city_id=$city_id AND Level.year='{$this->year}'")->row()->count;
    }

     /*
     * Function Name : class_cct_count()
     * Wroking :This function used for return total count of  cct.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_cct_count($city_id) {
        return $this->db->query("SELECT COUNT(id) AS count FROM Event  WHERE city_id=$city_id AND type='avm'")->row()->count;
    }

    /*
     * Function Name : class_tt_count()
     * Wroking :This function used for return total count of TT.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_tt_count($city_id) {
        return $this->db->query("SELECT COUNT(id) AS count FROM Event  WHERE city_id=$city_id AND type='teacher'")->row()->count;
    }

    /*
     * Function Name : tt_user_events()
     * Wroking :This function used for return total count of user events  in  TT.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function tt_user_events($city_id) {
        return $this->db->query("SELECT COUNT(UserEvent.user_id) AS count FROM UserEvent INNER JOIN Event
				ON Event.id=UserEvent.event_id  WHERE Event.city_id=$city_id AND Event.type='teacher'")->row()->count;
    }

    /*
     * Function Name : class_tt_attendance()
     * Wroking :This function used for return total attendance count of user in  TT.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function class_tt_attendance($city_id) {
        return $this->db->query("SELECT COUNT(UserEvent.user_id) AS count FROM UserEvent INNER JOIN Event
				ON Event.id=UserEvent.event_id  WHERE Event.city_id=$city_id AND Event.type='teacher' AND UserEvent.present='1'")->row()->count;
    }
 /*
     * Function Name : no_process_training()
     * Wroking :This function used for return number of process training.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    public function no_process_training($city_id) {
        return $this->db->query("SELECT COUNT(id) AS count FROM Event  WHERE city_id=$city_id AND type='process'")->row()->count;
    }

    /*
     * Function Name : process_training_user_events()
     * Wroking :This function used for return number of process training user events.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function process_training_user_events($city_id) {
        return $this->db->query("SELECT COUNT(UserEvent.user_id) AS count FROM UserEvent INNER JOIN Event
				ON Event.id=UserEvent.event_id  WHERE Event.city_id=$city_id AND Event.type='process'")->row()->count;
    }

    /*
     * Function Name : process_training_Attendance()
     * Wroking :This function used for return number of process training attendance
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function process_training_Attendance($city_id) {
        return $this->db->query("SELECT COUNT(UserEvent.user_id) AS count FROM UserEvent INNER JOIN Event
				ON Event.id=UserEvent.event_id  WHERE Event.city_id=$city_id AND Event.type='process' AND UserEvent.present='1'")->row()->count;
    }
 /*
     * Function Name : class_volunteers_negative_credit()
     * Wroking :This function used for return number of volunteers with negative credits.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function class_volunteers_negative_credit($city_id) {
        return $this->db->query("SELECT COUNT(id) AS count FROM User  WHERE city_id=$city_id AND credit <= 0 AND user_type='volunteer'")->row()->count;
    }

    /*
     * Function Name : class_getClasses()
     * Wroking :This function used for return classes of current city.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function class_getClasses($city_id) {
        return $this->db->query("SELECT Class.id FROM Class JOIN Level ON Level.id=Class.level_id JOIN 
				Center ON Center.id=Level.center_id WHERE Center.city_id={$city_id} AND Level.year='{$this->year}'")->result();
    }

     /*
     * Function Name : class_getfull_students()
     * Wroking :This function used for return full student count.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function class_getfull_students($class_id) {
        return $this->db->query("SELECT COUNT(id) AS count FROM StudentClass WHERE class_id={$class_id}")->row()->count;
    }

   /*
     * Function Name : class_getpresent_students()
     * Wroking :This function used for return full present student count.
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function class_getpresent_students($class_id) {
        return $this->db->query("SELECT COUNT(id) AS count FROM StudentClass WHERE class_id={$class_id} AND present='1'")->row()->count;
    }

    /*
     * Function Name : last_test_p()
     * Wroking :This function used for return last test in P Level
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function last_test_p($city_id) {
        return $this->db->query("SELECT Exam_Event.exam_on FROM Exam_Event JOIN Level ON Level.id=Exam_Event.level_id
              WHERE Exam_Event.city_id={$city_id} AND (Level.name LIKE '%P%') AND Level.year='{$this->year}' ORDER BY exam_on DESC ")->result();
    }

    /*
     * Function Name : last_test_s()
     * Wroking :This function used for return last test in S Level
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function last_test_s($city_id) {
        return $this->db->query("SELECT Exam_Event.exam_on FROM Exam_Event JOIN Level ON Level.id=Exam_Event.level_id
              WHERE Exam_Event.city_id={$city_id} AND (Level.name LIKE '%S%') AND Level.year='{$this->year}' ORDER BY exam_on DESC ")->result();
    }

    /*
     * Function Name : last_test_l1()
     * Wroking :This function used for return last test in L1 Level
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function last_test_l1($city_id) {
        return $this->db->query("SELECT Exam_Event.exam_on FROM Exam_Event JOIN Level ON Level.id=Exam_Event.level_id
              WHERE Exam_Event.city_id={$city_id} AND (Level.name LIKE '%L1%') AND Level.year='{$this->year}' ORDER BY exam_on DESC ")->result();
    }

    /*
     * Function Name : last_test_l2()
     * Wroking :This function used for return last test in L2 Level
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function last_test_l2($city_id) {
        return $this->db->query("SELECT Exam_Event.exam_on FROM Exam_Event JOIN Level ON Level.id=Exam_Event.level_id
              WHERE Exam_Event.city_id={$city_id} AND (Level.name LIKE '%L2%') AND Level.year='{$this->year}' ORDER BY exam_on DESC ")->result();
    }

    /*
     * Function Name : last_test_l3()
     * Wroking :This function used for return last test in L3 Level
     * @author:Rabeesh
     * @param :[$city_id]
     * @return: type: [array]
     */
    function last_test_l3($city_id) {
        return $this->db->query("SELECT Exam_Event.exam_on FROM Exam_Event JOIN Level ON Level.id=Exam_Event.level_id
              WHERE Exam_Event.city_id={$city_id} AND (Level.name LIKE '%L3%') AND Level.year='{$this->year}' ORDER BY exam_on DESC ")->result();
    }

}