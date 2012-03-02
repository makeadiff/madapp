<?php $this->load->view('layout/header', array('title'=>$title)); ?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>

<form action="<?php echo site_url('exam/save_marks') ?>" method="post">
<table class="data-table">
<tr><td></td><?php foreach($subjects as $s) { ?><th><?php echo "{$s->name}({$s->total_mark})"; ?></td><?php } ?></tr>
<?php foreach($student_ids as $student_id) { ?>
<tr>
<td><?php echo $student_names[$student_id] ?></td>
<?php foreach($subjects as $s) { ?><td><input type="text" size="3" value="" id="subject_<?php echo $s->id ?>" name="mark[<?php echo $student_id; ?>][<?php echo $s->id ?>]" /></td><?php } ?>
</tr>
<?php } ?>
</table>
<?php 
echo form_submit('action', 'Save Marks', 'class="green button"');
echo form_hidden('exam_id', $exam_id);
echo form_hidden('center_id', $center_id);
echo form_hidden('exam_on', $exam_on);
?>
</form>

<?php $this->load->view('layout/footer');