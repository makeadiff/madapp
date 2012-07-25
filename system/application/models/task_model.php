<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
class Task_model extends Model {

    /**
     * constructor 
     * */
    function Task_model() {
        parent::model();
        $this->ci = &get_instance();
        $this->city_id = $this->ci->session->userdata('city_id');
    }

    /*
     * Function Name : get_task()
     * Wroking :This function used for getting all tasks
     * @author:Rabeesh
     * @param :[$name]
     * @return: type: [array]
     */

    function get_task() {

        return $event = $this->db->query("SELECT * FROM Task order by id desc")->result();
    }

    /*
     * Function Name : add_task()
     * Wroking :This function used for saving tasks
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [array]
     */

    function add_task($data) {
        $this->db->insert("Task", array(
            'name' => $data['name'],
            'credit' => $data['credit'],
            'vertical' => $data['type'],
        ));
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : delete_task()
     * Wroking :This function used for deleting tasks
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [array]
     */

    function delete_task($data) {
        $this->db->delete('Task', array('id' => $data['id']));
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : gettask()
     * Wroking :This function used for getting task.
     * @author:Rabeesh
     * @param :[$id]
     * @return: type: [array]
     */

    function gettask($id) {
        return $this->db->where('id', $id)->get('Task')->result();
    }

    /*
     * Function Name : update_task()
     * Wroking :This function used for updating task.
     * @author:Rabeesh
     * @param :[$id]
     * @return: type: [array]
     */

    function update_task($data) {
        $this->db->where('id', $data['root_id']);
        $this->db->update("Task", array(
            'name' => $data['name'],
            'credit' => $data['credit'],
            'vertical' => $data['vertical']
        ));
        return ($this->db->affected_rows() > 0) ? true : false;
    }

}

