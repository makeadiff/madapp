<?php
class Batch_model extends Model {
    function Batch_model() {
        // Call the Model constructor
        parent::Model();
    }
    
    function get_batch($batch_id) {
    	return $this->db->where('id', $batch_id)->get("Batch")->row();
    }
    function get_batch_as_array($batch_id) {
    	return $this->db->where('id', $batch_id)->get("Batch")->row_array();
    }
    
    function get_batch_teachers($batch_id) {
    	return $this->db->query("SELECT User.id, User.name, UserBatch.level_id FROM User 
    				INNER JOIN UserBatch ON User.id=UserBatch.user_id 
    				WHERE UserBatch.batch_id={$batch_id} AND User.project_id=1")->result();
    }
    
    function get_teachers_in_batch_and_level($batch_id, $level_id) {
    	return $this->db->query("SELECT user_id AS id FROM UserBatch WHERE batch_id={$batch_id} AND level_id={$level_id}")->result();
    }
    
    function get_all_batches() {
    	return $this->db->where('project_id', 1)->get('Batch')->result();
    }
    
    function get_batches_in_level($level_id) {
    	return $this->db->query("SELECT Batch.* FROM Batch INNER JOIN BatchLevel ON Batch.id=BatchLevel.batch_id WHERE BatchLevel.level_id=$level_id AND Batch.project_id=1")->result();
    }
    
    function get_levels_in_batch($batch_id) {
    	return $this->db->query("SELECT Level.id,Level.name FROM Level INNER JOIN BatchLevel ON Level.id=BatchLevel.level_id WHERE BatchLevel.batch_id=$batch_id AND Level.project_id=1")->result();
    }
    
    function get_batches_in_center($center_id) {
    	return $this->db->where('center_id',$center_id)->where('project_id', 1)->get('Batch')->result();
    }
    
    function create($data) {
    	$data['project_id'] = 1;
    	$this->db->insert("Batch", $data);
    }
    
    function delete($batch_id) {
    	$this->db->delete('Batch', array('id'=>$batch_id));
    	$this->db->delete('BatchLevel', array('batch_id'=>$batch_id));
    	$this->db->delete('UserBatch', array('batch_id'=>$batch_id));
    }
}
