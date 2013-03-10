<?php

class CronMessage extends Controller {

		
	function CronMessage(){
	
		parent::Controller();
		
		//parent::__construct();
		
		$this->load->database();
		$this->load->library('sms');
		date_default_timezone_set('Asia/Calcutta');
		
		$this->debug = false;
	}
	
	
	function main(){
		
		$query = $this->db->select('req_id')->from('Message_Queue')->distinct()->get();
		
		foreach($query->result() as $q_row){
			
			$query1 = $this->db->from('request')->where('req_id',$q_row->req_id)->get();
			if($query1->num_rows() == 0){
			
				$this->db->where('req_id',$q_row->req_id)->delete('Message_Queue');
			}
			else{
			
				$request = $query1->row();
				if($request->int_vol_1 != -1)
					$this->db->where('req_id',$q_row->req_id)->delete('Message_Queue');
			}
		}
		
		$query2 = $this->db->from('Message_Queue')->get();
		
		foreach($query2->result() as $q_row){
				
			$now = new DateTime("now");
			$msg_time = new DateTime("$q_row->msg_time");
			
			if($now >= $msg_time){
			
				if($this->debug == true){
					echo "Message to $q_row->phone: $q_row->msg<br>";
				}
				else{
					$this->sms->send($q_row->phone,$q_row->msg);
				}
				
				$this->db->where('id',$q_row->id)->delete('Message_Queue');
				
			}
			
		}
	}
	
}
	
?>