<?php $this->load->view('layout/header', array('title'=>'Edit Class', 'message'=>$message)); ?>

<div id="head" class="clear"><h1>Edit Class on <?php echo date('j<\s\u\p>S</\s\u\p> M Y, H:i A', strtotime($class_details['class_on'])) ?></h1></div>

<form action="<?php echo site_url('classes/edit_class_save') ?>" class="form-area" method="post">

<?php for($i=0; $i<count($class_details['teachers']); $i++) { ?>
<label for='user_id[<?php echo $i ?>]'>Teacher</label>
<span class="form-element-holder"><strong><?php echo $teachers[$class_details['teachers'][$i]['user_id']] ?></strong></span><br />
<?php echo form_hidden('user_id['.$i.']', $class_details['teachers'][$i]['user_id']); ?>

<label for='substitute_id[<?php echo $i ?>]'>Substitue</label>
<?php echo form_dropdown('substitute_id['.$i.']', $substitutes, $class_details['teachers'][$i]['substitute_id']); ?><br />

<label for='status[<?php echo $i ?>]'>Status</label>
<?php echo form_dropdown('status['.$i.']', $statuses, $class_details['teachers'][$i]['status']); ?><br />

<?php echo form_hidden('user_class_id['.$i.']', $class_details['teachers'][$i]['id']); ?>
<br />
<?php } ?>

<label for="lesson_id">Feedback</label>
<?php echo form_dropdown('lesson_id', $all_lessons, $class_details['lesson_id']); ?><br />
<br />

<?php 
echo form_hidden('class_id', $class_details['id']);
echo form_hidden('project_id', 1);
echo '<label for="action">&nbsp;</label>' . form_submit('action', 'Edit');
?>
</form>

<?php $this->load->view('layout/footer'); ?>
