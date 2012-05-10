<?php $this->load->view('layout/header', array('title'=>'Reports')); ?>

<div id="head" class="clear"><h1>Report</h1></div>
<br />

<a href="<?php echo site_url('report/users_with_low_credits') ?>">Show volunteers with low credits</a><br />
<a href="<?php echo site_url('report/absent') ?>">Show volunteers who were absent without a substitute</a><br />
<a href="<?php echo site_url('report/volunteer_requirement') ?>">Show volunteer requirement in each center</a><br />
<a href="<?php echo site_url('report/get_volunteer_admin_credits') ?>">Admin Credits</a><br />
<a href="<?php echo site_url('analysis/class_progress_report') ?>">Class Progress Report</a><br />
<a href="<?php echo site_url('analysis/kids_attendance') ?>">Attendance Of The Kids</a><br />
<a href="<?php echo site_url('analysis/event_attendance') ?>">Attendance For Volunteer Events</a><br />
<a href="<?php echo site_url('analysis/exam_report') ?>">Exam Reports</a><br />
<a href="<?php echo site_url('analysis/exam_report_test') ?>">Exam Reports Test</a><br />

<?php $this->load->view('layout/footer'); ?>