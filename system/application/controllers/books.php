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
		$data['title'] = 'Manage Books';
		$this->load->view('layout/header',$data);
		
		$data['details']= $this->book_model->getbook_details(0);
		$this->load->view('books/manage_books', $data);
		$this->load->view('layout/footer');
	}
	
	/**
    * Function to popupaddbooks
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupaddbooks()
	{
		$this->user_auth->check_permission('books_add');
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
		$this->user_auth->check_permission('books_add');
		echo "helo=".$data['bookname']=$_REQUEST['bookname'];
		$returnFlag=$this->book_model->add_book($data);
		if($returnFlag) {
			$this->session->set_flashdata('success', 'Book Inserted Successfully !');
			redirect('books/manage_books');
		} else {
			$this->session->set_flashdata('success', 'Book Insertion Failed !');
			redirect('books/manage_books');
		}
	}
	/**
    * Function to popupEdit_books
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupEdit_books($uid)
	{
		$this->user_auth->check_permission('books_edit');
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
		$data['root_id']=$_REQUEST['root_id'];
		$data['bookname']=$_REQUEST['bookname'];
		$returnFlag= $this->book_model->update_bookname($data);
		if($returnFlag == true) {
			$this->session->set_flashdata('success', 'Book Successfully Updated.');
			redirect('books/manage_books');	  
		} else {
			$this->session->set_flashdata('success', 'Book Updation failed !!');
			redirect('books/manage_books');	  
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
		$data['book_id']=$this->uri->segment(3);
		$returnFlag= $this->book_model->delete_bookname($data);
		if($returnFlag)
		{
		$this->session->set_flashdata('success', 'Book deleted Successfully !!');
		redirect('books/manage_books');	  
		}
	}
	/**
    * Function to manage_chapters
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function manage_chapters()
	{
		$data['title'] = 'Manage Chapters';
		$this->load->view('layout/header',$data);
		$data['details']= $this->book_model->getlesson_details(0);
		$this->load->view('books/manage_chapters', $data);
		$this->load->view('layout/footer');
	
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
			$this->session->set_flashdata('success', 'Lesson Successfully Inserted!');
			redirect('books/manage_chapters');
		} else {
			$this->session->set_flashdata('success', 'Insertion failed!');
			redirect('books/manage_chapters');
		}
	}
	/**
    * Function to popupEdit_lesson
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupEdit_lesson($uid)
	{
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
			$data['rootId']=$_REQUEST['root_id'];;
			$data['book_id']=$_REQUEST['book'];
			
			$data['lessonname']=$_REQUEST['lessonname'];
			$returnFlag=$this->book_model->update_lesson($data);
			if($returnFlag == true) {
				$this->session->set_flashdata('success', 'Lesson Successfully Updated!');
				redirect('books/manage_chapters');
			} else {
				$this->session->set_flashdata('success', 'Lesson Updation Failed!');
				redirect('books/manage_chapters');
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
			$data['lesson_id']=$this->uri->segment(3);
			$returnFlag= $this->book_model->delete_lesson($data);
			if($returnFlag)
			{
			$this->session->set_flashdata('success', 'Lesson Successfully Deleted!');
				redirect('books/manage_chapters');
			} else{
			$this->session->set_flashdata('success', 'Failed To delete Lesson!');
				redirect('books/manage_chapters');
			}
	}
}