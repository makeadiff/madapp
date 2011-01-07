<?php
class Batch_model extends Model {
    function Batch_model() {
        // Call the Model constructor
        parent::Model();
    }
    
    function get_batch_teachers($batch_id) {
    	return $this->db->query("SELECT User.id, UserBatch.level_id FROM User 
    				INNER JOIN UserBatch ON User.id=UserBatch.user_id 
    				WHERE UserBatch.batch_id={$batch_id}")->result();
    }
    
    function get_all_batches() {
    	return $this->db->get('Batch')->result();
    }
}
