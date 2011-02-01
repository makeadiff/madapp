<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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

class Kids extends Controller  {

    /**
    * constructor 
    **/

    function Kids() {
        parent::Controller();
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->helper('file');
		$this->load->model('center_model');
		$this->load->model('kids_model');
		$this->load->model('level_model');
		$this->load->library('upload');
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
    * Function to manageaddkids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function manageaddkids()
	{
		$data['currentPage'] = 'db';
		$data['navId'] = '2';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('kids/addkids_view');
		$this->load->view('dashboard/includes/footer');
	
	}
   /**
    *
    * Function to getkidslist
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function getkidslist()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Kids';
		$linkCount = $this->kids_model->kids_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->kids_model->getkids_details();
		$this->load->view('kids/kids_list',$data);
	
	}
	/**
    *
    * Function to popupaddKids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupaddKids()
	{	
		//$this->user_auth->check_permission('kids_addkids');
		$data['center']= $this->center_model->getcenter();
		$this->load->view('kids/popups/addkids_popup',$data);
	
	}
	/**
    *
    * Function to popupEdit_kids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function popupEdit_kids()
	{
		//$this->user_auth->check_permission('kids_editkids');
		$uid = $this->uri->segment(3);
		$data['center']= $this->center_model->getcenter();
		$data['kids_details']= $this->kids_model->get_kids_details($uid);
		$this->load->view('kids/popups/kids_edit_view',$data);
	
	}
	/**
    *
    * Function to update_kids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function update_kids()
	{
		$data['rootId'] = $_REQUEST['rootId'];
		$id=$data['rootId'];
		$data['id']=$id;
		$data['center']=$_REQUEST['center'];
		$data['name']=$_REQUEST['name'];
		$date=$_REQUEST['date-pick'];
		$newdate=explode("/",$date);
		$data['date']=$newdate[2]."/".$newdate[1]."/".$newdate[0];
		$data['description']=$_REQUEST['description'];
		
		
		$returnFlag= $this->kids_model->update_student($data);
		
		$config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg
		
	
	
		foreach($_FILES as $key => $value)
        {
            if( ! empty($key['name']))
            {
                $this->upload->initialize($config);
        
                if ( ! $this->upload->do_upload($key))
                {
                    $errors[] = $this->upload->display_errors();
                    
                }    
                else
                {
                    $flag=$this->kids_model->process_pic($data);

                }
             }
        
        }
		
		
		
		
		
		
		if($returnFlag == true || $flag== true) 
			  {
					$message['msg']   =  "Student updated successfully.";
					$message['successFlag'] = "1";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('dashboard/errorStatus_view',$message);		  
			  }
			else
			  {
					$message['msg']   =  "Center not edited.";
					$message['successFlag'] = "0";
					$message['link']  =  "";
					$message['linkText'] = "";
					$message['icoFile'] = "ico_addScheme.png";
		
					$this->load->view('dashboard/errorStatus_view',$message);		  
			 }
	
	
	}
	/**
    *
    * Function to addkids
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function addkids()
	{
	$data['center']=$_REQUEST['center'];
	$data['name']=$_REQUEST['name'];
	$date=$_REQUEST['date-pick'];
	$newdate=explode("/",$date);
	$data['date']=$newdate[2]."/".$newdate[1]."/".$newdate[0];
	$data['description']=$_REQUEST['description'];
	
	$returnFlag= $this->kids_model->add_kids($data);
	$data['id']=$returnFlag;
	
		$config['upload_path'] = './uploads/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['max_size']    = '1000'; //2 meg
		
	
	
		foreach($_FILES as $key => $value)
        {
            if( ! empty($key['name']))
            {
                $this->upload->initialize($config);
        
                if ( ! $this->upload->do_upload($key))
                {
                    $errors[] = $this->upload->display_errors();
                    
                }    
                else
                {
                    $this->kids_model->process_pic($data);

                }
             }
        
        }
	
	
	
	
	if($returnFlag)
		  {
		  		$message['msg']   =  "Student added successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "popupaddKids";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
			
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
		else
		  {
		  		$message['msg']   =  "no updates performed.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupaddCneter";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
			
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
	}
	
	

	/**
    *
    * Function to ajax_deleteStudent
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function ajax_deleteStudent()
	{
		$data['entry_id'] = $_REQUEST['entry_id'];
		$flag= $this->kids_model->delete_kids($data);
	}
}