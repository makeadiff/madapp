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
		$this->load->model('book_lesson_model');
		$this->load->model('center_model', 'center_model');
		$this->load->helper('url');
	}
	
	function index($holder='', $center_id = 0) {
		$this->user_auth->check_permission('level_index');
		
		if(!is_numeric($center_id) or !$center_id) {
			show_error("Choose a center.");
		}
		$all_levels = $this->model->db->where('center_id',$center_id)->where('project_id',1)->get('Level')->result();
		$center_name = $this->model->db->where('id',$center_id)->get('Center')->row();
		
		$this->load->view('level/index', array('all_levels'	=> $all_levels,'center_name'=>$center_name->name, 'center_id'=>$center_id));
	}
	
	function create($holder, $center_id = 0) { 
		$this->user_auth->check_permission('level_create');
		
		if($this->input->post('action') == 'New') {
			$this->model->create(array(
					'name'		=>	$this->input->post('name'),
					'center_id'	=>	$this->input->post('center_id'),
					'project_id'=>	$this->input->post('project_id'),
					'book_id'	=>	$this->input->post('book_id'),
					'students'	=>	$this->input->post('students')
				));
				
			$this->message['success'] = 'The Level has been added';
			$this->index('center', $this->input->post('center_id'));
		
		} else {
			$this->load->helper('misc');
			$this->load->helper('form');
			
			$center_name = $this->center_model->get_center_name($center_id);
			$kids = $this->kids_model->get_kids_name($center_id);
			$all_books = idNameFormat($this->book_lesson_model->get_all_books());
			
			$this->load->view('level/form.php', array(
				'action'	=> 'New',
				'center_id'	=> $center_id,
				'center_name'=>$center_name,
				'all_books'	=> $all_books,
				'level'	=> array(
					'id'		=> 0,
					'name'		=> '',
					'center_id'	=> $center_id,
					'kids'		=> $kids->result(),
					'book_id'	=> 1,
					'selected_students'=> array()
					)
				));
		}
	}
	
	function edit($level_id) {
		$this->user_auth->check_permission('level_edit');
		
		if($this->input->post('action') == 'Edit') {
			$this->model->edit($level_id, array(
				'name'		=>	$this->input->post('name'),
				'center_id'	=>	$this->input->post('center_id'),
				'project_id'=>	$this->input->post('project_id'),
				'book_id'	=>	$this->input->post('book_id'),
				'students'	=>	$this->input->post('students')
			));

			$this->session->set_flashdata('success', 'The Level has been edited successfully');
			redirect('level/index/center/' . $this->input->post('center_id'));
		} else {
		
			$this->load->helper('misc');
			$this->load->helper('form');
			
			$level = $this->db->where('id',$level_id)->get('Level')->row_array();
			$center_id = $level['center_id'];
			$center_name = $this->center_model->get_center_name($center_id);
			$kids = $this->kids_model->get_kids_name($center_id);
			$level['kids'] = $kids->result();
			
			$level['selected_students'] = array_keys($this->model->get_kids_in_level($level_id));
			
			$all_books = idNameFormat($this->book_lesson_model->get_all_books());

			$this->load->view('level/form.php', array(
				'action' 	=> 'Edit',
				'center_id'	=> $center_id,
				'center_name'=> $center_name,
				'level'		=> $level,
				'all_books'	=> $all_books 
				));
		}
	}
	
	function delete($level_id) {
		$this->user_auth->check_permission('level_delete');
		
		//Make sure the level don't have any batches under it.
		$batches = $this->db->where('level_id', $level_id)->get('UserBatch')->result();
		if($batches) {
			show_error("This level has batches under it. You can only delete levels that have no batches");
		}
		$level_center = $this->db->select('center_id')->where('id', $level_id)->get('Level')->row();
		
 		$this->db->delete('Level', array('id'=>$level_id));
 		$this->db->delete('StudentLevel', array('level_id'=>$level_id));
		$this->message['success'] = 'The Level has been deleted successfully';
		$this->index('center', $level_center->center_id);
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