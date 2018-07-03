<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Comment_model extends Model 
{
	function Comment_model()
	{
		parent::Model();
		$this->ci = &get_instance();
		$this->city_id = $this->ci->session->userdata('city_id');
		$this->project_id = $this->ci->session->userdata('project_id');
		$this->year = $this->ci->session->userdata('year');
	}

	public function get_all($item, $item_id)
	{
		$comments = $this->db->query("SELECT C.id,C.comment,C.added_on,U.name AS user,U.id AS user_id,I.name AS item 
							FROM Comment C 
							INNER JOIN User U ON U.id=C.added_by_user_id
							INNER JOIN $item I ON I.id=C.item_id 
							WHERE C.item_id=$item_id");

		return $comments->result();
	}

}
