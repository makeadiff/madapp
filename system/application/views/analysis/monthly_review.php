<?php
$this->load->view('layout/header', array('title'=>'Monthly Review'));
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/hide_sidebar.css">
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/analysis.css">
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/classes/madsheet.js"></script>

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
<?php showCells('madapp_updation_status', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Student Attendance</td>
<?php showCells('attended_kids_percentage', $review, $months); ?>
</tr>

<tr><td class="vertical-name" colspan="14">English Project Head</td></tr>

<tr><td></td><td class="name">Periodic Assessment Results in MADApp</td>
<?php showCells('periodic_assessment_updation_status', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Class Progress</td>
<?php showCells('class_progress', $review, $months); ?>
</tr>


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


<tr><td class="vertical-name" colspan="14">PR</td></tr>

<tr><td></td><td class="name">Months Since Last Ping</td>
<?php showCells('months_since_ping', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Blog Post Count</td>
<?php showCells('blog_post_count', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Months Since Last PR Initiative</td>
<?php showCells('months_since_pr_initiative', $review, $months); ?>
</tr>


<tr><td class="vertical-name" colspan="14">CR</td></tr>

<tr><td></td><td class="name">Monthly Target</td>
<?php showCells('monthly_target', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Money Raised</td>
<?php showCells('money_raised', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Donor Upate Sent</td>
<?php showCells('donor_update_sent', $review, $months); ?>
</tr>


<tr><td class="vertical-name" colspan="14">Finance</td></tr>

<tr><td></td><td class="name">Accounts Updated</td>
<?php showCells('accounts_updated_status', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Number of Donors Pending Receipt</td>
<?php showCells('pending_receipt_count', $review, $months); ?>
</tr>


<tr><td class="vertical-name" colspan="14">President</td></tr>

<tr><td></td><td class="name">Core Team Meeting Conducted</td>
<?php showCells('core_team_meeting_stauts', $review, $months); ?>
</tr>

<tr><td></td><td class="name">Number of Red Flags</td>
<?php showCells('red_flag_count', $review, $months); ?>
</tr>

</table>


<?php 
$this->load->view('layout/footer');

function showCells($name, $review, $months) {
	foreach($months as $month_year) {
		if(isset($review[$month_year][$name])) {
			$r = $review[$month_year][$name];
			if($r->flag == 'red') echo "<td class='bad'>";
			elseif($r->flag == 'green') echo "<td class='good'>";

			echo $r->value;
			if(strpos($name, 'percentage')) echo '%';
			echo "</td>";
			
		} else {
			echo "<td>&nbsp;</td>";
		}
	}
}