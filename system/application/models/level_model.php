<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */

class Level_model extends Model {
	function Level_model() {
        parent::Model();
    }
    
	function get_all_levels_in_center($center_id) {
		return $this->db->where('project_id', 1)->where('center_id',$center_id)->get('Level')->result();
	}
	
	function get_level($level_id) {
		return $this->db->where('id', $level_id)->get('Level')->row();
	}
	
	function get_level_details($level_id) {
    	return $this->db->query("SELECT Center.name AS center_name, Level.name 
    		FROM Level INNER JOIN Center ON Center.id=Level.center_id 
    		WHERE Level.id=$level_id")->row();
    }
	
}
