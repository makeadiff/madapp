<?php
class Parameter extends Controller {
	var $sql_queries = array(
			'%total_vols_count%'	=> "SELECT COUNT(U.id) FROM User U 
					INNER JOIN UserGroup UG ON UG.user_id=U.id 
					INNER JOIN `Group` G ON UG.group_id=G.id
					WHERE U.user_type='volunteer'  AND U.city_id='%CITY_ID%' AND G.type='volunteer'",
			'%vols_who_left_this_cycle_count%' => "SELECT COUNT(U.id) FROM User U 
					INNER JOIN UserGroup UG ON UG.user_id=U.id 
					INNER JOIN `Group` G ON UG.group_id=G.id
					WHERE U.user_type='let_go' AND U.left_on > '%CYCLE_START_DATE%' AND U.left_on < '%CYCLE_END_DATE%'  AND U.city_id='%CITY_ID%'",
			'%total_ed_vols_count%' => "%total_vols_count% AND G.vertical_id=3",
			'%ed_vols_who_left_this_cycle_count%' => '%vols_who_left_this_cycle_count% AND G.vertical_id=3',

			'%event_vols_who_left_this_cycle_count%' => '%total_vols_count% AND G.vertical_id=10',
			'%total_event_vols_count%' => "%total_vols_count% AND G.vertical_id=10",

			'%cr_vols_who_left_this_cycle_count%' => '%total_vols_count% AND G.vertical_id=13',
			'%total_cr_vols_count%' => "%total_vols_count% AND G.vertical_id=13",

			'%cfr_vols_who_left_this_cycle_count%' => '%total_vols_count% AND G.vertical_id=12',
			'%total_cfr_vols_count%' => "%total_vols_count% AND G.vertical_id=12",


			);

	function Parameter() {
		parent::Controller();
		$this->load->model('Users_model','user_model');
		$this->load->model('city_model');
		$this->load->model('Review_Parameter_model','review_model');
		
		$this->load->helper('url');
		$this->load->helper('misc');

		// Replaces the query templates within the queries to its actual value.
		foreach($this->sql_queries as $qkey => $qquery) {
			foreach ($this->sql_queries as $tkey => $tquery) {
				$this->sql_queries[$tkey] = str_replace($qkey, $qquery, $tquery);
			}
		}
		
        $this->cycle = 1; // :TODO: Get current cycle - this is NOT valid
	}

	function calulate_review_for_user($user_id) {

	}

	function calculate_parameter($user_id, $parameter_id) {
		$parameter = $this->db->query("SELECT * FROM Review_Parameter WHERE id=$parameter_id")->row_array();

		$this->replace_values = array(
				'%CYCLE_START_DATE%'=> '2014-01-01',
				'%CYCLE_END_DATE%'	=> '2014-07-15',
				'%CITY_ID%' 		=> 1,
			);

		if($parameter['sql']) {
			$value = $this->get_query_value($sql, $this->replace_values);

		} elseif($parameter['formula']) {
			$keys = array_keys($this->sql_queries);
			foreach ($keys as $i => $value) {
				$keys[$i] = '/'.str_replace('%','\%', $value) .'/';
			}
			$formula = preg_replace_callback($keys, array($this, 'calculate_formula_value'), $parameter['formula']);
			$value = eval("return ($formula);");
		}


		$level = 0;

		if($parameter['start_compare'] == '1') {
			for($i=1; $i<5; $i++) {
				if(eval("return (( " . $value . $parameter['level_'.$i] . " ) ? true : false);")) {
					$level = $i;
					break;
				}
			}
		} elseif($parameter['start_compare'] == '5') {
			for($i=5; $i<0; $i++) {
				if(eval("return (( " . $value . $parameter['level_'.$i] . " ) ? true : false);")) {
					$level = $i;
					break;
				}
			}
		}

		$flags = array('nothing', 'black','red','orange','yellow','green');

		$this->review_model->save(array(
				'review_parameter_id'	=> $parameter['id'],
				'value'			=> $value,
				'flag'			=> $flags[$level],
				'level'			=> $level,
				'comment'		=> "Calculation:\n" . $parameter['formula'] . "\n" . $formula,
				'input_type'	=> 'automated',
				'review_period'	=> 'cycle',
				'cycle'			=> $this->cycle,
				'updated_on'	=> date("Y-m-d H:i:s"),
				'user_id'		=> $user_id
			));
		print "Saved $parameter[name]: $value\n";
	}

	// Finds the keys that should be replaced in the formula and replace it with the query - then execute it and return the value.
	function calculate_formula_value($match)  {
		$value = $this->get_query_value($this->sql_queries[$match[0]], $this->replace_values);
		return $value;
	}

	// Replace all the small elements in query like '%CITY_ID%' and return the value.
	function get_query_value($sql, $replace_values) {
		$sql = str_replace(array_keys($replace_values), array_values($replace_values), $sql);
		$data = $this->db->query($sql)->result_array();

		// Convert data to single value.
		$value = $this->get_single_value($data);
		if(!$value) $value = $this->get_first_value($data);
		return $value;
	}

	function get_single_value($data) {
		if(count($data) == 1) {
			$new_data = $data[0];
			if(count($new_data) == 1) {
				$last_data = array_values($new_data);
				return $last_data[0];
			}
		}

		return false;
	}

	function get_first_value($data) {
		$new_data = $data[0];
		if(count($new_data)) {
			$last_data = array_values($new_data);
			return $last_data[0];
		}

		return false;
	}


}

