<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 *
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package         MadApp
 * @author          Rabeesh
 * @copyright       Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @license         http://orisysindia.com/licence/brilliant.html
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
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('exam_model');
    }
	
    /**
    *
    * Function to 
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/

    function index()
    {
        
    }

    /**
    *
    * Function to exam_score
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
    function exam_score()
    {	
		
		$data['currentPage'] = 'db';
		$data['navId'] = '3';
		$data['message']='';
		$this->load->view('admin/includes/header',$data);
		$this->load->view('admin/includes/superadminNavigation',$data);
		$this->load->view('admin/student_exam_score_view',$data);
		$this->load->view('admin/includes/footer');

    }
	/**
    *
    * Function to ajax_sbjectbox
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function ajax_sbjectbox()
	{
		$data['sub_no'] = $_REQUEST['sub_no'];
		$this->load->view('admin/subjectbox_div',$data);
	}
	/**
    *
    * Function to get_center
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get_center()
	{
		$data['center']= $this->center_model->getcenter();
		$this->load->view('admin/getcenter_div',$data);
	
	}
	/**
    *
    * Function to get_kidslist
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function get_kidslist()
	{
		$c_id = $_REQUEST['center_id'];
		$data['kids']=$this->kids_model->getkids_name_incenter($c_id);
		$this->load->view('admin/kids_list_div',$data);
	
	}
	/**
    *
    * Function to input_exam_mark_details
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function input_exam_mark_details()
	{
		$agents = $_REQUEST['agents'];
		$name = $_REQUEST['name'];
		$choice_text = $_REQUEST['choice_text'];
		$center = $_REQUEST['center'];
		$exam_id=$this->exam_model->insert_exam_name($name);
		$choiceText = substr($choice_text,0,strlen($choice_text)-1);
		$subjects_id=$this->exam_model->insert_subject_name($choiceText,$exam_id);
		$flag=$this->exam_model->insert_exam_mark($choiceText,$exam_id,$agents);
	}
	
}