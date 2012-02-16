<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package         MadApp
 * @author          Rabeesh
 * @copyright       Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link            http://orisysindia.com
 * @since           Version 1.0
 * @filesource
 */
class Exam extends Controller  {
    /**
    * constructor 
    **/
    function Exam()
    {
        parent::Controller();
		$this->load->library('session');
        $this->load->library('user_auth');
		$this->load->helper('url');
        $this->load->helper('form');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL )
		{
			redirect('auth/login');
		}
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('exam_model');
    }
	
    function index() {
        redirect('exam/manage_exam');
    }
	/**
    * Function to manage_exam
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function manage_exam()
	{
		$data['title'] = 'Manage Exam';
		$this->user_auth->check_permission('exam_index');
		$this->load->view('layout/header',$data);
		$data['details'] = $this->exam_model->get_all();
		$this->load->view('student_exam_score/exam_list',$data);
		$this->load->view('layout/footer');
	}
	/**
    * Function to view_exam_details
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function view_exam_details()
	{
		$this->user_auth->check_permission('exam_view');
		$exam_id = $this->uri->segment(3);
		$data['exam_name']= $this->exam_model->get_exam_name_by_id($exam_id);
		$data['sub_name']= $this->exam_model->get_subject_names($exam_id);
		$this->load->view('student_exam_score/exam_details_view',$data);
	}
	

	
    function add_exam() {	
		$this->user_auth->check_permission('exam_add');
		$this->load->view('student_exam_score/add_exam');
    }
    
    function insert() {
		$subject_names = $this->input->post('subject');
		$totals = $this->input->post('subject_total');
		
		// Get the names of the subject and the total mark into one array.
		$subjects = array();
		for($i=0; $i<count($subject_names); $i++) {
			if($subject_names[$i]) $subjects[] = array('name'=>$subject_names[$i], 'total_mark'=>$totals[$i]);
		}
		$this->exam_model->insert($this->input->post('name'), $this->input->post('level'), $subjects);
		
		$this->session->set_flashdata("success", "Exam Added Successfully.");
		redirect('exam/manage_exam');
    }
    
    function add_event($exam_id) {
		$this->user_auth->check_permission('exam_add_event');
		
		$data = array(
			'centers'	=> idNameFormat($this->center_model->get_all()),
			'exam_name'	=> $this->exam_model->get_exam_name_by_id($exam_id)->name,
			'exam_id'	=> $exam_id,
		);
			
		$this->load->view('student_exam_score/add_event', $data);
    }
    
    function add_marks() {
		$this->user_auth->check_permission('exam_add_marks');
		$exam_id = $this->input->post('exam_id');
		
		$data = array(
			'exam_on'		=> $this->input->post('exam_on'),
			'exam_id'		=> $exam_id,
			'center_id'		=> $this->input->post('center_id'),
			'level_id'		=> $this->input->post('level_id'),
			'student_ids'	=> $this->input->post('student_id'),
			'student_names'	=> $this->input->post('student_names'),
			'title'			=> 'Add Marks',
			'subjects'		=> $this->exam_model->get_subject_names($exam_id)->result(),
		);
		
		$this->load->view('student_exam_score/add_marks', $data);
    }
    
    function save_marks() {
		$this->user_auth->check_permission('exam_save_marks');
		
		$exam_id = $this->input->post('exam_id');
		$mark = $this->input->post('mark');
		
		$exam_event_id = $this->exam_model->insert_exam($exam_id, $this->input->post('center_id'), $this->input->post('exam_on'));
		foreach($mark as $student_id => $subject) {
			foreach($subject as $subject_id => $mark_for_subject) {
				$this->exam_model->insert_mark($exam_id, $exam_event_id, $student_id, $subject_id, $mark_for_subject);
			}
		}
		
		$this->session->set_flashdata("success", "Exam Marks Added Successfully.");
		redirect('exam/manage_exam');
    }
    
    
    function view_exam_events($exam_id=0) {
		$this->user_auth->check_permission('exam_view_exam_events');
		$events = $this->exam_model->get_exam_events($exam_id);
		$this->load->view('student_exam_score/view_exam_events', array('events'=>$events,'title'=>'Exam Events'));
    }
    
    function view_scores($event_id) {
		$this->user_auth->check_permission('exam_view_scores');
		
		$exam_details = $this->exam_model->get_exam_event_details($event_id);
		$all_marks = $this->exam_model->get_marks($event_id);
		
		// Get the marks into a two dimentional array. Quite clever, actually.
		$marks = array();
		foreach($all_marks as $m) {
			if(!isset($marks[$m->student_id])) $marks[$m->student_id] = array($m->subject_id => $m->mark);
			else $marks[$m->student_id][$m->subject_id] = $m->mark;
		}
		
		$students = idNameFormat($this->exam_model->get_student_attending_exam($event_id));
		
		$this->load->view('student_exam_score/view_scores', array(
			'exam_details'	=> $exam_details,
			'marks'			=> $marks,
			'students'		=> $students,
			'title'			=> 'Exam Events'));
    }
	
	function delete_event($event_id) {
		$this->user_auth->check_permission('exam_delete_event');
		$this->exam_model->delete_event($event_id);

		$this->session->set_flashdata("success", "Exam event deleted successfully.");
		redirect("exam/manage_exam");
	}
	function delete($exam_id) {
		$this->user_auth->check_permission('exam_delete');
		
		$this->exam_model->delete($exam_id);
		$this->session->set_flashdata("success", "Exam deleted successfully.");
		redirect("exam/manage_exam");
	}
	
	
	
    // Ajax functions
    function get_levels($center_id) {
		$this->load->model('level_model');
		$data= array(
			'levels'	=>	$this->level_model->get_all_levels_in_center($center_id)
		);
		$this->load->view('student_exam_score/ajax/get_levels', $data);
    }
    
    function get_kids_in_level($level_id) {
		$this->load->model('level_model');
		$data = array(
			'kids'	=>	$this->level_model->get_kids_in_level($level_id),
		);
		$this->load->view('student_exam_score/ajax/get_kids_in_level', $data);
    }

}
