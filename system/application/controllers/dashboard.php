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
class Dashboard extends Controller  {
    function Dashboard() {
        parent::Controller();
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
		
		$this->load->helper('url');
        $this->load->helper('form');
		$this->load->model('center_model');
		$this->load->model('class_model');
		$this->load->model('kids_model');
		$this->load->model('level_model');
    }
	
    /**
    * Function to dashboard
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
    function dashboard_view() {	
		$data['title'] = 'MADApp Dashboard';
		$this->load->view('layout/header',$data);
		
		$upcomming_classes = $this->class_model->get_upcomming_unconfirmed_classes();
		$this->load->view('dashboard/dashboard', array('upcomming_classes'=>$upcomming_classes));
		$this->load->view('layout/footer');
    }
}
