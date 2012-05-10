<?php
/** 
 * CodeIgniter
 * An open source application development framework for PHP 4.3.2 or newer
 *
 * @package         MaddApp
 * @author          Rabeesh
 * @since           Version 1.0
 * @filesource
 */
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
	 /**
    *
    * Function to index
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	public function index() {
		$this->user_auth->check_permission('report_index');
		$this->load->view('national_reports/index');
	}
	 /**
    *
    * Function to footprint_table_of_all_cities
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
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
	 /**
    *
    * Function to classes_table_of_all_cities
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
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
				//Total Let go Volunteers.
				$data['letgovolunteers']=$this->national_model->class_volunteers_in_letgo($city_id);
				
				
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
	 /**
    *
    * Function to classes_progress_table_of_all_cities
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
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
				//Number of Madd Levels-P.
				$data['number_of_Levels_P']=$this->national_model->class_number_of_level_p($city_id);
				//Number of Madd Levels-S.
				$data['number_of_Levels_S']=$this->national_model->class_number_of_level_s($city_id);
				//Number of Madd Levels-L1.
				$data['number_of_Levels_L1']=$this->national_model->class_number_of_level_l1($city_id);
			
				$this->load->view('national_reports/city_class_progress', array('data'=>$data, 'fields'=>$header_names, 'title'=>$title));
				}
			$this->load->view('national_reports/city_foorprint_footer', array( 'fields'=>$header_names, 'title'=>$title));
	}
	 /**
    *
    * Function to events_table_of_all_cities
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function events_table_of_all_cities()
	{
		$city_report_data = $this->national_model->get_city_details();
		$header_names=array(
				'city'		             => 'City', 
				'tot_no_volunteers'	     => 'Total No of Volunteers',
				'no_process_training'    => 'No of Process trainings',
				'total_process_training' => 'Total Process Training attendance',
				'no_of_cts'              => 'No of CTs',
				'tot_ct_att'             => 'Total CT attendance',
				'no_of_tts'			     => 'No of TTs',
				'tot_tt_att'	         => 'Total TT attendance',
				'tot_no_ccts'    	     =>'Total No of CCTs',
				'avg_att_ccts'    	     =>'Avg Attendance at CCTs',
				'tot_focused_content'    =>'Total No of Focused Content Workshops',
				'tot_att_fcw'            =>'Total Attendance at FCW',
				'avg_att_fcw'            =>'AVG Attendance at FCW',
				
			);
			$title='City Footprint';
			$this->load->view('national_reports/city_foorprint_header', array( 'fields'=>$header_names, 'title'=>$title));
			foreach($city_report_data as $row)
				{
				$data['city_name']=$row->name;
				$city_id=$row->id;
				//Total Voluteers.
				$data['totalvolunteers']=$this->national_model->class_volunteers_count($city_id);
				//Total Number Of CCTs.
				$data['cct_count']=$this->national_model->class_cct_count($city_id);
				//Total Number Of Teachers count.
				$data['tt_count']=$this->national_model->class_tt_count($city_id);
				// Total User events for TTs Attendance.
				$tt_user_events=$this->national_model->tt_user_events($city_id);
				$data['tt_user_events']=($tt_user_events * 80)/100;
				//Present Total TTs Attendance.
				$data['tt_count_Attendance']=$this->national_model->class_tt_attendance($city_id);
				//Present Total TTs Attendance.
				$data['no_process_training']=$this->national_model->no_process_training($city_id);
				
				// Total User events for Process Training Attendance
				$process_training_user_events=$this->national_model->process_training_user_events($city_id);
				$data['process_training_user_events']=($process_training_user_events * 66)/100;
				// Process Training  Attendance.
				$data['process_training_Attendance']=$this->national_model->process_training_Attendance($city_id);
				
				$this->load->view('national_reports/city_events', array('data'=>$data, 'fields'=>$header_names, 'title'=>$title));
				}
			$this->load->view('national_reports/city_foorprint_footer', array( 'fields'=>$header_names, 'title'=>$title));
	}
	 /**
    *
    * Function to exam_table_of_all_cities
    * @author : Rabeesh
    * @param  : []
    * @return : type : []
    *
    **/
	function exam_table_of_all_cities()
	{
		$city_report_data = $this->national_model->get_city_details();
		$header_names=array(
				'city'		            => 'City', 
				'p_last_test'	        => 'P - Last Test Completed',
				's_last_test'		    => 'S - Last Test Completed',
				'l1_last_test'		    => 'L1 - Last Test Completed',
				'l2_last_test'          => 'L2 - Last Test Completed',
				'l3_last_test'          => 'L3 - Last Test Completed',
			);
			$title='City Footprint';
			$this->load->view('national_reports/city_foorprint_header', array( 'fields'=>$header_names, 'title'=>$title));
			foreach($city_report_data as $row)
				{
				$data['city_name']=$row->name;
				$city_id=$row->id;
				//Total Voluteers.
				$data['totalvolunteers']=$this->national_model->class_volunteers_count($city_id);
			
				
				$this->load->view('national_reports/city_exams', array('data'=>$data, 'fields'=>$header_names, 'title'=>$title));
				}
			$this->load->view('national_reports/city_foorprint_footer', array( 'fields'=>$header_names, 'title'=>$title));
	}
	function starters_table_of_all_cities()
	{
		$city_report_data = $this->national_model->get_city_details();
		$header_names=array(
				'city'		                => 'City', 
				'tot_mad_level_p'	        => 'No of MAD Levels - P',
				'no_child_p'		        => 'No of Children - P',
				'avg_score_ass1'		    => 'AVG Score-Asst 1',
				'avg_score_ass2'            => 'AVG Score-Asst 2',
				'avg_score_ass3'            => 'AVG Score-Asst 3',
				'avg_score_ass4'            => 'AVG Score -Asst 4',
				'no_class_low_score_ass1'   => 'No of Classes with Low Score -Asst 1',
				'no_class_low_score_ass2'   => 'No of Classes with LowScore - Asst 2',
				'no_class_low_score_ass3'   => 'No of Classes with Low Score - Asst 3',
				'no_class_low_score_ass4'   => 'No of Classes with Low Score - Asst 4',
				'no_child_low_score_ass1'   => 'No of Children with low score - Asst 1',
				'no_child_low_score_ass2'   => 'No of Children with low score - Asst 2',
				'no_child_low_score_ass3'   => 'No of Children with low score - Asst 3',
				'no_child_low_score_ass4'   => 'No of Children with low score - Asst 4',
			);
			$title='City Footprint';
			$this->load->view('national_reports/city_foorprint_header', array( 'fields'=>$header_names, 'title'=>$title));
			foreach($city_report_data as $row)
				{
				$data['city_name']=$row->name;
				$city_id=$row->id;
				//Number of Madd Levels-P.
				$data['number_of_Levels_P']=$this->national_model->class_number_of_level_p($city_id);
				//Getting Children count_P
			    $data['childPcount'] = $this->national_model->get_children_P($city_id);
				
				$this->load->view('national_reports/city_starters', array('data'=>$data, 'fields'=>$header_names, 'title'=>$title));
				}
			$this->load->view('national_reports/city_foorprint_footer', array( 'fields'=>$header_names, 'title'=>$title));
	}
}
?>