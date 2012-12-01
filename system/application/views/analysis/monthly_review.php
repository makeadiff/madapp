<?php
$this->load->view('layout/header', array('title'=>'Monthly Review'));

foreach($months as $month_year) if(isset($review[$month_year]['red_flag_count'])) $review[$month_year]['red_flag_count']->value = 0;
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/hide_sidebar.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/analysis.css">
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/classes/madsheet.js"></script>
<script type="text/javascript">
function comment(name, month_year) {
	var city_id = <?php echo $this->session->userdata('city_id'); ?>;
	jQuery.ajax({
				"url": "<?php echo site_url('analysis/monthly_review_get_comment'); ?>/" + month_year + '/' + name,
				"success": function(data) {
					jQuery("#comment").show();
					jQuery("#note").val(data);
					jQuery("#comment_month_year").val(month_year);
					jQuery("#comment_name").val(name);
				}
			});
	
}

function cancelComment() {
	jQuery("#comment").hide();
}

function saveComment() {
	var name = jQuery("#comment_name").val();
	var month_year = jQuery("#comment_month_year").val();
	jQuery.ajax({
			"url": "<?php echo site_url('analysis/monthly_review_set_comment'); ?>/" + month_year + '/' + name,
			"data": {"comment": jQuery("#note").val()},
			"type": "POST",
			"success": function(data) {
				jQuery("#comment").hide();
			}
		});
}

function inputData(name, value, month_year, ele, threshold, red_if) {
	var title = name.replace(/_/g," ");
	var input_value = Number(prompt(title, value));
	if(input_value == undefined) return;
	ele.innerHTML = input_value;
	
	var flag = "green";
	if(red_if == '<') {
		if(threshold < input_value) flag = "red";
	}
	else if(red_if == '>') {
		if(threshold > input_value) flag = "red";
	}
	
	if(flag == "red") ele.parentNode.className = "bad";
	else ele.parentNode.className = "good";
	
	jQuery.ajax({
				"url": "<?php echo site_url('analysis/save_review_data'); ?>/" + name + '/' + month_year + '/' + input_value + '/' + flag,
				"success": function(data) {
					//alert(data);
				}
			});
}
</script>
<style type="text/css">
#comment {
	position:absolute;
	top:200px;
	left:40%;
	width:300px;
	background:#ccc;
	border:1px solid black;
	display:none;
	padding:3px;
}
.vertical-name {
	font-weight:bold;
}
</style>

<div id="comment">
<form action="" method="post">
<textarea name="note" id="note" rows="5" cols="40"></textarea><br />
<input type="button" name="action" value="Cancel" onclick="cancelComment()" />
<input type="button" name="action" value="Save" onclick="saveComment()" style="float:right;" />
<input type="hidden" name="comment_month_year" id="comment_month_year" />
<input type="hidden" name="comment_name" id="comment_name" />
</form>
</div>

Number of Centers: <?php echo $center_count ?><br />
Number of Children: <?php echo $student_count ?><br />
Number of Teachers: <?php echo $teacher_count ?><br />
Number of Volunteers: <?php echo $volunteer_count ?><br />

<table class="data-table">
<tr>
<td></td><td></td>
<td>April</td><td>May</td><td>June</td><td>July</td><td>August</td><td>September</td><td>October</td><td>November</td><td>December</td><td>January</td><td>February</td><td>March</td>
<tr>
<!--
<tr>
<td></td><td class="name">Number of MAD Classes</td>
<?php showCells('class_count', $review, $months); ?>
</tr>

<tr>
<td></td><td class="name">Number of Fellows</td>
<?php showCells('fellows_count', $review, $months); ?>
</tr>
-->

<tr><td class="vertical-name" colspan="14">Operations</td></tr>

<tr><td></td><td class="name">Volunteers Absent without Substitute</td>
<?php showCells('absent_without_substitute_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Substitution Percentage</td>
<?php showCells('substitute_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Volunteers With Negative Credits</td>
<?php showCells('negative_credit_volunteer_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Volunteer Attendance Marked</td>
<?php showCells('madapp_volunteer_attendance_marked', $review, $months, false, true); ?>
</tr>

<tr><td></td><td class="name">Student Attendance Marked</td>
<?php showCells('madapp_student_attendance_marked', $review, $months, false, true); ?>
</tr>

<tr><td></td><td class="name">Class Progress Marked</td>
<?php showCells('madapp_class_progress_marked', $review, $months, false, true); ?>
</tr>

<tr><td></td><td class="name">Student Attendance</td>
<?php showCells('attended_kids_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Classes Cancelled</td>
<?php showCells('classes_cancelled_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Center Authority not visited in the last 2 months</td>
<?php showCells('center_authority_not_visited_2_months', $review, $months, true, false, 0, '>'); ?>
</tr>

<?php showEventAttendance(4, $attendance_matrix, $review, $months); ?>

<!-- ############################################################################### -->

<tr><td class="vertical-name" colspan="14">HR</td></tr>

<tr><td></td><td class="name">Number of Volunteers left to be recruited</td>
<?php showCells('volunteer_requirement_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Volunteers Remaining for Process Training</td>
<?php showCells('volunteers_missing_process_training', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Months Since CC</td>
<?php showCells('months_since_avm', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Attrition</td>
<?php showCells('attirition_percentage', $review, $months); ?>
</tr>

<?php showEventAttendance(5, $attendance_matrix, $review, $months); ?>

<!-- ############################################################### -->

<tr><td class="vertical-name" colspan="14">English Project Head</td></tr>

<!--
<tr><td></td><td class="name">Children who passed monthly assesment</td>
<?php showCells('periodic_assessment_updation_status', $review, $months); ?>
</tr>
-->

<tr><td></td><td class="name">Pass Percentage</td>
<?php showCells('pass_percentage', $review, $months, true, false, 70, '>'); ?>
</tr>


<tr><td></td><td class="name">Class Progress</td>
<?php showCells('class_progress_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Volunteers Remaining for Teacher Training I</td>
<?php showCells('volunteers_missing_teacher_training_1', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Volunteers Remaining for Teacher Training II</td>
<?php showCells('volunteers_missing_teacher_training_2', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Volunteers Remaining for Curriculum Training</td>
<?php showCells('volunteers_missing_curriculum_training', $review, $months); ?>
</tr>

<?php showEventAttendance(19, $attendance_matrix, $review, $months); ?>

<!-- ################################################################### -->
<tr><td class="vertical-name" colspan="14">Placements</td></tr>

<tr><td></td><td class="name">Number of child groups that did not go through an activity(monthly)</td>
<?php showCells('inactive_child_group_count', $review, $months, true, false, 0, '<'); ?>
</tr>

<tr><td></td><td class="name">Number of Kits contributed to repository</td>
<?php showCells('new_kit_count', $review, $months, true, false, 2, '>'); ?>
</tr>

<tr><td></td><td class="name">Number of interns with incomplete targets</td>
<?php showCells('inactive_intern_count', $review, $months, true, false, 0, '<'); ?>
</tr>

<tr><td></td><td class="name">Number of times kits sent late</td>
<?php showCells('late_kit_count', $review, $months, true, false, 0, '<'); ?>
</tr>

<tr><td></td><td class="name">Monthly Calendar Ready by 30th</td>
<?php showCells('monthly_calendar_status', $review, $months, true, true, 1, '>'); ?>
</tr>

<tr><td></td><td class="name">Percentage of Kids participating in the activities of the month</td>
<?php showCells('participating_kids_percentage', $review, $months, true, false); ?>
</tr>

<?php showEventAttendance(12, $attendance_matrix, $review, $months); ?>

<!-- ######################################################## -->

<tr><td class="vertical-name" colspan="14">PR</td></tr>

<tr><td></td><td class="name">Months Since Last Ping</td>
<?php showCells('months_since_ping', $review, $months, true, false, 1, '<'); ?>
</tr>

<tr><td></td><td class="name">PR Campaign</td>
<?php showCells('months_since_pr_initiative', $review, $months, true, true, 1, '<'); ?>
</tr>

<tr><td></td><td class="name">Strategic Tie-ups</td>
<?php showCells('strategic_tie_ups', $review, $months, true, true, 1, '>'); ?>
</tr>

<tr><td></td><td class="name">FB Plan Submission</td>
<?php showCells('fb_plan_submission', $review, $months, true, true, 1, '>'); ?>
</tr>

<tr><td></td><td class="name">Attendance in PR Concall</td>
<?php showCells('attendance_in_concall', $review, $months, true, true, 1, '>'); ?>
</tr>

<tr><td></td><td class="name">CC Attendance</td>
<?php showCells('cc_attendance_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Post on Blog</td>
<?php showCells('blog_post_count', $review, $months, true, true, 1, '>'); ?>
</tr>

<?php showEventAttendance(11, $attendance_matrix, $review, $months); ?>


<tr><td class="vertical-name" colspan="14">Finance</td></tr>

<tr><td></td><td class="name">Petty Cash Register Updated</td>
<?php showCells('accounts_updated_status', $review, $months, true, true, 1, '>'); ?>
</tr>

<tr><td></td><td class="name">Non-80G Donor Register Updated</td>
<?php showCells('non80g_donor_register_update', $review, $months, true, true, 1, '>'); ?>
</tr>

<tr><td></td><td class="name">80G Donor Register Updated</td>
<?php showCells('80g_donor_register_update', $review, $months, true, true, 1, '>'); ?>
</tr>

<tr><td></td><td class="name">Number of Donors Pending Receipt</td>
<?php showCells('pending_receipt_count', $review, $months, true, false, 0, '<'); ?>
</tr>

<?php showEventAttendance(15, $attendance_matrix, $review, $months); ?>


<tr><td class="vertical-name" colspan="14">President</td></tr>

<tr><td></td><td class="name">Fellows PIMPed</td>
<?php showCells('number_of_fellows_pimped', $review, $months, true, false, 2, "<"); ?>
</tr>

<tr><td></td><td class="name">Core Team Meeting Conducted</td>
<?php showCells('core_team_meeting_status', $review, $months); ?>
</tr>

<tr><td></td><td class="name">CC Attendance</td>
<?php showCells('cc_attendance_percentage', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Bills and Documents Submitted by the 5th</td>
<?php showCells('bills_documents_submitted', $review, $months, true, true, 1, '>'); ?>
</tr>

<tr><td></td><td class="name">Core Team Event Created</td>
<?php showCells('core_team_meeting_status', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Number of Red Flags</td>
<?php showCells('red_flag_count', $review, $months); ?>
</tr>

<?php showEventAttendance(2, $attendance_matrix, $review, $months); ?>

</table>


<?php
$this->load->view('layout/footer');

function showCells($name, $review, $months, $input=false, $yes_no=false, $threshold = 0, $red_if = '>') {
	foreach($months as $month_year) {
		if(isset($review[$month_year][$name])) {
			$r = $review[$month_year][$name];

			if($name == 'red_flag_count') {
				if($r->value >= 4) $r->flag = 'red';
			}

			if($r->flag == 'red' or $r->value == -1) {
				echo "<td class='bad'>";
				$review[$month_year]['red_flag_count']->value++;
			}
			elseif($r->flag == 'green') echo "<td class='good'>";
			else echo "<td class='none'>";
			
			$value = $r->value;
			if($value == -1) $value = 'No Data';
			elseif($yes_no) {
				$value = ($value) ? 'Yes' : 'No';
			}

			if($input and $review['user_auth']->get_permission('monthly_review_edit')) echo "<a onclick='inputData(\"$name\",\"{$r->value}\", \"$month_year\", this, $threshold, \"$red_if\");'>$value</a>";
			else echo $value;
			
			if(strpos($name, 'percentage')) echo '%';
			
			if($review['user_auth']->get_permission('monthly_review_comment')) echo "<a href='#' onclick='comment(\"$name\",\"$month_year\");' class='icon edit'>Note</a>";
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
