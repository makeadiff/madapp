<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

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
class Users_model extends Model {

    function Users_model() {
        parent::Model();
        $this->ci = &get_instance();
        $this->city_id = $this->ci->session->userdata('city_id');
        $this->project_id = $this->ci->session->userdata('project_id');
        $this->year = $this->ci->session->userdata('year');
    }

    /*
     * Function Name : login()
     * Wroking :This function used for login purpose.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [array]
     */

    function login($data) {
        $username = $data['username'];
        $password = $data['password'];

        $query = $this->db->where('email', $username)->where('password', $password)->where('status', '1')->where('user_type', 'volunteer')->get("User");
        if ($query->num_rows() > 0) {
            $user = $query->first_row();
            $memberCredentials['id'] = $user->id;
            $memberCredentials['email'] = $user->email;
            $memberCredentials['name'] = $user->name;
            $memberCredentials['project_id'] = $user->project_id;
            $memberCredentials['city_id'] = $user->city_id;
            $memberCredentials['permissions'] = $this->get_user_permissions($user->id);
            $memberCredentials['groups'] = $this->get_user_groups($user->id);

            return $memberCredentials;
        } else {
            return false;
        }
    }

    /*
     * Function Name : group_count()
     * Wroking :
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */

    function group_count() {
        
    }

    /*
     * Function Name : getgroup_details()
     * Wroking :This function used for return all group details.
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */

    function getgroup_details() {
        $this->db->select('*');
        $this->db->from('Group');
        $result = $this->db->get();
        return $result;
    }

    /*
     * Function Name : get_all_groups()
     * Wroking :This function used for return all group details.
     * @author:Rabeesh
     * @param :[]
     * @return: type: [array]
     */

    function get_all_groups() {
        return $this->db->from('Group')->get()->result();
    }

    /*
     * Function Name : add_group_name()
     * Wroking :This function used for save all group details.
     * @author:Rabeesh
     * @param :[$groupname]
     * @return: type: [boolean]
     */

    function add_group_name($groupname) {
        $data = array('name' => $groupname);
        $this->db->insert('Group', $data);
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
    }

    /*
     * Function Name : add_group_permission()
     * Wroking :This function used for save all group permission details.
     * @author:Rabeesh
     * @param :[$permission,$group_id]
     * @return: type: [boolean]
     */

    function add_group_permission($permission, $group_id) {
        $count = sizeof($permission);
        for ($j = 0; $j < $count; $j++) {
            $data = array('group_id' => $group_id, 'permission_id' => $permission[$j]);
            $this->db->set($data);
            $this->db->insert('GroupPermission');
        }
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : edit_group()
     * Wroking :This function used for return  group  details of geiven user.
     * @author:Rabeesh
     * @param :[$user_id]
     * @return: type: [boolean]
     */

    function edit_group($user_id) {
        $this->db->select('*');
        $this->db->from('Group');
        $this->db->where('id', $user_id);
        $result = $this->db->get();
        return $result;
    }

    /*
     * Function Name : update_group()
     * Wroking :This function used for update group.
     * @author:Rabeesh
     * @param :[$user_id]
     * @return: type: [boolean]
     */

    function update_group($group_id, $group_name) {
        $data = array('name' => $group_name);
        $this->db->where('id', $group_id);
        $this->db->update('Group', $data);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : update_permission()
     * Wroking :This function used for update group permission.
     * @author:Rabeesh
     * @param :[$user_id]
     * @return: type: [boolean]
     */

    function update_permission($group_id, $permission) {
        $this->db->where('group_id', $group_id);
        $this->db->delete('GroupPermission');

        $count = count($permission);
        for ($j = 0; $j < $count; $j++) {
            $data = array('group_id' => $group_id, 'permission_id' => $permission[$j]);
            $this->db->set($data);
            $this->db->insert('GroupPermission');
        }
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : delete_group()
     * Wroking :This function used for delete group.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */

    function delete_group($data) {
        $id = $data['entry_id'];
        $this->db->where('id', $id);
        $this->db->delete('Group');

        $this->db->where('group_id', $id);
        $this->db->delete('GroupPermission');

        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : get_user_groups_of_user()
     * Wroking :Returns the groups the current user belongs to...
     * @author:Rabeesh
     * @param :[$user_id, $data='name']
     * @return: type: [boolean]
     */

    function get_user_groups_of_user($user_id, $data='name') {
        $groups = $this->db->query("SELECT Group.$data AS data FROM `Group` INNER JOIN UserGroup ON Group.id=UserGroup.group_id WHERE UserGroup.user_id=$user_id")->result();
        $all_groups = array();
        foreach ($groups as $g)
            $all_groups[] = $g->data;

        return $all_groups;
    }

    /**
     * Function to users_count
     * @author:Rabeesh 
     * @param :[$data]
     * @return: type: [ Array()]
     * */
    function users_count() {
        
    }

    /*
     * Function Name : getuser_details()
     * Wroking :Returns the groups the current user belongs to...
     * @author:Rabeesh
     * @param :[$where=array()]
     * @return: type: [boolean]
     */

    function getuser_details($where=array()) {
        $this->db->select('User.*,City.name as city_name');
        $this->db->from('User');
        $this->db->where('User.project_id', $this->project_id)->where('User.status', '1');
        if (!empty($where['city_id']))
            $this->db->where('User.city_id', $where['city_id']);
        else
            $this->db->where('User.city_id', $this->city_id);

        $this->db->join('City', 'City.id = User.city_id', 'join');
        $this->db->orderby('User.name');

        $result = $this->db->get();

        return $result;
    }

    /*
     * Function Name : getuser_details_csv()
     * Wroking :Returns the user details as csv file.
     * @author:Rabeesh
     * @param :[]
     * @return: type: [boolean]
     */

    function getuser_details_csv() {
        $this->db->select('User.id,User.name,User.email,User.phone,User.credit,User.title,User.user_type,Center.name as center_name, City.name as city_name');
        $this->db->from('User');
        $this->db->join('Center', 'Center.id = User.center_id', 'join');
        $this->db->join('City', 'City.id = User.city_id', 'join');
        $this->db->where('User.project_id', $this->project_id)->where('User.status', '1');
        $result = $this->db->get();
        return $result;
    }

    /*
     * Function Name : adduser()
     * Wroking :Save user details.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */

    function adduser($data) {
        $email = $data['email'];
        $this->db->select('email');
        $this->db->from('User');
        $this->db->where('email', $email);
        $result = $this->db->get();
        if ($result->num_rows() > 0) {
            return false;
        } else {
            $user_array = array(
                'name' => $data['name'],
                'email' => $data['email'],
                'phone' => $this->_correct_phone_number($data['phone']),
                'password' => $data['password'],
                'address' => $data['address'],
                'sex' => $data['sex'],
                'city_id' => $data['city'],
                'project_id' => $data['project'],
                'user_type' => $data['type']
            );
            if (!empty($data['joined_on']))
                $user_array['joined_on'] = $data['joined_on'];
            else
                $user_array['joined_on'] = date('Y-m-d H:i:s');

            if (!empty($data['left_on']))
                $user_array['left_on'] = $data['left_on'];

            $this->db->insert('User', $user_array);
            return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
        }
    }

    /*
     * Function Name : adduser()
     * Wroking :This function redo the deletion.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [boolean]
     */

    function undelete($user_id) {
        return $this->db->where('id', $user_id)->update('User', array('status' => 1));
    }

    /*
     * Function Name : process_pic()
     * Wroking :
     * @author:Rabeesh
     * @param :[$data, $type='users']
     * @return: type: [boolean]
     */

    function process_pic($data, $type='users') {
        $id = $data['id'];
        //Get File Data Info
        $uploads = array($this->upload->data());
        $this->load->library('image_lib');
        $this->load->library('imageResize');

        //Move Files To User Folder
        foreach ($uploads as $key[] => $value) {
            $newimagename = $id . $value['file_ext'];
            $image_path = "uploads/$type/$newimagename";
            rename($value['full_path'], $image_path);

            $nwidth = '100';
            $nheight = '90';
            $fileSavePath = dirname(BASEPATH) . "/uploads/$type/thumbnails/$newimagename";
            imagejpeg(imageResize::Resize($image_path, $nwidth, $nheight), $fileSavePath);
            $imagename = $newimagename;
            $this->db->set('photo', $imagename);
            $this->db->where('id', $id);
            if ($type == 'users')
                $this->db->update('User');
            else
                $this->db->update('Student');

            return ($this->db->affected_rows() > 0) ? true : false;
        }
    }

    /*
     * Function Name : check_email_availability()
     * Wroking :Check user email availability.
     * @author:Rabeesh
     * @param :[$insert]
     * @return: type: [boolean]
     */

    function check_email_availability($insert) {
        $email = $insert['email'];
        $this->db->select('email');
        $this->db->from('User');
        $this->db->where('email', $email);
        $result = $this->db->get();
        if ($result->num_rows() > 0)
            return true;
        return false;
    }

    /*
     * Function Name : adduser_to_group()
     * Wroking :Add the user given as the first argument to all the groups specified in the second argument.
     * @author:
     * @param :[$user_id, $group_ids]
     * @return: type: [boolean]
     */

    function adduser_to_group($user_id, $group_ids) {
        foreach ($group_ids as $group_id) {
            $user_array = array('user_id' => $user_id, 'group_id' => $group_id);
            $this->db->insert('UserGroup', $user_array);
        }
        return ($this->db->affected_rows() > 0) ? $this->db->insert_id() : false;
    }

    /*
     * Function Name : remove_user_from_group()
     * Wroking :Removes the given user from the given group.
     * @author:
     * @param :[$user_id, $group_id]
     * @return: type: [boolean]
     */

    function remove_user_from_group($user_id, $group_id) {
        $this->db->delete('UserGroup', array('user_id' => $user_id, 'group_id' => $group_id));
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : user_details()
     * Wroking :Returns all the user details.
     * @author:
     * @param :[$user_id]
     * @return: type: [boolean]
     */

    function user_details($user_id) {
        $this->db->from('User');
        $this->db->where('User.id', $user_id); //->where('User.status','1');

        $result = $this->db->get()->row();
        $result->groups = $this->get_user_groups_of_user($user_id, 'id');
        $result->groups_name = $this->get_user_groups_of_user($user_id, 'name');
        $result->batch = $this->db->query("SELECT Batch.day, Batch.class_time, Center.name 
					FROM Batch INNER JOIN UserBatch ON UserBatch.batch_id=Batch.id 
					INNER JOIN Center ON Batch.center_id=Center.id WHERE UserBatch.user_id={$user_id}")->row();

        return $result;
    }

    /*
     * Function Name : updateuser()
     * Wroking :This function update user details.
     * @author:
     * @param :[$data]
     * @return: type: [boolean]
     */

    function updateuser($data) {
        $user_id = $data['rootId'];
        $user_array = array(
            'name' => $data['name'],
            'email' => $data['email'],
            'phone' => $this->_correct_phone_number($data['phone']),
            'address' => $data['address'],
            'sex' => $data['sex'],
        );
        if (!empty($data['city']))
            $user_array['city_id'] = $data['city'];
        if (!empty($data['project']))
            $user_array['project_id'] = $data['project'];
        if (!empty($data['type'])) {
            $user_array['user_type'] = $data['type'];
            if ($user_array['user_type'] == 'let_go') { // Remove user from his classes when he is let go.
                $this->db->delete('UserBatch', array('user_id' => $user_id));
                $this->db->delete('UserClass', array('user_id' => $user_id, 'status' => 'projected'));
                $this->db->delete('UserClass', array('user_id' => $user_id, 'status' => 'confirmed'));
            }
        }
        if (!empty($data['joined_on']))
            $user_array['joined_on'] = $data['joined_on'];
        if (!empty($data['left_on']))
            $user_array['left_on'] = $data['left_on'];
        if (isset($data['password']))
            $user_array['password'] = $data['password'];

        $this->db->where('id', $user_id);
        $this->db->update('User', $user_array);
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : updateuser_to_group()
     * Wroking :This function update user to group .
     * @author:
     * @param :[$data]
     * @return: type: [boolean]
     */

    function updateuser_to_group($data) {
        $rootId = $data['rootId'];
        $this->db->where('user_id', $rootId);
        $this->db->delete('UserGroup');
        $group = $data['group'];
        for ($i = 0; $i < sizeof($group); $i++) {
            $data['group'] = $group[$i];
            $user_array = array('group_id' => $data['group'], 'user_id' => $rootId);
            $this->db->insert('UserGroup', $user_array);
        }
        return ($this->db->affected_rows() > 0) ? true : false;
    }

    /*
     * Function Name : delete()
     * Wroking :This function delete user.
     * @author:
     * @param :[$user_id]
     * @return: type: [boolean]
     */

    function delete($user_id) {
        $this->db->where('id', $user_id)->update('User', array('status' => '0'));
        $affected = $this->db->affected_rows();

        if ($affected) {
            $this->db->delete('UserBatch', array('user_id' => $user_id));
            return true;
        }
        return false;
    }

    /*
     * Function Name : get_user()
     * Wroking :This function return user details of a given user.
     * @author:
     * @param :[$user_id]
     * @return: type: [boolean]
     */

    function get_user($user_id) {
        return $this->db->where('id', $user_id)->get('User')->row();
    }

    /*
     * Function Name : getUsersById()
     * Wroking :This function return user details of a given user.
     * @author:
     * @param :[$user_id]
     * @return: type: [boolean]
     */

    function getUsersById() {
        $this->load->helper('misc');
        return getById("SELECT id, name FROM User WHERE city_id={$this->city_id} AND project_id={$this->project_id} AND user_type='volunteer' AND status='1'", $this->db);
    }
/*
     * Function Name : get_users_in_city()
     * Wroking :This function return user details in a city.
     * @author:
     * @param :[$city_id=false]
     * @return: type: [boolean]
     */
    function get_users_in_city($city_id=false) {
        if ($city_id === false)
            $city_id = $this->city_id;
        return $this->db->where('city_id', $city_id)->where('project_id', $this->project_id)->where('user_type', 'volunteer')->where('status', '1')->orderby('name')->get('User')->result();
    }
/*
     * Function Name : set_user_batch_and_level()
     * Wroking :This function save user batch and level.
     * @author:
     * @param :[$user_id, $batch_id, $level_id]
     * @return: type: [boolean]
     */
    function set_user_batch_and_level($user_id, $batch_id, $level_id) {
        $this->db->insert("UserBatch", array('user_id' => $user_id, 'batch_id' => $batch_id, 'level_id' => $level_id));
    }
/*
     * Function Name : unset_user_batch_and_level()
     * Wroking :This function delete user batch and level.
     * @author:
     * @param :[$user_id, $batch_id, $level_id]
     * @return: type: [boolean]
     */
    function unset_user_batch_and_level($batch_id, $level_id) {
        $this->db->delete("UserBatch", array('batch_id' => $batch_id, 'level_id' => $level_id));
    }
/*
     * Function Name : update_credit()
     * Wroking :This function update user credit.
     * @author:
     * @param :[$user_id, $change]
     * @return: type: [boolean]
     */
    function update_credit($user_id, $change) {
        if ($change == 1)
            $change = '+1';
        if ($change == 2)
            $change = '+2';
        $this->db->query("UPDATE User SET credit=credit $change WHERE id=$user_id");
    }
/*
     * Function Name : set_credit()
     * Wroking :
     * @author:
     * @param :[$user_id, $credit]
     * @return: type: [boolean]
     */
    function set_credit($user_id, $credit) {
        $this->db->query("UPDATE User SET credit=$credit WHERE id=$user_id");
    }
/*
     * Function Name : recalculate_user_credit()
     * Wroking :
     * @author:
     * @param :[$user_id, $update_if_wrong=false, $debug=false]
     * @return: type: [array]
     */
    function recalculate_user_credit($user_id, $update_if_wrong=false, $debug=false) {
        $this->ci->load->model('level_model');
        $this->ci->load->model('event_model');
        $credit = 3;
        $classes_so_far = $this->get_usercredits($user_id);

        foreach ($classes_so_far as $row) {
            if ($row['user_id'] == $user_id and $row['substitute_id'] == 0 and $row['status'] == 'absent') {
                $credit = $credit - 2;
            } else if ($row['user_id'] == $user_id and $row['substitute_id'] != 0 and ($row['status'] == 'absent' or $row['status'] == 'attended')) {
                $credit = $credit - 1;
            } else if ($row['substitute_id'] == $user_id and $row['status'] == 'absent') {
                $credit = $credit - 2;
            } elseif ($row['substitute_id'] == $user_id and $row['status'] == 'attended') {
                $credit_sub_gets = 1;
                // If the sub is from the same level, give him/her 2 credits. Because we are SO generous.
                $substitute_levels = $this->ci->level_model->get_user_level($row['substitute_id']);
                $current_class_level = $this->ci->level_model->get_class_level($row['class_id']);
                if (in_array($current_class_level, $substitute_levels)) {
                    $credit_sub_gets = 2;
                }
                $credit = $credit + $credit_sub_gets;
            }
        }

        $event_attendence = $this->ci->event_model->get_missing_user_attendance_for_event_type($user_id, 'avm');
        foreach ($event_attendence as $event) {
            $credit = $credit - 1;
        }

        if ($update_if_wrong) {
            $user = $this->get_user($user_id);

            $existing_credits = $user->credit;
            if ($debug)
                print "\t\t\t\tActual Credit: $credit\t\tExisting: $existing_credits";
            if ($existing_credits != $credit) {
                if ($debug)
                    print "\t\tWRONG!";
                $this->set_credit($user_id, $credit);
            }
        }

        return $credit;
    }
/*
     * Function Name : get_users_batch()
     * Wroking :
     * @author:
     * @param :[$user_id]
     * @return: type: [integer]
     */
    function get_users_batch($user_id) {
        $users_batch = $this->db->query("SELECT batch_id FROM UserBatch WHERE user_id=$user_id")->row();
        if ($users_batch)
            return $users_batch->batch_id;
        else
            return 0;
    }
/*
     * Function Name : search_users()
     * Wroking :
     * @author:
     * @param :[$data]
     * @return: type: [array]
     */
    function search_users($data) {
        $this->db->select('User.id,User.name,User.photo,User.email,User.password,User.phone,User.credit,
							User.joined_on,User.user_type,User.address,User.sex,User.source,User.birthday,
							User.job_status,User.preferred_day,User.why_mad, City.name as city_name');
        $this->db->from('User');
        $this->db->join('City', 'City.id = User.city_id', 'left');


        if (!isset($data['status']))
            $data['status'] = 1;
        if ($data['status'] !== false)
            $this->db->where('User.status', $data['status']); // Setting status as 'false' gets you even the deleted users

        if (!empty($data['project_id']))
            $this->db->where('User.project_id', $data['project_id']);
        elseif ($this->project_id)
            $this->db->where('User.project_id', $this->project_id);

        if (isset($data['city_id']) and $data['city_id'] != 0)
            $this->db->where('User.city_id', $data['city_id']);
        else if (!isset($data['city_id']))
            $this->db->where('User.city_id', $this->city_id);

        if (!empty($data['user_type']))
            $this->db->where('user_type', $data['user_type']);
        if (!empty($data['not_user_type']))
            $this->db->where_not_in('user_type', $data['not_user_type']);
        if (!empty($data['name']))
            $this->db->like('User.name', $data['name']);
        if (!empty($data['phone']))
            $this->db->where('User.phone', $data['phone']);
        if (!empty($data['email']))
            $this->db->where('User.email', $data['email']);
        if (!empty($data['left_on']))
            $this->db->where('DATE_FORMAT(User.left_on, "%Y-%m") = ', date('Y-m', strtotime($data['left_on'])));

        if (!empty($data['user_group'])) {
            $this->db->join('UserGroup', 'User.id = UserGroup.user_id', 'join');
            $this->db->where_in('UserGroup.group_id', $data['user_group']);
        }
        if (!empty($data['center'])) {
            $this->db->join('UserClass', 'User.id = UserClass.user_id', 'join');
            $this->db->join('Class', 'Class.id = UserClass.class_id', 'join');
            $this->db->join('Level', 'Class.level_id = Level.id', 'join');
            $this->db->where_in('Level.center_id', $data['center']);
        }


        if (!empty($data['user_type']) and $data['user_type'] == 'applicant') {
            $this->db->orderby('User.joined_on DESC');
        }
        $this->db->orderby('User.name');

        $all_users = $this->db->get()->result();
        //echo $this->db->last_query();

        $return = array();
        foreach ($all_users as $user) {
            // Get the batches for this User. An user can have two batches. That's why I don't do join to get this date.
            //$user->batches = colFormat($this->db->where('user_id',$user->id)->get('UserBatch')->result_array()); // :SLOW:
            // Gets the UserGroup of the users...
            if (!empty($data['get_user_groups']))
                $user->groups = $this->get_user_groups_of_user($user->id);
            if (!empty($data['get_user_class']))
                $user->batch = $this->db->query("SELECT Batch.day, Batch.class_time, Center.name 
					FROM Batch INNER JOIN UserBatch ON UserBatch.batch_id=Batch.id 
					INNER JOIN Center ON Batch.center_id=Center.id 
					WHERE UserBatch.user_id={$user->id} AND Batch.year={$this->year}")->row();

            $return[$user->id] = $user;
        }
        return $return;
    }

/////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  
    /*
     * Function Name : get_user_permissions()
     * Wroking :Returns all the permissions for the given user as an array.
     * @author:
     * @param :[$user_id]
     * @return: type: [boolean]
     */
    function get_user_permissions($user_id) {
        $permissions = $this->db->query("SELECT DISTINCT(Permission.name) FROM Permission 
			INNER JOIN GroupPermission ON GroupPermission.permission_id=Permission.id  
			INNER JOIN UserGroup ON GroupPermission.group_id=UserGroup.group_id 
			WHERE UserGroup.user_id=$user_id")->result();

        if (!count($permissions)) { // If he has no group, he is volunteer group.
            $default_group = 9; //:HARD-CODE: 9 is the teacher group.
            $permissions = $this->db->query("SELECT DISTINCT(Permission.name) FROM Permission 
				INNER JOIN GroupPermission ON GroupPermission.permission_id=Permission.id  
				WHERE GroupPermission.group_id=$default_group")->result();
        }

        $all_permissions = array();
        foreach ($permissions as $permission) {
            $all_permissions[] = $permission->name;
        }

        return $all_permissions;
    }


     /*
     * Function Name : get_user_groups()
     * Wroking :Returns all the groups for the given user as an associative array with group id as the key.
     * @author:
     * @param :[$user_id]
     * @return: type: [boolean]
     */
    function get_user_groups($user_id) {
        $groups = $this->db->query("SELECT `Group`.id,`Group`.name FROM `Group`
			INNER JOIN `UserGroup` ON `Group`.id=`UserGroup`.group_id 
			WHERE `UserGroup`.user_id=$user_id")->result();

        $all_groups = array();
        foreach ($groups as $group) {
            $all_groups[$group->id] = $group->name;
        }

        return $all_groups;
    }
 /*
     * Function Name : user_registration()
     * Wroking :
     * @author:
     * @param :[$data]
     * @return: type: [boolean]
     */
    function user_registration($data) {
        if (!empty($data['user_id'])) {
            $user_type = $this->db->query("SELECT user_type FROM User WHERE id=$data[user_id]")->row();
            if ($user_type->user_type != 'applicant') {
                $this->session->set_flashdata('error', 'Only Applicants can use this form.');
                return;
            }
            $userdetailsArray = array('name' => $data['name'],
                'email' => $data['email'],
                'phone' => $this->_correct_phone_number($data['phone']),
                'address' => $data['address'],
                'sex' => $data['sex'],
                'city_id' => $data['city_id'],
                'job_status' => $data['job_status'],
                'birthday' => date('Y-m-d', strtotime($data['birthday'])),
                'why_mad' => $data['why_mad'],
                'preferred_day' => $data['preferred_day'],
                'source' => $data['source'],
            );
            $this->db->where('id', $data['user_id'])->update('User', $userdetailsArray);
            $userdetailsArray['id'] = $data['user_id'];
            return $userdetailsArray;
        }

        $email = $data['email'];
        $debug = "";

        // Make sure there is no duplication of emails - or phone...
        $result = $this->db->query("SELECT id,email,phone FROM User WHERE email='$email' OR phone='{$data['phone']}'")->result();

        $debug .= print_r($result, 1);
        if (!$result) {
            $userdetailsArray = array('name' => $data['name'],
                'email' => $data['email'],
                'phone' => $this->_correct_phone_number($data['phone']),
                'address' => $data['address'],
                'sex' => $data['sex'],
                'city_id' => $data['city_id'],
                'job_status' => $data['job_status'],
                'birthday' => date('Y-m-d', strtotime($data['birthday'])),
                'why_mad' => $data['why_mad'],
                'preferred_day' => $data['preferred_day'],
                'source' => $data['source'],
                'user_type' => 'applicant',
                'status' => '1',
                'password' => 'pass',
                'joined_on' => date('Y-m-d H:i:s'),
                'project_id' => 1
            );
            $this->db->insert('User', $userdetailsArray);
            $debug .= $this->db->last_query();

            $userdetailsArray['id'] = $this->db->insert_id();

            $debug .= print_r($userdetailsArray, 1);
            $this->db->where('name', 'registeration_debug_info')->update('Setting', array('data' => $debug));

            return $userdetailsArray;
        } else {
            foreach ($result as $r) {
                if ($r->email == $data['email'])
                    $this->session->set_flashdata('error', 'Email already in database. Use another email address.');
                else if ($r->phone == $data['phone'])
                    $this->session->set_flashdata('error', 'Phone number already in database. You have registered already.');
                break;
            }

            return false;
        }
    }

     /*
     * Function Name : get_password()
     * Wroking :Return the user password
     * @author:
     * @param :[$data]
     * @return: type: [boolean]
     */
    function get_password($data) {
        $email = $data['email'];
        return $this->db->where('email', $email)->get("User")->row();
    }
/*
     * Function Name : get_usercredits()
     * Wroking :Return the user credits
     * @author:
     * @param :[$current_user_id]
     * @return: type: [array]
     */
    function get_usercredits($current_user_id) {
        $this->db->select('UserClass.*,Class.class_on');
        $this->db->from('UserClass');
        $this->db->join('Class', 'Class.id=UserClass.class_id', 'join');
        $this->db->where('UserClass.user_id', $current_user_id);
        $this->db->or_where('UserClass.substitute_id', $current_user_id);
        $result = $this->db->get();

        if ($result)
            return $result->result_array();
        return array();
    }

   /*
     * Function Name : get_name_of_Substitute()
     * Wroking :Return the name of substitute.
     * @author:
     * @param :[$substitute_id]
     * @return: type: [array]
     */
    function get_name_of_Substitute($substitute_id) {
        $this->db->select('name');
        $this->db->from('User');
        $this->db->where('id', $substitute_id);
        $result = $this->db->get();
        return $result->row();
    }
/*
     * Function Name : get_name_of_Substitute()
     * Wroking :Changes the phone number format from +919746068565 to 9746068565. Remove the 91 at the starting.
     * @author:
     * @param :[$substitute_id]
     * @return: type: [array]
     */

    private function _correct_phone_number($phone) {
        if (strlen($phone) > 10) {
            return preg_replace('/^\+?91\D?/', '', $phone);
        }
        return $phone;
    }

}