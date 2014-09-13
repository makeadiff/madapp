<?php $this->load->view('layout/header', array('title'=>'National Reports')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/hide_sidebar.css">

<div id="head" class="clear"><h1>National Reports</h1></div>

<ul class="tabs">
<li <?php if($controller == 'footprint_table_of_all_cities') echo 'class="tab-selected"'; ?>><a href="<?php echo site_url('national_dashboard/footprint_table_of_all_cities') ?>">Footprint</a></li>
<li <?php if($controller == 'classes_table_of_all_cities') echo 'class="tab-selected"'; ?>><a href="<?php echo site_url('national_dashboard/classes_table_of_all_cities') ?>">Classes</a></li>
<li <?php if($controller == 'classes_progress_table_of_all_cities') echo 'class="tab-selected"'; ?>><a href="<?php echo site_url('national_dashboard/classes_progress_table_of_all_cities') ?>">Class Progress</a></li>
<li <?php if($controller == 'events_table_of_all_cities') echo 'class="tab-selected"'; ?>><a href="<?php echo site_url('national_dashboard/events_table_of_all_cities') ?>">Events</a></li>
<li <?php if($controller == 'exam_table_of_all_cities') echo 'class="tab-selected"'; ?>><a href="<?php echo site_url('national_dashboard/exam_table_of_all_cities') ?>">Exams</a></li>
<li <?php if($controller == 'starters_table_of_all_cities') echo 'class="tab-selected"'; ?>><a href="<?php echo site_url('national_dashboard/starters_table_of_all_cities') ?>">Starters</a></li>
</ul>
<br />

<table id="main" class="data-table">
<tr><?php foreach($fields as $field_name=>$field_title) { ?>
<th><?php echo $field_title ?></th>
<?php } ?></tr>
