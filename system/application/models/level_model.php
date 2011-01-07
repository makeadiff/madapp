<?php
class Level_model extends Model {
    function Level_model() {
        // Call the Model constructor
        parent::Model();
    }
    
	/**
    * Function to getlevel
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean,]
    **/
	function getlevel()
	{
	$this->db->select('*');
	$this->db->from('level');
	$result=$this->db->get();
	return $result;
	}
	
}
