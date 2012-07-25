<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package         MadApp
 * @author          Rabeesh
 * @copyright       Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link            http://orisysindia.com
 * @since           Version 1.0
 * @filesource
 */
class Debug extends Controller {

    function Debug() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('navigation');
        $this->load->library('user_auth');
        $this->load->library('validation');
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('center_model');
        $this->load->model('project_model');
        $this->load->model('users_model');
        $this->load->model('city_model');
    }

    /*
     * Function Name : delete_students_in_center()
     * Wroking :Delete students in center
     * @author: Rabeesh
     * @param :[]
     * @return: type: []
     */

    function delete_students_in_center() {
        $centers = array(7, 22, 23, 24, 10, 11, 12, 36);

        $student_ids = $this->users_model->db->query("SELECT id FROM `Student` WHERE center_id IN(" . implode(',', $centers) . ")")->result();
        print "Total Students: " . count($student_ids) . '<br />';
        //return;

        foreach ($student_ids as $row) {
            $id = $row->id;
            print "$id,";
            $this->db->query("DELETE FROM Student WHERE id=$id");
            $this->db->query("DELETE FROM StudentLevel WHERE student_id=$id");
            $this->db->query("DELETE FROM StudentClass WHERE student_id=$id");
        }
    }

}
