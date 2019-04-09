<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 * @package		madapp
 * @author		Rabeesh
 */
class Contact_model extends Model {
    function Contact_model() {
        // Call the Model constructor
        parent::Model();
        $this->ci = &get_instance();
    }

    public function add($data)
    {
        $exists = $this->db->where(['email' => $data['email']])->get("Contact")->first_row();
        if($exists) {
            return [false, "The given Email is already in our system."];
        }

        $contact = $this->db->insert('Contact', [
            'name' => isset($data['name']) ? $data['name'] : '',
            'email'=> $data['email'],
            'phone'=> isset($data['phone']) ? $data['phone'] : '',
            'city_id' => isset($data['city_id']) ? $data['city_id'] : '0',
            'birthday' => isset($data['birthday']) ? $data['birthday'] : '',
            'sex' => isset($data['sex']) ? $data['sex'] : 'f',
            'source' => isset($data['source']) ? $data['source'] : 'other',
            'address' => isset($data['address']) ? $data['address'] : '',
            'why_mad' => isset($data['why_mad']) ? $data['why_mad'] : '',
            'job_status' => isset($data['job_status']) ? $data['job_status'] : 'other',
            'is_applicant' => isset($data['is_applicant']) ? $data['is_applicant'] : '1',
            'is_subscribed' => isset($data['is_subscribed']) ? $data['is_subscribed'] : '0',
            'is_care_collective' => isset($data['is_care_collective']) ? $data['is_care_collective'] : '0',
            'added_on'  => date('Y-m-d H:i:s'),
            'updated_on'  => date('Y-m-d H:i:s'),
            'status'    => '1'
        ]);

        return [$contact, "Success"];
    }

    public function setZohoId($user_id, $zoho_user_id)
    {
        $this->db->where('id', $user_id)->update("Contact", ['zoho_user_id' => $zoho_user_id]);
    }

}
