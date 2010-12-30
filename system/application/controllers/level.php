<?php
class Level extends Controller {
	private $message;
	
	function Level() {
		parent::Controller();
		$this->message = array('success'=>false, 'error'=>false);
	
		$this->load->scaffolding('Level');
		$this->load->model('Level_model','model', TRUE);
		$this->load->helper('url');
	}
	
	function index($holder, $center_id = 0) {
		if(!is_numeric($center_id)) {
			show_error("Choose a center." . $center_id);
		}
		$all_levels = $this->model->db->where('center_id',$center_id)->get('Level')->result();
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
			$center_ids = getById("SELECT id, name FROM Center", $this->model->db);
			
			$this->load->view('level/form.php', array(
				'action' 	=> 'Edit',
				'center_ids'=> $center_ids,
				'level'		=> $level
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


}