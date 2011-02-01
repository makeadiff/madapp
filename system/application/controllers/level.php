<?php
class Level extends Controller {
	private $message;
	
	function Level() {
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
	
		$this->load->scaffolding('Level');
		$this->load->model('Level_model','model', TRUE);
		$this->load->model('kids_model');
		$this->load->helper('url');
	}
	
	function index($holder='', $center_id = 0) {
		if(!is_numeric($center_id) or !$center_id) {
			show_error("Choose a center.");
		}
		$all_levels = $this->model->db->where('center_id',$center_id)->where('project_id',1)->get('Level')->result();
		$center_name = $this->model->db->where('id',$center_id)->get('Center')->row();
		
		$this->load->view('level/index', array('all_levels'	=> $all_levels,'center_name'=>$center_name->name, 'center_id'=>$center_id));
	}
	
	function create($holder, $center_id = 0) { 
		if($this->input->post('action') == 'New') {
			$this->db->insert('Level', 
				array(
					'name'		=>	$this->input->post('name'),
					'center_id'	=>	$this->input->post('center_id'),
					'project_id'=>	$this->input->post('project_id'),
				));

			$this->message['success'] = 'The Level has been added';
			$this->index('center', $this->input->post('center_id'));
		
		} else {
			$this->load->helper('misc');
			$this->load->helper('form');
			
			$center_ids = getById("SELECT id, name FROM Center", $this->model->db);
			$this->load->view('level/form.php', array(
				'action' => 'New',
				'center_ids' => $center_ids,
				'level'	=> array(
					'id'		=> 0,
					'name'		=>	'Level ',
					'center_id'	=> $center_id,
					)
				));
		}
	}
	
	function edit($level_id) {
		if($this->input->post('action') == 'Edit') {
			$this->db->where('id', $this->input->post('id'))->update('Level', 
				array(
					'name'		=>	$this->input->post('name'), 
					'center_id'	=>	$this->input->post('center_id'),
					'project_id'=>	$this->input->post('project_id'),
				));
			$this->message['success'] = 'The Level has been edited successfully';
			$this->index('center', $this->input->post('center_id'));
		} else {
			$this->load->helper('misc');
			$this->load->helper('form');
			$level = $this->db->where('id',$level_id)->get('Level')->row_array();
			$c_id=$level['center_id'];
			$data['kids']=$this->kids_model->get_kids_name($c_id);
			$kids=$data['kids']->result_array();
			$center_ids = getById("SELECT id, name FROM Center", $this->model->db);
			$this->load->view('level/form.php', array(
				'action' 	=> 'Edit',
				'center_ids'=> $center_ids,
				'level'		=> $level,
				'kids'	=>$kids
				));
		}
	}
	
	function delete($level_id) {
		//Make sure the level don't have any batches under it.
		$batches = $this->db->where('level_id', $level_id)->get('Batch')->result();
		if($batches) {
			show_error("This level has batches under it. You can only delete levels that have no batches");
		}
		// Check for kids in this batch too? Not sure.
				
		$this->db->delete('Level', array('id'=>$level_id));
		$this->message['success'] = 'The Level has been deleted successfully';
		$this->index();
	}
	
	/**
    * Function to update_student
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    **/
	function update_student()
	{
		$level = $_REQUEST['level'];
		$agents = $_REQUEST['agents'];
		$agents = str_replace("on,","",$agents);
		$agents = substr($agents,0,strlen($agents)-1);
			$explode_agents_array = explode(",",trim($agents));
			for($i=0;$i<sizeof($explode_agents_array);$i++)
			   {
					$agent_id = $explode_agents_array[$i];				
					$this->kids_model->kids_level_update($agent_id,$level);
			   }
	
	}


}