<?php
$this->load->view('layout/header', array('title'=>'Monthly Review'));
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/hide_sidebar.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/analysis.css">
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/classes/madsheet.js"></script>
<script type="text/javascript">
function inputData(name, value, month_year, ele) {
	var title = name.replace(/_/g," ");
	var input_value = prompt(title, value);
	if(input_value == undefined) return;
	ele.innerHTML = input_value;
	jQuery.ajax({
				"url": "<?php echo site_url('analysis/save_review_data'); ?>/" + name + '/' + month_year + '/' + input_value,
				"success": function(data) {
					alert(data);
				}
			});
}
</script>

Number of Centers: <?php echo $center_count ?><br />
Number of Children: <?php echo $student_count ?><br />
Number of Volunteers: <?php echo $teacher_count ?><br />

<table class="data-table">
<tr>
<td></td><td></td>
<td>April</td><td>May</td><td>June</td><td>July</td><td>August</td><td>September</td><td>October</td><td>November</td><td>December</td><td>January</td><td>February</td><td>March</td>
<tr>

<tr>
<td></td><td class="name">Number of MAD Classes</td>
<?php showCells('class_count', $review, $months); ?>
</tr>


<tr><td class="vertical-name" colspan="14">Operations</td></tr>

<tr><td></td><td class="name">Volunteers Absent without Substitute</td>
<?php showCells('absent_without_substitute_count', $review, $months); ?>
</tr>

<tr><td></td><td class="name"></td>
<?php showCells('absent_without_substitute_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">MADApp Up To Date</td>
<?php showCells('madapp_updated_ops', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Student Attendance</td>
<?php showCells('attended_kids_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Center Authorities Visited</td>
<?php showCells('center_authorities_visited', $review, $months, true); ?>
</tr>

<?php showEventAttendance(4, $attendance_matrix, $review, $months); ?>

<tr><td class="vertical-name" colspan="14">English Project Head</td></tr>

<tr><td></td><td class="name">Periodic Assessment Results in MADApp</td>
<?php showCells('periodic_assessment_updation_status', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Class Progress</td>
<?php showCells('class_progress', $review, $months); ?>
</tr>

<?php showEventAttendance(4, $attendance_matrix, $review, $months); ?>


<tr><td class="vertical-name" colspan="14">HR</td></tr>

<tr><td></td><td class="name">Volunteer Requirement</td>
<?php showCells('volunteer_requirement_count', $review, $months); ?>
</tr>

<tr><td></td><td class="name"></td>
<?php showCells('volunteer_requirement_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Attrition</td>
<?php showCells('attirition_count', $review, $months); ?>
</tr>

<tr><td></td><td class="name"></td>
<?php showCells('attirition_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Months Since AVM</td>
<?php showCells('months_since_avm', $review, $months); ?>
</tr>

<tr><td></td><td class="name">MADApp Up To Date</td>
<?php showCells('madapp_updated_hr', $review, $months, true); ?>

<tr><td></td><td class="name">Exit Interviews Conducted</td>
<?php showCells('exit_interviews_conducted', $review, $months, true); ?>

<?php showEventAttendance(5, $attendance_matrix, $review, $months); ?>


<tr><td class="vertical-name" colspan="14">PR</td></tr>

<tr><td></td><td class="name">Months Since Last Ping</td>
<?php showCells('months_since_ping', $review, $months, true); ?>
</tr>

<tr><td></td><td class="name">Blog Post Count</td>
<?php showCells('blog_post_count', $review, $months, true); ?>
</tr>

<tr><td></td><td class="name">Months Since Last PR Initiative</td>
<?php showCells('months_since_pr_initiative', $review, $months, true); ?>
</tr>

<tr><td></td><td class="name">Activity on City FB Page</td>
<?php showCells('activity_on_city_page', $review, $months, true); ?>
</tr>

<tr><td></td><td class="name">FB Plan Submission</td>
<?php showCells('fb_plan_submission', $review, $months, true); ?>
</tr>

<tr><td></td><td class="name">Attendance in PR Concall</td>
<?php showCells('attendance_in_concall', $review, $months, true); ?>
</tr>

<?php showEventAttendance(11, $attendance_matrix, $review, $months); ?>


<tr><td class="vertical-name" colspan="14">Finance</td></tr>

<tr><td></td><td class="name">Accounts Updated</td>
<?php showCells('accounts_updated_status', $review, $months, true); ?>
</tr>

<tr><td></td><td class="name">Number of Donors Pending Receipt</td>
<?php showCells('pending_receipt_count', $review, $months, true); ?>
</tr>

<?php showEventAttendance(15, $attendance_matrix, $review, $months); ?>


<tr><td class="vertical-name" colspan="14">President</td></tr>

<tr><td></td><td class="name">Core Team Meeting Conducted</td>
<?php showCells('core_team_meeting_stauts', $review, $months); ?>
</tr>

<tr><td></td><td class="name">MADApp Up To Date</td>
<?php showCells('madapp_updated_president', $review, $months, true); ?>

<tr><td></td><td class="name">Number of fellows PIMPed</td>
<?php showCells('number_of_fellows_pimped', $review, $months, true); ?>
</tr>

<tr><td></td><td class="name">Number of Red Flags</td>
<?php showCells('red_flag_count', $review, $months); ?>
</tr>

<?php showEventAttendance(2, $attendance_matrix, $review, $months); ?>

</table>


<?php
$this->load->view('layout/footer');

function showCells($name, $review, $months, $input=false, $yes_no=false) {
	foreach($months as $month_year) {
		if(isset($review[$month_year][$name])) {
			$r = $review[$month_year][$name];
			if($r->flag == 'red') echo "<td class='bad'>";
			elseif($r->flag == 'green') echo "<td class='good'>";
			else echo "<td class='none'>";
			
			$value = $r->value;
			if($value == -1) $value = 'No Data';
			elseif($yes_no) {
				$value = ($value) ? 'Yes' : 'No';
			}

			if($input) echo "<a onclick='inputData(\"$name\",\"{$r->value}\", \"$month_year\", this);'>$value</a>";
			else echo $value;
			
			if(strpos($name, 'percentage')) echo '%';
			echo "</td>";
			
		} else {
			echo "<td>&nbsp;</td>";
		}
	}
}

function showEventAttendance($vertical, $attendance_matrix, $review, $months) {
	$people = $attendance_matrix[$vertical];
	$events = array('review_meeting' => "Monthly Review Meeting", 'avm' => "City Circle Time", 'core_team_meeting'=> "Core Team Meeting");
	
	foreach($people as $p)
	foreach($events as $event_name => $name) {
		$person_responsible  = '';
		if(count($people) > 1) $person_responsible = "({$p->name})";
		print "<tr><td></td><td class='name'>Attended $name $person_responsible</td>";
		showCells('core_team_'.$event_name.'_attendance_'.$vertical.'_'.$p->id, $review, $months, false, true);
		print "</tr>";
	}
	
}
