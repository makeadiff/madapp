<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer

 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */
class Book_model extends Model {

    function Book_model() {
        parent::Model();
        $this->ci = &get_instance();
        $this->city_id = $this->ci->session->userdata('city_id');
        $this->project_id = $this->ci->session->userdata('project_id');
    }

    /*
     * Function Name : getbook_count()
     * Wroking :This function returns total book count
     * @author:Rabeesh
     * @param :[]
     * @return: type: [count]
     */

    function getbook_count() {
        $this->db->select('*');
        $this->db->from('Book');
        $count = $this->db->get();
        return count($count->result());
    }

    /*
     * Function Name : getbook_details()
     * Wroking :This function returns all the book details
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */

    function getbook_details() {
        $this->db->select('*');
        $this->db->from('Book');
        $result = $this->db->get();
        return $result;
    }

    /*
     * Function Name : add_book()
     * Wroking :This function save  book details
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */

    function add_book($data) {
        $array_details = array('name' => $data['bookname']);
        $this->db->insert('Book', $array_details);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : getbook_name()
     * Wroking :This function returns  book details of given bookid
     * @author:Rabeesh
     * @param :[$uid]
     * @return: type: [array]
     */

    function getbook_name($uid) {
        $this->db->select('*');
        $this->db->from('Book');
        $this->db->where('id', $uid);
        $result = $this->db->get();
        return $result;
    }

    /*
     * Function Name : update_bookname()
     * Wroking :This function update book details
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */

    function update_bookname($data) {
        $root_id = $data['root_id'];
        $array_details = array('name' => $data['bookname']);
        $this->db->where('id', $root_id);
        $this->db->update('Book', $array_details);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : delete_bookname()
     * Wroking :This function delete book details
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */

    function delete_bookname($data) {
        $id = $data['book_id'];
        $this->db->where('id', $id);
        $this->db->delete('Book');
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : getchpater_count()
     * Wroking :This function return total chapter count.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [count]
     */

    function getchpater_count() {
        $this->db->select('*');
        $this->db->from('Lesson');
        $count = $this->db->get();
        return count($count->result());
    }

    /*
     * Function Name : getlesson_details()
     * Wroking :This function return lesson details
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */

    function getlesson_details() {
        $this->db->select('Book.name as book_name,Lesson.*');
        $this->db->from('Lesson');
        $this->db->join('Book', 'Book.id=Lesson.book_id', 'join');
        return $this->db->get();
    }
        /*
         * Function Name : add_lesson()
         * Wroking :This function save lesson details
         * @author:Rabeesh
         * @param :[]
         * @return: type: [boolean]
         */

        function add_lesson($data) {
            $array_details = array('book_id' => $data['book'],
                'name' => $data['lessonname'],
            );
            $this->db->insert('Lesson', $array_details);
            return ($this->db->affected_rows() > 0) ? true : false;
        }

        /*
         * Function Name : getlesson_name()
         * Wroking :This function returns the lesson name
         * @author:Rabeesh
         * @param :[$uid]
         * @return: type: [array]
         */

        function getlesson_name($uid) {
            $this->db->select('Book.name as book_name,Book.id as book_id,Lesson.*');
            $this->db->from('Lesson');
            $this->db->join('Book', 'Book.id=Lesson.book_id', 'join');
            $this->db->where('Lesson.id', $uid);
            return $this->db->get();
        }

        /*
         * Function Name : update_lesson()
         * Wroking :This function update lesson details.
         * @author:Rabeesh
         * @param :[$data]
         * @return: type: [boolean]
         */
        function update_lesson($data) {
            $root_id = $data['rootId'];
            $array_details = array('name' => $data['lessonname'],
                'book_id' => $data['book_id']);
            $this->db->where('id', $root_id);
            $this->db->update('Lesson', $array_details);
            return ($this->db->affected_rows() > 0) ? true : false;
        }

        /*
         * Function Name : delete_lesson()
         * Wroking :This function delete lesson details.
         * @author:Rabeesh
         * @param :[$data]
         * @return: type: [boolean]
         */
        function delete_lesson($data) {
            $id = $data['lesson_id'];
            $this->db->where('id', $id);
            $this->db->delete('Lesson');
            return ($this->db->affected_rows() > 0) ? true : false;
        }

    
}