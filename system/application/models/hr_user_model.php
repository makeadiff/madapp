<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class HR_user_model extends Model {
    function HR_user_model() {
        // Call the Model constructor
        parent::Model();
        $this->ci = &get_instance();
    }

    function set_count($user_id, $field, $count=0) {
        $this->db->query("DELETE FROM HR_User WHERE user_id=$user_id AND $field IS NOT NULL");
        $this->db->insert("HR_User", array($field => $count, "user_id"=>$user_id));
    }

    function get_people_status($user_ids, $field) {
        if(!$user_ids) return array();
        $users = $this->db->select("user_id,$field")->from("HR_User")->where_in('user_id', $user_ids)->get()->result();
        $data = idNameFormat($users, array('user_id', $field));

        $return_data = array();
        foreach ($user_ids as $user_id) {
            if(!isset($data[$user_id])) {
                $this->set_count($user_id, $field, 0);
                $return_data[$user_id] = 0;
            } else {
                $return_data[$user_id] = $data[$user_id];
            }
        }
        return $return_data;
    }
}
