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
	
    /**
    * Function to 
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/

    function index()
    {
        
    }
	function manage_exam()
	{
		$data['currentPage'] = 'db';
		$data['navId'] = '3';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('student_exam_score/student_exam_view');
		$this->load->view('dashboard/includes/footer');
	}
	function get_examdetails()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Exam';
		$linkCount = $this->exam_model->exam_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->exam_model->get_exam();
		$this->load->view('student_exam_score/exam_list',$data);
	
	}
	function view_exam_details()
	{
		$exam_id = $this->uri->segment(3);
		$data['exam_name']= $this->exam_model->get_exam_name_by_id($exam_id);
		$data['contents']= $this->exam_model->get_exam_details($exam_id);
		$data['sub_name']= $this->exam_model->get_subject_names($exam_id);
		$this->load->view('student_exam_score/exam_details_view',$data);
	}
    /**
    * Function to exam_score
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
    function add_exam()
    {	
		
		$this->load->view('student_exam_score/student_exam');
    }
	/**
    * Function to ajax_sbjectbox
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function ajax_sbjectbox()
	{
		$data['sub_no'] = $_REQUEST['sub_no'];
		$this->load->view('student_exam_score/subjectbox_div',$data);
	}
	/**
    * Function to get_center
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function get_center()
	{
		$data['center']= $this->center_model->getcenter();
		$this->load->view('student_exam_score/getcenter_div',$data);
	
	}
	/**
    * Function to get_kidslist
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function get_kidslist()
	{
		$c_id = $_REQUEST['center_id'];
		$data['kids']=$this->kids_model->getkids_name_incenter($c_id);
		$this->load->view('student_exam_score/kids_list_div',$data);
	}
	/**
    * Function to input_exam_mark_details
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function input_exam_mark_details()
	{	
		//$this->user_auth->check_permission('exam_addexam_details');
		$agents = $_REQUEST['agents'];
		$name = $_REQUEST['name'];
		$choice_text = $_REQUEST['choice_text'];
		$exam_id=$this->exam_model->insert_exam_name($name);
		$choiceText = substr($choice_text,0,strlen($choice_text)-1);
		$subjects_id=$this->exam_model->insert_subject_name($choiceText,$exam_id);
		$flag=$this->exam_model->insert_exam_mark($choiceText,$exam_id,$agents);
		if($flag)
		{
		$this->load->view('student_exam_score/succes_div');
		}
		else
		{
		$this->load->view('student_exam_score/error_div');
		}
	}
	/**
    * Function to exam_score
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function exam_score()
	{
		$data['currentPage'] = 'db';
		$data['navId'] = '4';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('student_exam_score/score_view');
		$this->load->view('dashboard/includes/footer');
	
	}
	/**
    * Function to get_examlist
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function get_examlist()
	{
		$data['exam_details']=$this->exam_model->get_exam();
		$this->load->view('student_exam_score/exam_div',$data);	
	}
	/**
    * Function to ajax_getexam_details
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function ajax_getexam_details()
	{
		$data['title'] = 'Score';
		$exam_id = $_REQUEST['exam_id'];
		$data['subject']=$this->exam_model->get_subject_names($exam_id);
		$this->load->view('student_exam_score/exam_score_header',$data);
		$data['details']=$this->exam_model->get_student_names($exam_id);
		$student_id=$data['details']->result_array();
			foreach($student_id as $row)
			{
			$student_id=$row['student_id'];
			$data['id']=$student_id;
			$data['student_name']=$row['name'];
			$data['details1']=$this->exam_model->get_mark_details($exam_id,$student_id);
			$this->load->view('student_exam_score/examscore_div',$data);
			}
		$this->load->view('student_exam_score/exam_score_footer');
	}
	/**
    * Function to popupAddMark
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function popupAddMark()
		{
		//$this->user_auth->check_permission('exam_addmark');
		$data['exam_details']=$this->exam_model->get_exam();
		$this->load->view('student_exam_score/popups/popup_getexam',$data);
		}
		/**
    * Function to getexam_subjects_name
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function getexam_subjects_name()
		{
		
		$exam_id = $_REQUEST['exam_id'];
		$data['subject']=$this->exam_model->get_subject_names($exam_id);
		$data['details']=$this->exam_model->get_student_names($exam_id);
		$this->load->view('student_exam_score/popups/popup_add_exam',$data);
		}
		/**
    * Function to addMarks
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function addMarks()
		{
		$data['exam_id']=$_REQUEST['exam_id'];
		$sub_count=$_REQUEST['sub_count'];
		$stnt_count=$_REQUEST['stnt_count'];
			for($i=1;$i<=$stnt_count;$i++)
			{
				$data['student']=$_REQUEST['stnt_name'.$i];
				for($j=1;$j<=$sub_count;$j++)
				{
				$data['subject']=$_REQUEST['sub_name'.$j];
				$data['marks']=$_REQUEST[$i.'mark'.$j];
				$returnFlag=$this->exam_model->store_marks($data);
				}
			}
		  		$message['msg']   =  "Mark Added succeessfully";
				$message['successFlag'] = "0";
				$message['link']  =  "addMarks";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/close_thickbox',$message);
		 
		}
}