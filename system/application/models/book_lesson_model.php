<?php

class Book_Lesson_model extends Model {

    function Book_Lesson_model() {
        // Call the Model constructor
        parent::Model();

        $this->ci = &get_instance();
        $this->city_id = $this->ci->session->userdata('city_id');
        $this->project_id = $this->ci->session->userdata('project_id');
    }

    /*
     * Function Name : get_all_books()
     * Wroking :This function returns  all book details 
     * @author:
     * @param :[]
     * @return: type: []
     */

    function get_all_books() {
        return $this->db->get("Book")->result();
    }

    /*
     * Function Name : get_all_lessons()
     * Wroking :This function returns  all lesson details 
     * @author:
     * @param :[]
     * @return: type: []
     */

    function get_all_lessons() {
        return $this->db->get("Lesson")->result();
    }

    /*
     * Function Name : get_lessons_in_book()
     * Wroking :This function returns  all lesson details of given book
     * @author:
     * @param :[$book_id]
     * @return: type: []
     */

    function get_lessons_in_book($book_id) {
        return $this->db->where('book_id', $book_id)->get("Lesson")->result();
    }

}
