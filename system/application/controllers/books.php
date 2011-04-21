<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
 
class Books extends Controller
{
	function Books()
	{
		parent::Controller();
		$this->load->library('session');
        $this->load->library('user_auth');
		$this->load->helper('url');
        $this->load->helper('form');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		
		$this->load->library('validation');
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('book_model');
	}
	/**
    * Function to manage_books
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function manage_books()
	{
		$data['currentPage'] = 'db';
		$data['navId'] = '1';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('books/addbook_view');
		$this->load->view('dashboard/includes/footer');
	}
	/**
    * Function to get_booklist
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_booklist()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Books';
		$linkCount = $this->book_model->getbook_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->book_model->getbook_details($page_no);
		$this->load->view('books/book_list',$data);
	}
	/**
    * Function to popupaddbooks
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupaddbooks()
	{
		$this->load->view('books/popups/addbook_popup');
	
	}
	/**
    * Function to addbook
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function addbook()
	{
		$data['bookname']=$_REQUEST['bookname'];
		$returnFlag=$this->book_model->add_book($data);
		if($returnFlag) {
			$message['msg']   =  "Book added successfully.";
			$message['successFlag'] = "1";
			$message['link']  =  "popupaddCneter";
			$message['linkText'] = "Add New Center";
			$message['icoFile'] = "ico_addScheme.png";
			$this->load->view('dashboard/errorStatus_view',$message);
		} else {
			$message['msg']   =  "Book Not Added.";
			$message['successFlag'] = "0";
			$message['link']  =  "popupaddCneter";
			$message['linkText'] = "Add new Center";
			$message['icoFile'] = "ico_addScheme.png";
			$this->load->view('dashboard/errorStatus_view',$message);
			}
	}
	/**
    * Function to popupEdit_books
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupEdit_books()
	{
		//$this->user_auth->check_permission('');
			$uid = $this->uri->segment(3);
			$data['book_name']= $this->book_model->getbook_name($uid);
			$this->load->view('books/popups/book_edit_view',$data);	
	}
	/**
    * Function to updatebook
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function updatebook()
	{
			$data['root_id']=$_REQUEST['rootId'];
			$data['bookname']=$_REQUEST['bookname'];
			$returnFlag= $this->book_model->update_bookname($data);
			if($returnFlag == true) {
				$message['msg']   =  "Book edited successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "manage_books";
				$message['linkText'] = "";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);		  
			} else {
				$message['msg']   =  "Book not edited.";
				$message['successFlag'] = "0";
				$message['link']  =  "manage_books";
				$message['linkText'] = "";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);		  
		}
	
	}
	/**
    * Function to ajax_deletebook
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function ajax_deletebook()
	{
			$data['book_id']=$_REQUEST['entry_id'];
			$returnFlag= $this->book_model->delete_bookname($data);
	}
	/**
    * Function to manage_chapters
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function manage_chapters()
	{
			$data['currentPage'] = 'db';
			$data['navId'] = '1';
			$this->load->view('dashboard/includes/header',$data);
			$this->load->view('dashboard/includes/superadminNavigation',$data);
			$this->load->view('books/addchapter_view');
			$this->load->view('dashboard/includes/footer');
	
	}
	/**
    * Function to get_chapterlist
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_chapterlist()
	{
			$page_no = $_REQUEST['pageno'];
			$data['title'] = 'Manage Books';
			$linkCount = $this->book_model->getchpater_count();
			$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
			$data['currentPage'] = $page_no;
			$data['details']= $this->book_model->getlesson_details($page_no);
			$this->load->view('books/lesson_list',$data);
	}
	/**
    * Function to popupadd_lesson
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupadd_lesson()
	{
			$data['details']= $this->book_model->getbook_details();
			$this->load->view('books/popups/addlesson_popup',$data);
	}
	/**
    * Function to addlesson
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function addlesson()
	{
			$data['book']=$_REQUEST['book'];
			$data['lessonname']=$_REQUEST['lessonname'];
			$returnFlag= $this->book_model->add_lesson($data);
			if($returnFlag) {
			$message['msg']   =  "Book added successfully.";
			$message['successFlag'] = "1";
			$message['link']  =  "popupaddCneter";
			$message['linkText'] = "Add New Center";
			$message['icoFile'] = "ico_addScheme.png";
			$this->load->view('dashboard/errorStatus_view',$message);
		} else {
			$message['msg']   =  "Book Not Added.";
			$message['successFlag'] = "0";
			$message['link']  =  "popupaddCneter";
			$message['linkText'] = "Add new Center";
			$message['icoFile'] = "ico_addScheme.png";
			$this->load->view('dashboard/errorStatus_view',$message);
			}
	}
	/**
    * Function to popupEdit_lesson
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupEdit_lesson()
	{
			$uid = $this->uri->segment(3);
			$data['details']= $this->book_model->getbook_details();
			$data['book_name']= $this->book_model->getlesson_name($uid);
			$this->load->view('books/popups/lesson_edit_view',$data);	
	
	}
	/**
    * Function to update_lesson
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function update_lesson()
	{
			$data['book_id']=$_REQUEST['book'];
			$data['rootId']=$_REQUEST['rootId'];
			$data['lessonname']=$_REQUEST['lessonname'];
			$returnFlag=$this->book_model->update_lesson($data);
			if($returnFlag == true) {
				$message['msg']   =  "Book edited successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "manage_books";
				$message['linkText'] = "";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);		  
			} else {
				$message['msg']   =  "Book not edited.";
				$message['successFlag'] = "0";
				$message['link']  =  "manage_books";
				$message['linkText'] = "";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);		  
		}
			
	}
	/**
    * Function to ajax_deletelesson
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function ajax_deletelesson()
	{
			$data['lesson_id']=$_REQUEST['entry_id'];
			$returnFlag= $this->book_model->delete_lesson($data);
	}
}