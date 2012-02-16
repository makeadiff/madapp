<?php $this->load->view('layout/header', array('title'=>$title)); ?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>

<table class="data-table">
<tr><td>&nbsp;</td>
<?php foreach($exam_details->subjects as $s) { ?><th><?php echo "{$s->name}({$s->total_mark})"; ?></td><?php } ?></tr>
<?php foreach($marks as $student_id => $student_mark) { ?>
<tr>
<td><?php echo $students[$student_id] ?></td>
<?php 
ksort($student_mark); 
foreach($student_mark as $subject_id => $mark) { ?>
<td><?php echo $mark ?></td>
<?php } ?>
</tr>
<?php } ?>

</table>

<?php $this->load->view('layout/footer');