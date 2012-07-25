<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 * @package		madapp
 * @author		Rabeesh
 */
class Settings_model extends Model {

    function Settings_model() {
        // Call the Model constructor
        parent::Model();
        $this->ci = &get_instance();
    }

    /*
     * Function Name : getsettings()
     * Wroking :This function used for getting all settings.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [array]
     */

    function getsettings() {
        $settings = $this->db->orderby('name')->get('Setting')->result();
        return $settings;
    }

    /*
     * Function Name : addsetting()
     * Wroking :This function used for saving settings.
     * @author:Rabeesh
     * @param :[$data]
     * @return: type: [array]
     */

    function addsetting($data) {
        $success = $this->db->insert('Setting', array(
            'name' => $data['name'],
            'value' => $data['value'],
            'data' => $data['data'],
                ));
        return ($this->db->affected_rows() > 0 ) ? true : false;
    }

    /*
     * Function Name : editsetting()
     * Wroking :This function used for saving settings.
     * @author:Rabeesh
     * @param :[$data,$settings_id]
     * @return: type: [array]
     */

    function editsetting($data, $settings_id) {
        $this->db->where('id', $settings_id)->update('Setting', $data);
        return ($this->db->affected_rows() > 0 ) ? true : false;
    }

    function get_settings($setting_id) {
        return $this->db->where('id', $setting_id)->get('Setting')->row_array();
    }

    /*
     * Function Name : editsetting()
     * Wroking :This function used for deleting settings.
     * @author:Rabeesh
     * @param :[$id]
     * @return: type: [array]
     */

    function deletesetting($id) {
        $this->db->where('id', $id)->delete('Setting');
    }

    /*
     * Function Name : get_setting_value()
     * Wroking :This function used for settings value.
     * @author:Rabeesh
     * @param :[$name]
     * @return: type: [array]
     */

    function get_setting_value($name) {
        $setting = $this->db->where('name', $name)->get('Setting')->row();
        return ($setting->value) ? $setting->value : $setting->data;
    }

    /*
     * Function Name : set_setting_value()
     * Wroking :This function used for saving settings value.
     * @author:Rabeesh
     * @param :[$name]
     * @return: type: [array]
     */

    function set_setting_value($name, $value, $type='value') {
        $this->db->where('name', $name)->update('Setting', array($type => $value));
    }

}
