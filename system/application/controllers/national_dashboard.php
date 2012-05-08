<?php
class National_dashboard extends Controller {
	private $message;
	
	function National_dashboard() {
		parent::Controller();
		$this-> message = array('success'=>false, 'error'=>false);
	
		$this->load->model('National_model', 'national_model');
		
		$this->load->library('session');
        $this->load->library('user_auth');
		$logged_user_id = $this->session->userdata('id');
		if($logged_user_id == NULL ) {
			redirect('auth/login');
		}
	}
	
	public function index() {
		$this->user_auth->check_permission('report_index');
		$this->load->view('national_reports/index');
	}
	public function footprint_table_of_all_cities()
	{
		$city_report_data = $this->national_model->get_city_details();
		$header_names=array(
				'city'		    => 'City', 
				'center_name'	=> 'No of centers',
				'class_p'		=> 'No of MAD Classes - P',
				'class_s'		=> 'No of MAD Levels - S',
				'class_l1'		=> 'No of MAD Levels - L1',
				'class_l2'		=> 'No of MAD Levels - L2',
				'class_l3'	    => 'No of MAD Levels - L3',
				'total_level'	=> 'Total No of MAD Levels',
				'children_p'    =>'No of Children - P',
				'children_s'    =>'No of Children - S',
				'children_l1'   =>'No of Children -L1',
				'children_l2'   =>'No of Children - L2',
				'children_l3'   =>'No of Children - L3',
				'total_children'=>'Total No of Children',
				
				'volunteers_p'    =>'No of Volunteers -P',
				'volunteers_s'    =>'No of Volunteers -S',
				'volunteers_l1'   =>'No of Volunteers -L1',
				'volunteers_l2'   =>'No of Volunteers - L2',
				'volunteers_l3'   =>'No of Volunteers - L3',
				'total_volunteers'=>'Total No of Volunteers'
			);
			$title='City Footprint';
			$this->load->view('national_reports/city_foorprint_header', array( 'fields'=>$header_names, 'title'=>$title));
		foreach($city_report_data as $row)
		{
			$data['city_name']=$row->name;
			$city_id=$row->id;
			//Getting Class counts
			$data['center_count'] = $this->national_model->get_center_count($city_id);
			$data['classPcount'] = $this->national_model->get_classes_p($city_id);
			$data['classScount'] = $this->national_model->get_classes_s($city_id);
			$data['classL1count'] = $this->national_model->get_classes_L1($city_id);
			$data['classL2count'] = $this->national_model->get_classes_L2($city_id);
			$data['classL3count'] = $this->national_model->get_classes_L3($city_id);
			$data['totalClass']=$data['classPcount']+$data['classScount']+$data['classL1count']+$data['classL2count']+$data['classL3count'];
			//Getting Children count
			$data['childPcount'] = $this->national_model->get_children_P($city_id);
			$data['childScount'] = $this->national_model->get_children_S($city_id);
			$data['childL1count'] = $this->national_model->get_children_L1($city_id);
			$data['childL2count'] = $this->national_model->get_children_L1($city_id);
			$data['childL3count'] = $this->national_model->get_children_L1($city_id);
			$data['totalchild']=$data['childPcount']+$data['childScount']+$data['childL1count']+$data['childL2count']+$data['childL3count'];
			//Getting Volunteers  count
			
			$data['volunteersPcount'] = $this->national_model->get_Volunteers_P($city_id);
			$data['volunteersScount'] = $this->national_model->get_Volunteers_S($city_id);
			$data['volunteersL1count'] = $this->national_model->get_Volunteers_L1($city_id);
			$data['volunteersL2count'] = $this->national_model->get_Volunteers_L2($city_id);
			$data['volunteersL3count'] = $this->national_model->get_Volunteers_L3($city_id);
			$data['totalvolunteers']=$data['volunteersPcount']+$data['volunteersScount']+$data['volunteersL1count']+$data['volunteersL2count']+$data['volunteersL3count'];
			$this->load->view('national_reports/city_footprint', array('data'=>$data, 'fields'=>$header_names, 'title'=>$title));
			
			
		}
		$this->load->view('national_reports/city_foorprint_footer', array( 'fields'=>$header_names, 'title'=>$title));
	}
	function classes_table_of_all_cities()
	{
		$city_report_data = $this->national_model->get_city_details();
		$header_names=array(
				'city'		         => 'City', 
				'center_name'	     => 'Total No of Children',
				'avg_att'		     => 'City Avg Attendance',
				'tot_mad_levels'	 => 'Total No of MAD Levels',
				'low_mad_level'		 => 'No of MAD Levels with low average attendance',
				'no_volunteers'		 => 'Total No of Volunteers in the City',
				'volunteers_negative'=> 'Volunteers in Negative Credits',
				'total_Volunteersin' => 'Volunteersin  Let Go list',
				'total_mad_class'    =>'Total Number of MAD Classes',
				'no_substitute'      =>'No of Substitutes',
				'no_missed'          =>'No of Classes missed without Sub',
				'no_cancelled'       =>'No of Classes Cancelled',
				'low_child_att'      =>'No of Classes with low child attendance',
				
			);
			$title='City Footprint';
			$this->load->view('national_reports/city_foorprint_header', array( 'fields'=>$header_names, 'title'=>$title));
			foreach($city_report_data as $row)
				{
				$data['city_name']=$row->name;
				$city_id=$row->id;
				//Getting Children count.
				$data['totalchild']=$this->national_model->class_children_count($city_id);
				//Total Madd Level.
				$data['maddlevels']=$this->national_model->class_level_count($city_id);
				//Total Volunteers.
				$data['totalvolunteers']=$this->national_model->class_volunteers_count($city_id);
				
				
				//Total Madd attendance.
				$data['city_avg_attendance']=$this->national_model->class_avg_attendance($city_id);
				//Total Madd Classes.
				$data['totalmaddclasses']=$this->national_model->class_class_count($city_id);
				//Total Substitute Count.
				$data['class_substitute_count']=$this->national_model->class_substitute_count($city_id);
				//finding 33% of Total Madd Classes.
				$data['substitute_madd_percentage']=($data['totalmaddclasses'] * 33)/100;
				//Total Missed Classes without substitute.
				$data['class_missed_count']=$this->national_model->class_missed_count($city_id);
				//finding 10% of Total Madd Classes.
				$data['missed_madd_percentage']=($data['totalmaddclasses'] * 10)/100;
				//Total Cancelled Classes.
				$data['class_cancelled_count']=$this->national_model->class_cancelled_count($city_id);
				//finding 25% of Total Madd Classes.
				$data['cancelled_madd_percentage']=($data['totalmaddclasses'] * 25)/100;
				
				$this->load->view('national_reports/city_classes', array('data'=>$data, 'fields'=>$header_names, 'title'=>$title));
				}
			$this->load->view('national_reports/city_foorprint_footer', array( 'fields'=>$header_names, 'title'=>$title));
	}
	function classes_progress_table_of_all_cities()
	{
		$city_report_data = $this->national_model->get_city_details();
		$header_names=array(
				'city'		            => 'City', 
				'tot_mad_level'	        => 'Total No of MAD Levels',
				'no_slow'		        => 'No of Slow Levels',
				'no_fast'		        => 'No of Fast Levels',
				'median_unit'           => 'Median Unit Covered - P',
				'least_unit'            => 'Least Unit Covered - P',
				'most_unit'			    => 'Most Unit Covered - P',
				'no_mad_levels_p'	    => 'No of MAD Levels - P',
				'no_slow_classes_p'    	=>'No of Slow Classes - P',
				'no_fats_classes_p'    	=>'No of Fast Classes - P',
				'median_unit_covered_s' =>'Median Unit Covered - S',
				'least_unit_covered_s'  =>'Least Unit Covered - S',
				'most_unit_covered_s'   =>'Most Unit Covered - S',
				'no_madd_levels_s'   	=>'No of MAD Levels - S',
				'no_slow_classes_s'   	=>'No of Slow Classes - S',
				'no_fast_classes_s'   	=>'No of Fast Classes - S',
				'median_unit_covered_L1'=>'Median Unit Covered - L1',
				'least_unit_covered_L1' =>'Least Unit Covered - L1',
				'most_unit_covered_L1'  =>'Most Unit Covered - L1',
				'no_madd_levels_L1'   	=>'No of MAD Levels - L1',
				'no_slow_classes_L1'    =>'No of Slow Classes - L1',
				'no_fast_classes_L1'    =>'No of Fast Classes - L1',
				
			);
			$title='City Footprint';
			$this->load->view('national_reports/city_foorprint_header', array( 'fields'=>$header_names, 'title'=>$title));
			foreach($city_report_data as $row)
				{
				$data['city_name']=$row->name;
				$city_id=$row->id;
				//Total Madd Level.
				$data['maddlevels']=$this->national_model->class_level_count($city_id);
			
				$this->load->view('national_reports/city_class_progress', array('data'=>$data, 'fields'=>$header_names, 'title'=>$title));
				}
			$this->load->view('national_reports/city_foorprint_footer', array( 'fields'=>$header_names, 'title'=>$title));
	}
}
?>