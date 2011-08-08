<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package		MadApp
 * @author		Rabeesh
 * @copyright	Copyright (c) 2008 - 2010, OrisysIndia, LLP.
 * @link		http://orisysindia.com
 * @since		Version 1.0
 * @filesource
 */
class Admincredit_model extends Model{
	
		/**
   		 * constructor 
    	**/
		function Admincredit_model()
		{
			parent::model();
			$this->ci = &get_instance();
			$this->city_id = $this->ci->session->userdata('city_id');
		}
		function get_credit()
		{
			$current_user_id=$this->ci->session->userdata('id');
			return $this->db->query("SELECT AdminCredit.*,Task.name FROM AdminCredit INNER JOIN Task ON AdminCredit.task_id=Task.id 
				WHERE user_id='$current_user_id' ORDER BY id DESC")->result();
		}
		
		function get_task()
		{
		
			return $this->db->query("SELECT * FROM Task ORDER BY id DESC")->result();
		
		}
		
		function get_users()
		{
			$city_id=$this->ci->session->userdata('city_id');
			$admin_user_group = 14;
			return $this->db->query("SELECT User.* FROM User INNER JOIN UserGroup ON UserGroup.user_id=User.id  WHERE UserGroup.group_id=$admin_user_group AND User.city_id='$city_id'")->result();
		
		}
		function update_admincredits($data)
		{
			$current_user_id=$this->ci->session->userdata('id');
			$dtm=date("Y-m-d H:i:s");
			$task_id=$data['task_id'];
			$user_id=$data['user'];
			if($this->input->post('reason'))
			{
				$reason=$this->input->post('reason');
				$credit=$this->input->post('credit');
				$event = $this->db->query("UPDATE User SET admin_credit='$credit' WHERE id='$user_id'");
				
				$this->db->insert("AdminCredit", array(
					'user_id'	=> $data['user'],
					'task_id'	=> $data['task_id'],
					'awarded_by'=> $current_user_id,
					'reason'	=> $reason,
					'added_on'	=> $dtm,
					'credit'	=> $credit,
					'vertical'	=> $this->input->post('type')
				));
			} 
			else 
			{
				$reason=''; 
				$event = $this->db->query("SELECT * FROM Task WHERE id='$task_id'")->row();
				$credit=$event->credit;
				$vertical=$event->vertical;
				$event = $this->db->query("UPDATE User SET admin_credit='$credit' WHERE id='$user_id'");
				
				$this->db->insert("AdminCredit", array(
					'user_id' 	=> $data['user'],
					'task_id' 	=> $data['task_id'],
					'awarded_by'=> $current_user_id,
					'reason' 	=> $reason,
					'added_on'	=> $dtm,
					'credit'	=> $credit,
					'vertical'	=> $vertical
				));
			}
			return ($this->db->affected_rows() > 0) ? true : false;
		}
		
}		