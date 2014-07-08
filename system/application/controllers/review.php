<?php
class Review extends Controller {
	private $message;
	
	function Review() {
		parent::Controller();
		$this->load->model('Users_model','user_model');
		$this->load->model('Class_model','class_model');
		$this->load->model('Level_model','level_model');
		$this->load->model('Center_model','center_model');
		$this->load->model('city_model');
		$this->load->model('Batch_model','batch_model');
		
		$this->load->helper('url');
		$this->load->helper('misc');
		
		$this->load->library('session');
        $this->load->library('user_auth');
        
        $logged_user_id = $this->user_auth->logged_in();
		if(!$logged_user_id) {
			redirect('auth/login');
		}
        $this->user_details = $this->user_auth->getUser();
	}

	function select_people() {
		$this->user_auth->check_permission('review_select_people');
		$city_id = $this->session->userdata('city_id');

		$fellows = $this->user_model->get_fellows($city_id);
		dump($fellows);
	}
	
	function report($vertical='all') {
		$this->user_auth->check_permission('review_report');
		$data = array();
		$this->load->model('kids_model');
		$this->load->model('review_model');
		
		$all_cities = array();
		if($vertical != 'all') {
			$all_cities_data = $this->city_model->get_all();
			foreach($all_cities_data as $city_details) $all_cities[$city_details->id] = $city_details->name;
		} else {
			$all_cities = array($this->session->userdata('city_id') => '');
		}
		
		foreach($all_cities as $city_id => $city_name) {
			$core_team_groups = array(2,4,5,11,12,15,19);
			$vps = $this->users_model->search_users(array('city_id'=>$city_id,'user_group'=> $core_team_groups, 'user_type'=>'volunteer', 'get_user_groups'=>true)); //18(Library), 10(CR) and 20(FOM) Excluded
			$attendance_matrix = array();
			foreach($core_team_groups as $vertical_id) {
				$people = array();
				foreach($vps as $vp) {
					if(in_array($vertical_id, array_keys($vp->groups))) {
						$people[] = $vp;
					}
				}
				
				$attendance_matrix[$vertical_id] = $people;
			}
			
			$data[$city_id]['attendance_matrix'] = $attendance_matrix;
			
			$current_year = $this->session->userdata('year');
			$month_names = array($current_year.'-04', $current_year.'-05', $current_year.'-06', $current_year.'-07', $current_year.'-08', $current_year.'-09', $current_year.'-10', $current_year.'-11', $current_year.'-12', 
								($current_year+1).'-01', ($current_year+1).'-02', ($current_year+1).'-03', ); get_month_list();
			foreach($month_names as $year_month) {
					$data[$city_id]['review'][$year_month] = idNameFormat($this->review_model->get_monthly_review($year_month, $this->session->userdata('city_id')), array('name'));
			}
			$data[$city_id]['review']['user_auth'] = $this->user_auth;
		}
		
		
		$this->load->view('analysis/monthly_review', array('data' => $data, 'all_cities' => $all_cities, 'vertical' => $vertical, 'months'=>$month_names));
	}
	
	function save_review_data($name, $year_month, $value, $flag = 'green') {
		$this->load->model('review_model');
		
		$this->review_model->save($name, $value, $year_month, $flag, $this->session->userdata('city_id'));
		echo "Saved";
	}
	
	function monthly_review_get_comment($year_month, $name) {
		$this->load->model('review_model');
		
		$comment = $this->review_model->get_comment($this->session->userdata('city_id'), $year_month, $name);
		echo $comment;
	}
	
	function monthly_review_set_comment($year_month, $name) {
		$this->load->model('review_model');
		
		$comment = $this->input->post('comment');
		$this->review_model->set_comment($this->session->userdata('city_id'), $year_month, $name, $comment);
		echo "Done";
	}
	
}



