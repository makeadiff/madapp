<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @license		http://orisysindia.com/licence/brilliant.html
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */

class Project_model extends Model
{
	
    function Project_model()
    {
        parent::Model();
    }
    /**
    * Function to getkids_details
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [ Array()]
    **/
	function getproject()
	{
		$this->db->select('*');
		$this->db->from('project');
		$result=$this->db->get();
		return $result;
	
	}
}	