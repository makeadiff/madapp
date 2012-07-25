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
    /*
     * Function Name : get_batch()
     * Wroking :This function returns  batch name 
     * @author:
     * @param :[$batch_id]
     * @return: type: []
     */
    function get_batch($batch_id) {
    	$data = $this->db->where('id', $batch_id)->get("Batch")->row();
    	$data->name = $this->create_batch_name($data->day, $data->class_time);
    	return $data;
    }
    /*
     * Function Name : get_batch_as_array()
     * Wroking :This function returns  all details of batch 
     * @author:
     * @param :[$batch_id]
     * @return: type: [array]
     */
    function get_batch_as_array($batch_id) {
    	return $this->db->where('id', $batch_id)->get("Batch")->row_array();
    }
     /*
     * Function Name : get_batch_teachers()
     * Wroking :This function returns  teachers details of given  batch 
     * @author:
     * @param :[$batch_id]
     * @return: type: [array]
     */
    function get_batch_teachers($batch_id) {
    	return $this->db->query("SELECT User.id, User.name, UserBatch.level_id FROM User 
    				INNER JOIN UserBatch ON User.id=UserBatch.user_id 
    				WHERE UserBatch.batch_id={$batch_id} AND User.project_id={$this->project_id}")->result();
    }
    /*
     * Function Name : get_teachers_in_batch_and_level()
     * Wroking :This function returns  teachers details of given  batch and level
     * @author:
     * @param :[$batch_id, $level_id]
     * @return: type: [array]
     */
    function get_teachers_in_batch_and_level($batch_id, $level_id) {
    	return colFormat($this->db->query("SELECT user_id FROM UserBatch WHERE batch_id={$batch_id} AND level_id={$level_id}")->result());
    }
    /*
     * Function Name : get_teachers_in_batch()
     * Wroking :This function returns  teachers details of given  batch
     * @author:
     * @param :[$batch_id, $level_id]
     * @return: type: [array]
     */
    function get_teachers_in_batch($batch_id) {
    	return $this->db->query("SELECT user_id AS id FROM UserBatch WHERE batch_id={$batch_id}")->result();
    }
    /*
     * Function Name : get_all_batches()
     * Wroking :This function returns  all batch details of given project
     * @author:
     * @param :[]
     * @return: type: [array]
     */
    function get_all_batches() {
    	return $this->db->where('project_id', $this->project_id)->where('year', $this->year)->get('Batch')->result();
    }
    /*
     * Function Name : get_batches_in_level()
     * Wroking :This function returns  all batch details of given level and year
     * @author:
     * @param :[$level_id]
     * @return: type: [array]
     */
    function get_batches_in_level($level_id) {
    	return $this->db->query("SELECT Batch.* FROM Batch INNER JOIN UserBatch ON Batch.id=UserBatch.batch_id 
			WHERE UserBatch.level_id=$level_id AND Batch.project_id={$this->project_id} AND Batch.year={$this->year}")->result();
    }
    /*
     * Function Name : get_volunteer_requirement_in_batch()
     * Wroking :This function returns  volunteer requirments of given batch
     * @author:
     * @param :[$batch_id]
     * @return: type: [array]
     */
    function get_volunteer_requirement_in_batch($batch_id) {
    	return $this->db->query("SELECT level_id AS id, requirement AS name FROM UserBatch WHERE batch_id=$batch_id AND user_id=0")->result();
    }
     /*
     * Function Name : get_levels_in_batch()
     * Wroking :This function returns  level details of given batch and project
     * @author:
     * @param :[$batch_id]
     * @return: type: [array]
     */
    function get_levels_in_batch($batch_id) {

    	return $this->db->query("SELECT Level.id,Level.name FROM Level INNER JOIN UserBatch ON Level.id=UserBatch.level_id 
			WHERE UserBatch.batch_id=$batch_id AND Level.project_id={$this->project_id}")->result();

    }
    /*
     * Function Name : get_batches_in_center()
     * Wroking :This function returns  batches of given center
     * @author:
     * @param :[$center_id]
     * @return: type: [array]
     */
    function get_batches_in_center($center_id) {
    	return $this->db->where('center_id',$center_id)->where('project_id', $this->project_id)->where('year', $this->year)->orderby('day')->get('Batch')->result();
    }
    /*
     * Function Name : create_batch_name()
     * Wroking :This function create batch name and returns  batches name
     * @author:
     * @param :[$day, $time]
     * @return: type: [array]
     */
    function create_batch_name($day, $time) {
    	$day_list = array('Sunday','Monday','Tuesday','Wednesday','Thursday','Friday','Saturday');
		return $day_list[$day] . ' ' . date('h:i A', strtotime('2011-01-01 '.$time));
    }
	 /*
     * Function Name : get_center_of_batch()
     * Wroking :This function returns  center id of given batch
     * @author:
     * @param :[$batch_id]
     * @return: type: [array]
     */
	function get_center_of_batch($batch_id) {
		return $this->db->where('id',$batch_id)->get('Batch')->row()->center_id;
	}
     /*
     * Function Name : get_batch_head()
     * Wroking :Get the details about the batch head of a given batch.
     * @author:
     * @param :[$batch_id]
     * @return: type: [array]
     */

    function get_batch_head($batch_id) {
		return $this->db->query("SELECT User.id, User.name, User.phone FROM User INNER JOIN Batch ON User.id=Batch.batch_head_id WHERE Batch.id=$batch_id")->row();
    }
  	 /*
     * Function Name : get_class_days()
     * Wroking :This function returns  class days of given center
     * @author:
     * @param :[$center_id]
     * @return: type: [array]
     */
  	function get_class_days($center_id) {
		$class_days = $this->db->query("SELECT id,day,class_time FROM Batch WHERE center_id=$center_id AND project_id={$this->project_id} AND year={$this->year} ORDER BY day")->result();
		$return = array();
		foreach($class_days as $batch) {
			$return[$batch->id] = $this->create_batch_name($batch->day, $batch->class_time);
		}
		return $return;
	}
	 /*
     * Function Name : set_volunteer_requirement()
     * Wroking :This function save volunteer requirments
     * @author:
     * @param :[$batch_id, $level_id, $requirement]
     * @return: type: [array]
     */
	function set_volunteer_requirement($batch_id, $level_id, $requirement) {
		if(!$requirement) return;
		
		$this->db->insert("UserBatch", array(
			'batch_id'	=> $batch_id,
			'level_id'	=> $level_id,
			'requirement'=>$requirement,
			'user_id'	=> 0
		));
	}
	 /*
     * Function Name : create()
     * Wroking :This function save batch details and save batch head
     * @author:
     * @param :[$data]
     * @return: type: [array]
     */
    function create($data) {
    	$data['project_id'] = $this->project_id;
    	$data['year'] = $this->year;
    	$this->db->insert("Batch", $data);
    	
    	if($data['batch_head_id'] > 0) {
			$this->load->model('users_model');
			$this->users_model->adduser_to_group($data['batch_head_id'], array(8));// Add the batch head to Batch Head group.
		}
    }
     /*
     * Function Name : edit()
     * Wroking :This function update batch details.
     * @author:
     * @param :[$data]
     * @return: type: [array]
     */
    function edit($batch_id, $data) {
		$old_batch_head = $this->get_batch_head($batch_id);
		$this->db->where('id', $batch_id)->update('Batch',$data);
		if($data['batch_head_id'] > 0) {
			$this->load->model('users_model');
			$this->users_model->remove_user_from_group($old_batch_head->id,8);// Remove old batch head from Batch Head Group.
			$this->users_model->adduser_to_group($data['batch_head_id'], array(8));// Add the batch head to Batch Head group.
		}
    }
     /*
     * Function Name : delete()
     * Wroking :This function delete batch details.
     * @author:
     * @param :[$data]
     * @return: type: [array]
     */
    function delete($batch_id) {
    	$this->db->delete('Batch', array('id'=>$batch_id));
    	$this->db->delete('UserBatch', array('batch_id'=>$batch_id));
    }
}
