<?php $this->load->view('layout/flatui/header', array('title' => $center->name . ' Allocation Report')); ?>
<style type="text/css">
td, th {
	padding:3px;
}
.nopad {
	padding: 0 !important;
}
</style>

<div class="container" id="content">
<h2 class="title"><?php echo $center->name ?> Allocation Report</h2>

<p>Class started on <strong><?php echo date("F dS, Y", strtotime($center->class_starts_on)) ?></strong>.</p>

<table width="100%" class="table">
<tr><th>Level</th><th>Students</th><th>Batch Info</th></tr>
<?php foreach($data as $level) { ?>
<tr><td><?php echo $level['level_name'] ?></td>

<td class="nopad"><table>
<?php foreach ($level['kids'] as $student_id => $name) { ?>
<tr><td><?php echo $name ?></td></tr>
<?php } ?>
</table></td>

<td valign='top' class="nopad"><table width='100%'><tr><th>Batches</th><th>Teachers</th></tr>

<?php foreach ($level['batch'] as $batch_id => $batch) { ?>
<tr><td width='50%'><?php echo $batch['name'] ?></td><td class="nopad"><table width="100%">

<?php foreach ($batch['teachers'] as $user_id) { ?>
<tr><td width="50%"><?php echo (isset($all_users[$user_id]) ? $all_users[$user_id]->name : 'None: ') ?></td>
<td><?php echo (isset($all_users[$user_id]) ? $all_subjects[$all_users[$user_id]->subject_id] : 'None') ?></td></tr>
<?php } ?>
</table></td></tr>
<?php } ?>

</table></td>
</tr>
<?php } ?>
</table>
</div>

<?php $this->load->view('layout/flatui/footer'); ?>
