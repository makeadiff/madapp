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
	
		$now = new DateTime("now");
		$today_morning = new DateTime("today 08:00");
		$today_night = new DateTime("today 22:30");
		
		if($now >= $today_night || $now <=$today_morning)
			exit();
		
		$query = $this->db->select('req_id')->from('Message_Queue')->distinct()->get();
		
		foreach($query->result() as $q_row){
			
			//Check if the request exist
			$query1 = $this->db->from('request')->where('req_id',$q_row->req_id)->get();
			if($query1->num_rows() == 0){
			
				$this->db->where('req_id',$q_row->req_id)->delete('Message_Queue');
			}
			else{
			
				$request = $query1->row();
				//Check if the first volunteer has replied
				if($request->int_vol_1 != -1)
					$this->db->where('req_id',$q_row->req_id)->where('send',0)->delete('Message_Queue');
			}
		}
		
		$query2 = $this->db->from('Message_Queue')->get();
		
		foreach($query2->result() as $q_row){
				
			$now = new DateTime("now");
			$msg_time = new DateTime("$q_row->msg_time");
			
			if($now >= $msg_time && $q_row->send == 0){
			
					
				if($this->debug == true){
					echo "Message to $q_row->phone: $q_row->msg<br>";
				}
				else{
					$this->sms->send($q_row->phone,$q_row->msg);
				}
				
				$data = array(
							'send' => 1
						);
					
				$this->db->where('id',$q_row->id)->update('Message_Queue', $data); 
				
				
			}
			
		}
	}
	
}
	
?>