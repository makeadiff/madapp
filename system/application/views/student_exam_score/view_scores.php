<?php $this->load->view('layout/header', array('title'=>$title)); ?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>

<h3><?php echo $exam_details->center ?></h3>
<h4><?php echo $exam_details->name ?></h4>
<h5><?php echo format($exam_details->level) ?></h5>

<table class="data-table">
<tr><td>&nbsp;</td>
<?php foreach($exam_details->subjects as $s) { ?><th><?php echo "{$s->name}({$s->total_mark})"; ?></td><?php } ?><th>Total</th></tr>
<?php foreach($marks as $student_id => $student_mark) { ?>
<tr>
<td><?php echo $students[$student_id] ?></td>
<?php 
ksort($student_mark); 
$total = 0;
foreach($student_mark as $subject_id => $mark) {
	$total += $mark;
?>
<td><?php echo $mark ?></td>
<?php } ?>
<td><?php echo $total ?></td>
</tr>
<?php } ?>

</table>

<?php $this->load->view('layout/footer');