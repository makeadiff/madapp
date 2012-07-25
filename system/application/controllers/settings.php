<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
class Settings extends Controller {

    private $message;

    /**
     * constructor 
     * */
    function Settings() {
        parent::Controller();
        $message = array('success' => false, 'error' => false);

        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->session->userdata('id');
        if ($logged_user_id == NULL) {
            redirect('auth/login');
        }
        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->helper('misc');
        $this->load->scaffolding('Setting');
        $this->load->model('settings_model', 'model', TRUE);

        $this->load->model('Users_model', 'user_model');
    }

    /*
     * Function Name : index()
     * Wroking :This function used for showing index of setting window
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
    function index() {
        $this->user_auth->check_permission('setting_index');
        $all_settings = $this->model->getsettings();
        $this->load->view('settings/settings_index', array('all_settings' => $all_settings, 'message' => $this->message));
    }

    /*
     * Function Name : add_settings()
     * Wroking :This function used for showing settings add window
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
    function add_settings() {
        $this->load->view('settings/settings_view.php');
    }

    /*
     * Function Name : setting_list_refresh()
     * Wroking :This function used for refresh the settings list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
    function setting_list_refresh() {
        $all_settings = $this->model->getsettings();
        $this->load->view('settings/setting_update', array('all_settings' => $all_settings, 'message' => $this->message));
    }

    /*
     * Function Name : create()
     * Wroking :This function used for saving the settings list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
    function create() {
        $this->user_auth->check_permission('setting_create');
        // Make a new setting.
        $name = $_REQUEST['name'];
        $value = $_REQUEST['value'];
        $data = $_REQUEST['data'];
        $data = array(
            'name' => $name,
            'value' => $value,
            'data' => $data,
        );
        $returnFlag = $this->model->addsetting($data);
        if ($returnFlag) {
            $this->session->set_flashdata('success', 'Settings Inserted Successfully !');
            redirect('settings/index');
        } else {
            $this->session->set_flashdata('success', 'Settings Insertion Failed !');
            redirect('settings/index');
        }
    }

    /*
     * Function Name : edit_settings()
     * Wroking :This function used for showing edit window of settings list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
    function edit_settings() {
        $settings_id = $this->uri->segment(3);
        $settings = $this->model->get_settings($settings_id);
        $this->load->view('settings/settings_editview.php', array('setting' => $settings));
    }

    /*
     * Function Name : edit()
     * Wroking :This function used for updating the settings list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
    function edit() {
        $this->user_auth->check_permission('setting_edit');
        $settings_id = $this->uri->segment(3);
        $name = $_REQUEST['name'];
        $value = $_REQUEST['value'];
        $data = $_REQUEST['data'];
        $data = array(
            'name' => $name,
            'value' => $value,
            'data' => $data,
        );
        $returnFlag = $this->model->editsetting($data, $settings_id);
        if ($returnFlag) {
            $this->session->set_flashdata('success', 'The Setting has been edited !');
            redirect('settings/index');
        } else {
            $this->session->set_flashdata('success', 'Settings Updation Failed !');
            redirect('settings/index');
        }
    }

    /*
     * Function Name : delete()
     * Wroking :This function used for deleting the settings list
     * @author:Rabeesh
     * @param :[]
     * @return: type: []
     */
    function delete() {
        $id = $this->uri->segment(3);
        $this->model->deletesetting($id);
        $this->message['success'] = 'The Setting successfully deleted';
        $this->index();
    }

}