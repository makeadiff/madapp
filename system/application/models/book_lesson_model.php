<?php
class Book_Lesson_model extends Model {
    function Book_Lesson_model() {
        // Call the Model constructor
        parent::Model();
        
        $this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
    }
    
    function get_all_books() {
    	return $this->db->get("Book")->result();
    }
    function get_all_lessons() {
    	return $this->db->get("Lesson")->result();
    }

	function get_lessons_in_book($book_id) {
		return $this->db->where('book_id', $book_id)->get("Lesson")->result();
	}
}
