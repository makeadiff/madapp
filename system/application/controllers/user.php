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
class User extends Controller  {
    /**
    * constructor 
    **/
    function User()
    {
        parent::Controller();
		$this->load->library('session');
        $this->load->library('user_auth');
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->helper('csv');
		$logged_user_id = $this->session->userdata('email');
		if($logged_user_id == NULL )
		{
			redirect('common/login');
		}
		$this->load->model('center_model');
		$this->load->model('project_model');
		$this->load->model('users_model');
		$this->load->model('city_model');
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
	/**
    * Function to manageadd_user
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function manageadd_user()
	{
		$data['currentPage'] = 'db';
		$data['navId'] = '';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$this->load->view('user/user_view');
		$this->load->view('dashboard/includes/footer');
	
	}
	/**
    * Function to get_userlist
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function get_userlist()
	{
		$page_no = $_REQUEST['pageno'];
		$data['title'] = 'Manage Users';
		$linkCount = $this->users_model->users_count();
		$data['linkCounter'] = ceil($linkCount/PAGINATION_CONSTANT);
		$data['currentPage'] = $page_no;
		$data['details']= $this->users_model->getuser_details();
		$this->load->view('user/user_list',$data);
	
	}
	/**
    * Function to popupAdduser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupAdduser()
	{
		$data['center']= $this->center_model->getcenter();
		$data['details']= $this->center_model->getcity();
		$data['project']= $this->project_model->getproject();
		$data['user_group']= $this->users_model->getgroup_details();
		$this->load->view('user/popups/add_user',$data);
	
	}
	/**
    * Function to adduser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function adduser()
	{
	$data['name'] = $_REQUEST['name'];
	$data['group'] = $_REQUEST['group'];
	$data['position'] = $_REQUEST['position'];
	$data['email'] = $_REQUEST['email'];
	$data['password'] = $_REQUEST['password'];
	$data['phone'] = $_REQUEST['phone'];
	$data['city'] = $_REQUEST['city'];
	$data['center'] = $_REQUEST['center'];
	$data['project'] = $_REQUEST['project'];
	$data['type'] = $_REQUEST['type'];
	$data['insert_id']= $this->users_model->adduser($data);
	if($data['insert_id'])
	{
	$returnFlag= $this->users_model->adduser_to_group($data);
	if($returnFlag)
		  {
		  		$message['msg']   =  "User Added successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "popupAdduser";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
		else
		  {
		  		$message['msg']   =  "No Action performed.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupAdduser";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);
		  }
	}
	else	
	{
		   	    $message['msg']   =  "No Group created.";
				$message['successFlag'] = "0";
				$message['link']  =  "popupAdduser";
				$message['linkText'] = "add new Center";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);
	}
	}
	/**
    * Function to popupEditusers
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function popupEditusers()
	{
		$uid = $this->uri->segment(3);
		$data['center']= $this->center_model->getcenter();
		$data['details']= $this->center_model->getcity();
		$data['project']= $this->project_model->getproject();
		//$data['user_group']= $this->users_model->getgroup_details();
		$data['user']= $this->users_model->user_details($uid);
		$content=$data['user']->result_array();
		foreach($content as $row) 
		{
			$uid=$row['group_id'];
		}
		
		$data['group_name'] = $this->users_model->edit_group($uid);
		$data['group_details'] = $this->users_model->getgroup_details();
		$this->load->view('user/popups/user_edit_view',$data);
	}
	/**
    * Function to update_user
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function update_user()
		{
		$data['rootId'] = $_REQUEST['rootId'];
		$data['name'] = $_REQUEST['name'];
		$data['group'] = $_REQUEST['group'];
		$data['position'] = $_REQUEST['position'];
		$data['email'] = $_REQUEST['email'];
		//$data['password'] = $_REQUEST['password'];
		$data['phone'] = $_REQUEST['phone'];
		$data['city'] = $_REQUEST['city'];
		$data['center'] = $_REQUEST['center'];
		$data['project'] = $_REQUEST['project'];
		$data['type'] = $_REQUEST['type'];
		$flag= $this->users_model->updateuser($data);
		$returnFlag= $this->users_model->updateuser_to_group($data);
		if($flag || $returnFlag ) 
		{
				$message['msg']   =  "Profile edited successfully.";
				$message['successFlag'] = "1";
				$message['link']  =  "";
				$message['linkText'] = "";
				$message['icoFile'] = "ico_addScheme.png";
				$this->load->view('dashboard/errorStatus_view',$message);		  
			} else {
				$message['msg']   =  "Profile not edited.";
				$message['successFlag'] = "0";
				$message['link']  =  "";
				$message['linkText'] = "";
				$message['icoFile'] = "ico_addScheme.png";
	
				$this->load->view('dashboard/errorStatus_view',$message);		  
		}
	}
	
	/**
    * Function to ajax_deleteuser
    * @author:Rabeesh 
    * @param :[$data]
    * @return: type: [Boolean, Array()]
    **/
	function ajax_deleteuser()
	{
		$data['entry_id'] = $_REQUEST['entry_id'];
		$flag1= $this->users_model->delete_groupby_userid($data);
	}
	function view_users()
	{
		$data['currentPage'] = 'db';
		$data['navId'] = '';
		$data['title'] = 'Users view';
		$this->load->view('dashboard/includes/header',$data);
		$this->load->view('dashboard/includes/superadminNavigation',$data);
		$data['city']= $this->city_model->get_city();
		$data['group']= $this->users_model->getgroup_details();
		$this->load->view('user/user_search_header',$data);
		$data['details']= $this->users_model->getuser_details();
		$result=$data['details']->result_array();
		if($result)
		{
			$this->load->view('user/users_search_view_div',$data);
			$this->load->view('user/user_search_footer',$data);
			$this->load->view('dashboard/includes/footer');
		}
		else
		{
			$this->load->view('user/error_list');
			$this->load->view('dashboard/includes/footer');
		
		}
	}
	function user_search()
	{
		$data['city']=$_REQUEST['city'];
		//$data['name']=$_REQUEST['name'];
		$group=$_REQUEST['group'];
		$data['title'] = 'Users view';
		$agents = substr($group,0,strlen($group)-1);
		$explode_agent = explode(",",trim($agents));
		for($i=0;$i<sizeof($explode_agent);$i++)
		{
		 	$data['group']=$explode_agent[$i];
		 	$data['details']= $this->users_model->searchuser_details($data);
			$flag=$data['details']->result_array();
			if($flag )
			{
			$this->load->view('user/update_search_view_list',$data);
			}
			else
			{
			//$this->load->view('user/error_list');
			}
		}
	}
	
	function csv_export()
	{
	$query= $this->users_model->getuser_details_csv();
	query_to_csv($query, TRUE, 'user_details.csv');
	}
	function update_footer()
	{
		$data['city']=$_REQUEST['city'];
		//$data['name']=$_REQUEST['name'];
		$group=$_REQUEST['group'];
		$group_sub = substr($group,0,strlen($group)-1);
		$group_ex= explode(",",trim($group_sub));
		$data['group'] =implode("-",$group_ex);
		$this->load->view('user/update_csvbutton_footer',$data);
	}
	function updated_csv_export()
	{
		$data['city']=$this->uri->segment(3);
		$group=$this->uri->segment(4);
		
		$explode_agent = explode("-",trim($group));
		for($i=0;$i<sizeof($explode_agent);$i++)
		{
		 	$data['group']=$explode_agent[$i];
		
			$query= $this->users_model->searchuser_details_csv($data);
			//print_r($query->result());
			$result=$query->result_array();
			//print_r($result);
		foreach($result as $row)
			{
				$id=$row['id'];
				$name=$row['name'];
				$title=$row['title'];
				$email=$row['email'];
				$phone=$row['phone'];
				$center_name=$row['center_name'];
				$city_name=$row['city_name'];
				$user_type=$row['user_type'];
				
				$details_array=array( $id, $name, $title,$email,$phone,$center_name,$city_name,$user_type);
				
			}
			//$header_array=array('id','Name','Position Held','Email','Mobile No','Center','City');
			$array[$i]=$details_array;
			//print_r($array);
			//$array_csv=array($header_array,$details_array);
			//$x=array();
			//$x=$array_csv;
			//print_r($array_csv);
			
		}
		
		/*print_r($x);
		$array123 = array(
	array('Last Name', 'First Name', 'Gender'),
	array('q', 'w', 'e'),
	array('r', 'S', 'f'),
	array('F', 'M', 'e')
);
print_r($array123);*/
		array_to_csv($array,'user_details.csv');
		
	}
}	