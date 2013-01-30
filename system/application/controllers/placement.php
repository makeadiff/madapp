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
class Placement extends Controller {

    function Placement() {
        parent::Controller();
        $this->load->library('session');
        $this->load->library('user_auth');
        $logged_user_id = $this->user_auth->logged_in();
        if (!$logged_user_id) {
            redirect('auth/login');
        }

        $this->load->helper('url');
        $this->load->helper('form');
        $this->load->model('class_model');
        $this->load->model('users_model');
        $this->load->model('placement_model');
        $this->load->model('permission_model');
        $this->load->library('upload');
    }

    /**
     * Function to dashboard
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     * */
    function placement_view() {
        $data['title'] = 'MADApp Placement';
        $this->load->view('layout/header', $data);
        $upcomming_classes = $this->class_model->get_upcomming_classes();
        $current_user = $this->users_model->get_user($this->session->userdata('id'));
        $this->load->view('placement/placement', array('upcomming_classes' => $upcomming_classes, 'current_user' => $current_user));
        $this->load->view('layout/footer');
    }

    /**
     *
     * Function to manageadd_group
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function manageaddchild_group() {


        $data['title'] = 'Manage Child Group';
        $data['details'] = $this->placement_model->getgroup_details();
        $this->load->view('layout/header', $data);
        $this->load->view('placement/add_groupname_view', $data);
        $this->load->view('layout/footer');
    }

    /**
     *
     * Function to popupaddgroup
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function popupaddgroup() {

        $data['center'] = $this->placement_model->getcenter_details();
        $this->load->view('placement/popups/add_group', $data);
    }

    /**
     *
     * Function to addgroup_name
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function addgroup_name() {


        $data['groupname'] = $_REQUEST['groupname'];
        $data['cgroup'] = $_REQUEST['cgroup'];
        $data['centreid'] = $_REQUEST['center_id'];
        $data['sex'] = $_REQUEST['sex'];
        $data['actfrq'] = $_REQUEST['actfrq'];
        $data['code'] = rand(10, 1000);

        $group_id = $this->placement_model->add_group_name($data);
        if ($group_id) {
            $this->session->set_flashdata('success', "Successfully Inserted");
            redirect('placement/manageaddchild_group');
        } else {
            $this->session->set_flashdata('error', "Not Inserted");
            redirect('placement/manageaddchild_group');
        }
    }

    /**
     *
     * Function to popupEdit_group
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function popupEdit_group() {

        $uid = $this->uri->segment(3);
        $data['details'] = $this->placement_model->edit_group($uid);
        $data['center'] = $this->placement_model->getcenter_details();
        //$data['permission']= $this->permission_model->getpermission_details();
        //$data['group_permission']= $this->permission_model->getgroup_permission_details($uid);
        $this->load->view('placement/popups/group_edit_view', $data);
    }

    /**
     *
     * Function to updategroup_name
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function updategroup_name() {


        $data['group_id'] = $this->uri->segment(3);
        $data['group_name'] = $_REQUEST['groupname'];
        $data['cgroup'] = $_REQUEST['cgroup'];
        $data['centreid'] = $_REQUEST['center_id'];
        $data['sex'] = $_REQUEST['sex'];
        $data['actfrq'] = $_REQUEST['actfrq'];
        $data['code'] = rand(10, 1000);

        $returnFlag = $this->placement_model->update_group($data);
        if ($returnFlag) {
            $this->session->set_flashdata('success', "Successfully Updated");
            redirect('placement/manageaddchild_group');
        } else {
            $this->session->set_flashdata('error', "Updation Failed");
            redirect('placement/manageaddchild_group');
        }
    }

    /**
     *
     * Function to ajax_deletegroup
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function ajax_deletegroup() {

        $data['entry_id'] = $this->uri->segment(3);
        $flag = $this->placement_model->delete_group($data);
        if (flag) {
            $this->session->set_flashdata('success', "Child Group Deleted Successfully");
            redirect('placement/manageaddchild_group');
        } else {
            $this->session->set_flashdata('success', "Failed To Delete Group");
            redirect('placement/manageaddchild_group');
        }
    }

    /**
     *
     * Function to manageadd_group
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function manageplacement_activity() {


        $data['title'] = 'Manage Placement Repository';
        $data['details'] = $this->placement_model->getactivity_details();
        $this->load->view('layout/header', $data);
        $this->load->view('placement/add_activity_view', $data);
        $this->load->view('layout/footer');
    }

    /**
     *
     * Function to popupaddgroup
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function popupaddactivity() {

        $data['center'] = $this->placement_model->getcenter_details();
        $data['city'] = $this->placement_model->cityactivity_details();
        $this->load->view('placement/popups/add_activity', $data);
    }

    /**
     *
     * Function to addgroup_name
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function addactivity_name() {
        $data['filename'] == '';
        $data['activityname'] = $_REQUEST['activityname'];
        $data['locact'] = $_REQUEST['locact'];

        $data['skill'] = $_REQUEST['skill'];
        if ($data['skill'] != '1') {
            $data['skill'] = '0';
        }
        $data['sex'] = $_REQUEST['sex'];
        $data['career'] = $_REQUEST['career'];
        if ($data['career'] != '1') {
            $data['career'] = '0';
        }

        $data['generalised'] = $_REQUEST['generalised'];
        if ($data['generalised'] != '1') {
            $data['generalised'] = '0';
        }
        $data['specialised'] = $_REQUEST['specialised'];
        if ($data['specialised'] != '1') {
            $data['specialised'] = '0';
        }
        $data['field_expert'] = $_REQUEST['field_expert'];
        if ($data['field_expert'] != '1') {
            $data['field_expert'] = '0';
        }

        $config['upload_path'] = dirname(BASEPATH) . '/uploads/';
        $config['allowed_types'] = 'gif|jpg|png|xls|doc|docx|pdf|JPG|PNG|GIF|JPEG|jpeg';
        $config['max_size'] = '100000'; //2 meg
        foreach ($_FILES as $key => $value) {
            if (!empty($key['name'])) {
                $this->upload->initialize($config);

                if (!$this->upload->do_upload($key)) {
                    $errors[] = $this->upload->display_errors();
                } else {
//                            $data = $this->upload->data();
//                            $data['filename'] = $data['file_name'];
                }
            }
        }

        $datas = $this->upload->data();
        $data['filename'] = $datas['file_name'];
        $data['link'] = $_REQUEST['link'];
        $data['creator'] = $_REQUEST['creator'];
        $data['track'] = $_REQUEST['track'];
        $data['class-range'] = $_REQUEST['class-range'];

        $group_id = $this->placement_model->add_activity_name($data);
        if ($group_id) {
            $this->session->set_flashdata('success', "Successfully Inserted");
            redirect('placement/manageplacement_activity');
        } else {
            $this->session->set_flashdata('error', "Not Inserted");
            redirect('placement/manageplacement_activity');
        }
    }

    /**
     *
     * Function to popupEdit_group
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function popupEdit_activity() {

        $uid = $this->uri->segment(3);
        $data['details'] = $this->placement_model->edit_activity($uid);
        $data['city'] = $this->placement_model->cityactivity_details();
        $this->load->view('placement/popups/group_activityedit_view', $data);
    }

    /**
     *
     * Function to updategroup_name
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function updateactivity_name() {

        $data['filename'] == '';
        $data['group_id'] = $this->uri->segment(3);
        $data['activityname'] = $_REQUEST['activityname'];
        $data['locact'] = $_REQUEST['locact'];
        $data['skill'] = $_REQUEST['skill'];
        if ($data['skill'] != '1') {
            $data['skill'] = '0';
        }
        $data['sex'] = $_REQUEST['sex'];
        $data['career'] = $_REQUEST['career'];
        if ($data['career'] != '1') {
            $data['career'] = '0';
        }

        $data['generalised'] = $_REQUEST['generalised'];
        if ($data['generalised'] != '1') {
            $data['generalised'] = '0';
        }
        $data['specialised'] = $_REQUEST['specialised'];
        if ($data['specialised'] != '1') {
            $data['specialised'] = '0';
        }
        $data['field_expert'] = $_REQUEST['field_expert'];
        if ($data['field_expert'] != '1') {
            $data['field_expert'] = '0';
        }
        $data['creator'] = $_REQUEST['creator'];
        $data['track'] = $_REQUEST['track'];
        $data['class-range'] = $_REQUEST['class-range'];
        $data['link'] = $_REQUEST['link'];

        $config['upload_path'] = dirname(BASEPATH) . '/uploads/';
        $config['allowed_types'] = 'gif|jpg|png|xls|doc|docx|pdf|JPG|PNG|GIF|JPEG|jpeg';
        $config['max_size'] = '100000'; //2 meg
        foreach ($_FILES as $key => $value) {
            if (!empty($key['name'])) {
                $this->upload->initialize($config);

                if (!$this->upload->do_upload($key)) {
                    $errors[] = $this->upload->display_errors();
                } else {
//                            $data = $this->upload->data();
//                            $data['filename'] = $data['file_name'];
                }
            }
        }

        $datas = $this->upload->data();
        if($datas['file_name'])
        {
        $data['filename'] = $datas['file_name'];
        }
        else
        {
         $data['filename'] = $_REQUEST['previous_file'];   
        }
        $returnFlag = $this->placement_model->update_activity($data);

        if ($returnFlag) {
            $this->session->set_flashdata('success', "Successfully Updated");
            redirect('placement/manageplacement_activity');
        } else {
            $this->session->set_flashdata('error', "Updation Failed");
            redirect('placement/manageplacement_activity');
        }
    }
     /**
     *
     * Function to download afile
     * @author : Siju
     * @param  : []
     * @return : type : []
     *
     * */
    function manage_downloads()
    {
        $filename = $this->uri->segment(3);
        $data = file_get_contents(dirname(BASEPATH) . "/uploads/".$filename); // Read the file's contents
//$name = 'myphoto.jpg';

$this->force_download($filename, $data);
    }

    /**
     *
     * Function to ajax_deleteactivity
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function ajax_deleteactivity() {

        $data['entry_id'] = $this->uri->segment(3);
        $flag = $this->placement_model->delete_activity($data);
        if (flag) {
            $this->session->set_flashdata('success', "Activity Deleted Successfully");
            redirect('placement/manageplacement_activity');
        } else {
            $this->session->set_flashdata('success', "Failed To Delete Activity");
            redirect('placement/manageplacement_activity');
        }
    }

    /**
     *
     * Function to manageadd_event
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function manageevents() {


        $data['title'] = 'Manage Events';
        $data['details'] = $this->placement_model->getevent_details();
        $data['user'] = $this->placement_model->get_user_details();
        $data['city'] = $this->placement_model->cityactivity_details();
        $this->load->view('layout/header', $data);
        $this->load->view('placement/add_eventname_view', $data);
        $this->load->view('layout/footer');
    }

    /**
     *
     * Function to popupaddevent
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function popupaddevent() {

        $data['group'] = $this->placement_model->getgroup_details();
        $data['activity'] = $this->placement_model->getactivity_details();
        $data['city'] = $this->placement_model->cityactivity_details();
        $data['user'] = $this->placement_model->get_user_details();
        $data['usid'] = $this->session->userdata('id');
        $this->load->view('placement/popups/add_event', $data);
    }

    // Ajax functions
    function get_corporate($corporate) {
        if ($corporate == 1) {
            $this->load->view('placement/ajax/corporate_details');
        }
    }

    /**
     *
     * Function to addevent_name
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function addevent_name() {

        $data['eventname'] = $this->input->post('eventname');
        $data['city'] = $this->input->post('city');
        $data['datepick'] = $this->input->post('date-pick');
        $data['group_id'] = $this->input->post('group_id');
        $data['activity_id'] = $this->input->post('activity_id');

        if ($this->input->post('corporate') == 1) {
            $data['corpname'] = $this->input->post('corpname');
            $data['novol'] = $this->input->post('novol');
            $data['corpoc'] = $this->input->post('corpoc');
            $data['crintrn'] = $this->input->post('crintrn');
        } else {
            $data['corpname'] = '';
            $data['novol'] = '';
            $data['corpoc'] = '';
            $data['crintrn'] = '';
        }
        //$data['usid'] = $this->session->userdata('id');
        $data['usid'] = $this->input->post('owner');
        $group_id = $this->placement_model->add_event_name($data);
        if ($group_id) {
            $data['event_id'] = $group_id;
            $this->placement_model->add_event_group_name($data);
            $this->session->set_flashdata('success', "Successfully Inserted");
            redirect('placement/manageevents');
        } else {
            $this->session->set_flashdata('error', "Not Inserted");
            redirect('placement/manageevents');
        }
    }

    /**
     *
     * Function to popupEdit_event
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function popupEdit_event() {

        $uid = $this->uri->segment(3);
        $data['details'] = $this->placement_model->edit_event($uid);
        $data['activity'] = $this->placement_model->getactivity_details();
        $this->load->view('placement/popups/event_edit_view', $data);
    }

    /**
     *
     * Function to add feedback
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function addfeedback() {
//print_r($this->input->post('attendance'));
        $data['eventid'] = $this->input->post('event_id');

        $data['feedback_score'] = $this->input->post('feedback_score');
        //$data['attendance'] = $this->input->post('attendance');
        $data['feedback_career'] = $this->input->post('feedback_career');
        $data['feedback_repeat'] = $this->input->post('feedback_repeat');
        $data['feedback_volunteer_count'] = $this->input->post('feedback_volunteer_count');
        $data['feedback_volunteer_repeat_strongly_agree'] = $this->input->post('feedback_volunteer_repeat_strongly_agree');
        $data['feedback_volunteer_repeat_agree'] = $this->input->post('feedback_volunteer_repeat_agree');
        $data['feedback_volunteer_repeat_strongly_neutral'] = $this->input->post('feedback_volunteer_repeat_strongly_neutral');
        $data['feedback_volunteer_repeat_disagree'] = $this->input->post('feedback_volunteer_repeat_disagree');
        $data['feedback_volunteer_repeat_strongly_disagree'] = $this->input->post('feedback_volunteer_repeat_strongly_disagree');
        $data['feedback_volunteer_engaging_strongly_agree'] = $this->input->post('feedback_volunteer_engaging_strongly_agree');
        $data['feedback_volunteer_engaging_agree'] = $this->input->post('feedback_volunteer_engaging_agree');
        $data['feedback_volunteer_engaging_strongly_neutral'] = $this->input->post('feedback_volunteer_engaging_strongly_neutral');
        $data['feedback_volunteer_engaging_disagree'] = $this->input->post('feedback_volunteer_engaging_disagree');
        $data['feedback_volunteer_engaging_strongly_disagree'] = $this->input->post('feedback_volunteer_engaging_strongly_disagree');
        $data['feedback_volunteer_suggestion'] = $this->input->post('feedback_volunteer_suggestion');

        $data['feedback_partner_engaging_strongly_agree'] = $this->input->post('feedback_partner_engaging_strongly_agree');
        $data['feedback_partner_engaging_agree'] = $this->input->post('feedback_partner_engaging_agree');
        $data['feedback_partner_engaging_neutral'] = $this->input->post('feedback_partner_engaging_neutral');
        $data['feedback_partner_engaging_disagree'] = $this->input->post('feedback_partner_engaging_disagree');
        $data['feedback_partner_engaging_strongly_disagree'] = $this->input->post('feedback_partner_engaging_strongly_disagree');
        $data['feedback_partner_rating_excelent'] = $this->input->post('feedback_partner_rating_excelent');
        $data['feedback_partner_rating_very_good'] = $this->input->post('feedback_partner_rating_very_good');
        $data['feedback_partner_rating_average'] = $this->input->post('feedback_partner_rating_average');
        $data['feedback_partner_rating_poor'] = $this->input->post('feedback_partner_rating_poor');
        $data['feedback_partner_rating_very_poor'] = $this->input->post('feedback_partner_rating_very_poor');



        //     if ($data['eventid'] && $data['feedback_score'] && $data['feedback_volunteer_count'] && $data['feedback_partner_engaging_strongly_agree']) {
//            $data['event_id'] = $group_id;
        if ($this->placement_model->add_feedback($data)) {
            $this->session->set_flashdata('success', "Successfully Inserted");
            redirect('placement/manageevents');
        } else {
            $this->session->set_flashdata('error', "Not Inserted");
            redirect('placement/manageevents');
        }
        // } else {
//            $this->session->set_flashdata('error', "Not Inserted");
//            redirect('placement/manageevents');
//        }
    }

    /**
     *
     * Function to updateevent_name
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function updateevent_name() {

        $data['event_id'] = $this->uri->segment(3);
        $data['eventname'] = $this->input->post('eventname');
        $data['datepick'] = $this->input->post('date-pick');
        $data['activity_id'] = $this->input->post('activity_id');
        if ($this->input->post('corporate') == 1) {
            $data['corpname'] = $this->input->post('corpname');
            $data['novol'] = $this->input->post('novol');
            $data['corpoc'] = $this->input->post('corpoc');
            $data['crintrn'] = $this->input->post('crintrn');
        } else {
            $data['corpname'] = '';
            $data['novol'] = '';
            $data['corpoc'] = '';
            $data['crintrn'] = '';
        }
        $returnFlag = $this->placement_model->update_event($data);
        if ($returnFlag) {
            $this->session->set_flashdata('success', "Successfully Updated");
            redirect('placement/manageevents');
        } else {
            $this->session->set_flashdata('error', "Updation Failed");
            redirect('placement/manageevents');
        }
    }

    /**
     *
     * Function to ajax_deleteevent
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function ajax_deleteevent() {

        $data['entry_id'] = $this->uri->segment(3);
        $flag = $this->placement_model->delete_event($data);
        if (flag) {
            $this->session->set_flashdata('success', "Event Deleted Successfully");
            redirect('placement/manageevents');
        } else {
            $this->session->set_flashdata('success', "Failed To Delete Event");
            redirect('placement/manageevents');
        }
    }

    // Ajax functions
    function get_corporate_update($corporate, $id) {
        if ($corporate == 1) {
            $data['details'] = $this->placement_model->edit_event($id);
            $this->load->view('placement/ajax/updatecorporate_details', $data);
        }
    }

    /**
     *
     * Function to popupaddevent
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function popupaddfeedback() {

        $data['event'] = $this->placement_model->getevent_details();
        $this->load->view('placement/popups/add_feedback', $data);
    }

    function get_feedback($event) {
        $data['feedback'] = $this->placement_model->getevent_feedback_details($event);
        $data['student'] = $this->placement_model->getevent_student_details($event);
        //print_r($data['feedback']);
        $this->load->view('placement/ajax/feedback_details', $data);
    }

    /**
     *
     * Function to popud mark attendance
     * @author : Siju
     * @param  : []
     * @return : type : []
     *
     * */
    function popupmarkattendance() {

        $data['event'] = $this->placement_model->getevent_details();
        $this->load->view('placement/popups/mark_attendance', $data);
    }

    function get_student($event) {
        $data['feedback'] = $this->placement_model->getevent_feedback_details($event);
        $data['student'] = $this->placement_model->getevent_student_details($event);
        //print_r($data['feedback']);
        $this->load->view('placement/ajax/student_details', $data);
    }

    /**
     *
     * Function to add attendance
     * @author : Siju
     * @param  : []
     * @return : type : []
     *
     * */
    function markattendance() {
        print_r($this->input->post('attendance'));
        $data['eventid'] = $this->input->post('event_id');
        $data['attendance'] = $this->input->post('attendance');
        if ($this->placement_model->mark_attendance($data)) {
            $this->session->set_flashdata('success', "Successfully Inserted");
            redirect('placement/manageevents');
        } else {
            $this->session->set_flashdata('error', "Not Inserted");
            redirect('placement/manageevents');
        }
    }

    /**
     *
     * Function to Events Calendar 
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function eventscalendar() {
        $data['title'] = 'Events Calendar';
        $data['calenderdetails'] = $this->placement_model->getcalendar_event_details();
        $this->load->view('layout/header', $data);
        $this->load->view('placement/eventscalendar_view', $data);
        $this->load->view('layout/footer');
    }

    function popuplistevents() {
        $uid = $this->uri->segment(3);
        $data['title'] = 'Events On-' . $uid;
        $data['list_details'] = $this->placement_model->list_event($uid);
        $data['city'] = $this->placement_model->cityactivity_details();
        $this->load->view('layout/header', $data);
        $this->load->view('placement/popups/event_list_view', $data);
        $this->load->view('layout/footer');
    }

    /**
     *
     * Function to popupEditCalender_event
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function popupEditCalender_event() {

        $uid = $this->uri->segment(3);
        $data['details'] = $this->placement_model->edit_event($uid);
        $data['activity'] = $this->placement_model->getactivity_details();
        $data['feedback'] = $this->placement_model->getevent_feedback_details($uid);
        $data['student'] = $this->placement_model->getevent_student_details($uid);
        $this->load->view('placement/popups/calendar_event_edit_view', $data);
    }

    /**
     *
     * Function to updateevent_name
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function update_calendenar_event_name() {

        $data['event_id'] = $this->uri->segment(3);
        $data['eventname'] = $this->input->post('eventname');
        $data['datepick'] = $this->input->post('date-pick');
        $data['activity_id'] = $this->input->post('activity_id');
        $data['started_date'] = $this->input->post('started_date');
        $data['attendance'] = $this->input->post('attendance');
        if ($this->input->post('corporate') == 1) {
            $data['corpname'] = $this->input->post('corpname');
            $data['novol'] = $this->input->post('novol');
            $data['corpoc'] = $this->input->post('corpoc');
            $data['crintrn'] = $this->input->post('crintrn');
        } else {
            $data['corpname'] = '';
            $data['novol'] = '';
            $data['corpoc'] = '';
            $data['crintrn'] = '';
        }
        $returnFlag = $this->placement_model->update_calendar_event($data);
        if ($returnFlag) {
            $this->session->set_flashdata('success', "Successfully Updated");
            redirect('placement/popuplistevents/' . $data['started_date']);
        } else {
            $this->session->set_flashdata('error', "Updation Failed");
            redirect('placement/popuplistevents/' . $data['started_date']);
        }
    }

    /**
     *
     * Function to ajax_calendar_deleteevent
     * @author : Rabeesh
     * @param  : []
     * @return : type : []
     *
     * */
    function ajax_calendar_deleteevent() {

        $data['entry_id'] = $this->uri->segment(3);
        $flag = $this->placement_model->delete_event($data);
        if (flag) {
            $this->session->set_flashdata('success', "Event Deleted Successfully");
            redirect('placement/eventscalendar');
        } else {
            $this->session->set_flashdata('success', "Failed To Delete Event");
            redirect('placement/eventscalendar');
        }
    }

    /**
     * Function to view report page
     * @author:Siju 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function report() {
        $data['title'] = 'Placement - Report';
        $this->load->view('layout/header', $data);
        $this->load->view('placement/report_view', $data);
        $this->load->view('layout/footer');
    }

    /**
     * Function to view child impact report 
     * @author:Siju 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function childimpact() {
        $data['title'] = 'Placement - child impact';
        $data['groupdetails'] = $this->placement_model->getgroup_details();
        $this->load->view('layout/header', $data);
        $this->load->view('placement/childimpact_view', $data);
        $norecord_flag = 1;
        $shadeFlag = 0;
        $shadeClass = '';
        $statusIco = '';
        $statusText = '';
        $content = $data['groupdetails']->result_array();
        $i = 0;
        foreach ($content as $row) {
            $data['name'] = $row['name'];
            $data['id'] = $row['id'];
            $i++;
            $norecord_flag = 0;

            if ($shadeFlag == 0) {
                $data['shadeClass'] = 'even';
                $shadeFlag = 1;
            } else if ($shadeFlag == 1) {
                $data['shadeClass'] = 'odd';
                $shadeFlag = 0;
            }
            $this->load->view('placement/childimpact_view1', $data);
            $activitycount = $this->placement_model->activitycount_details($data['id']);
            $data['actcount'] = $activitycount->num_rows();
            $activitycount = $activitycount->result_array();

            foreach ($activitycount as $row) {

                $temp = explode('-', $row['started_on']);
                $data['month'] = $temp[1];
                $data['days'] = (strtotime(date("Y-m-d")) - strtotime($row['started_on'])) / (60 * 60 * 24);
            }
            $this->load->view('placement/childimpact_view3', $data);
        }
        $this->load->view('placement/childimpact_view2', $data);


        $this->load->view('layout/footer');
    }

    function childimpact_kids() {

        $group_id = $this->uri->segment(3);
        $data['groupdetails_kids'] = $this->placement_model->getgroup_student_details($group_id);
        $this->load->view('placement/childimpact_kids_view', $data);
    }

    function event_days_count() {
        $group_id = $this->uri->segment(3);
        $data['event_days'] = $this->placement_model->event_days_count_details($group_id);
        $this->load->view('placement/event_days_count_view', $data);
    }

    /**
     * Function to view intern record 
     * @author:Siju 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function internrecord() {
        $data['title'] = 'Placement - intern record';
        $data['groupdetails'] = $this->placement_model->getstudentgroup_details();
        $this->load->view('layout/header', $data);
        $this->load->view('placement/internrecord_view', $data);
        $norecord_flag = 1;
        $shadeFlag = 0;
        $shadeClass = '';
        $statusIco = '';
        $statusText = '';
        $content = $data['groupdetails']->result_array();

        $i = 0;
        foreach ($content as $row) {
            $data['name'] = $row['name'];
            $data['id'] = $row['id'];
            $interncount = $this->placement_model->getstudentgroupcount_details($data['id']);
            $data['interncount'] = $interncount->num_rows();

            $i++;
            $norecord_flag = 0;

            if ($shadeFlag == 0) {
                $data['shadeClass'] = 'even';
                $shadeFlag = 1;
            } else if ($shadeFlag == 1) {
                $data['shadeClass'] = 'odd';
                $shadeFlag = 0;
            }

            $test = $this->placement_model->getjan($data['id']);
            $data['jan'] = $test->num_rows();
            $test = $this->placement_model->getfeb($data['id']);
            $data['feb'] = $test->num_rows();
            $test = $this->placement_model->getmar($data['id']);
            $data['mar'] = $test->num_rows();
            $test = $this->placement_model->getapril($data['id']);
            $data['apr'] = $test->num_rows();
            $test = $this->placement_model->getmay($data['id']);
            $data['may'] = $test->num_rows();
            $test = $this->placement_model->getjune($data['id']);
            $data['jun'] = $test->num_rows();
            $test = $this->placement_model->getjuly($data['id']);
            $data['jly'] = $test->num_rows();
            $test = $this->placement_model->getaug($data['id']);
            $data['aug'] = $test->num_rows();
            $test = $this->placement_model->getsep($data['id']);
            $data['sep'] = $test->num_rows();
            $test = $this->placement_model->getoct($data['id']);
            $data['oct'] = $test->num_rows();
            $test = $this->placement_model->getnov($data['id']);
            $data['nov'] = $test->num_rows();
            $test = $this->placement_model->getdec($data['id']);
            $data['dec'] = $test->num_rows();

            $this->load->view('placement/internrecord_view1', $data);
        }
        $this->load->view('placement/internrecord_view2', $data);
        $this->load->view('layout/footer');
    }

    function internrecord_activity() {

        $group_id = $this->uri->segment(3);
        $data['groupdetails_kids'] = $this->placement_model->getgroup_student_details($group_id);
        $this->load->view('placement/childimpact_kids_view', $data);
    }

    /**
     * Function to view city log 
     * @author:Siju 
     * @param :[$data]
     * @return: type: [Boolean,]
     * */
    function activitylog() {
        $data['title'] = 'Placement - activity log';
        $data['activitydetails'] = $this->placement_model->cityactivity_details();
        $this->load->view('layout/header', $data);

        $this->load->view('placement/activitylog_view', $data);
        $norecord_flag = 1;
        $shadeFlag = 0;
        $shadeClass = '';
        $statusIco = '';
        $statusText = '';
        $data['month']= 0;
        $content = $data['activitydetails']->result_array();
        $i = 0;
        foreach ($content as $row) {
            $data['name'] = $row['name'];
            $data['id'] = $row['id'];
            $i++;
            $norecord_flag = 0;

            if ($shadeFlag == 0) {
                $data['shadeClass'] = 'even';
                $shadeFlag = 1;
            } else if ($shadeFlag == 1) {
                $data['shadeClass'] = 'odd';
                $shadeFlag = 0;
            }
            $this->load->view('placement/activitylog_view1', $data);
            $activitymonthdetails = $this->placement_model->cityactivitymonth_details($data['id']);
            $data['activitymonthdetailsover'] = $this->placement_model->cityactivityovermonth_details($data['id']);
            $data['actcount'] = $activitymonthdetails->num_rows();
            foreach ($activitymonthdetails->result_array() as $row) {
                $temp = explode('-', $row['started_on']);
               // print_r($temp);
                $data['month'] = $temp[1];
                $data['days'] = (strtotime(date("Y-m-d")) - strtotime($row['started_on'])) / (60 * 60 * 24);
            }
            $this->load->view('placement/activitylog_view3', $data);
        }
        $this->load->view('placement/activitylog_view2', $data);


        $this->load->view('layout/footer');
    }

}
