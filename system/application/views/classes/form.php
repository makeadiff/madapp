<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript">
		 $(document).ready(function(){
    	 $('#other_city0').change(function(){
		 if($(this).val() == -1){
		 var flag=0
         	$.ajax({
            type: "POST",
           	url: "<?= site_url('classes/other_city_teachers')?>"+'/'+flag,
           	 success: function(msg){
           		 $('#sidebar').html(msg);
           		 }
            	});
		 }
    });
	$('#other_city1').change(function(){
		 if($(this).val() == -1){
		  var flag=1
         	$.ajax({
            type: "POST",
           	url: "<?= site_url('classes/other_city_teachers')?>"+'/'+flag,
           	 success: function(msg){
           		 $('#sidebar').html(msg);
           		 }
            	});
		 }
    });
	$('#other_city2').change(function(){
		 if($(this).val() == -1){
		  var flag=2
         	$.ajax({
            type: "POST",
           	url: "<?= site_url('classes/other_city_teachers')?>"+'/'+flag,
           	 success: function(msg){
           		 $('#sidebar').html(msg);
           		 }
            	});
		 }
    });
});  
</script>

<?php $this->load->view('layout/header', array('title'=>'Edit Class on '. date('jS M Y, H:i A', strtotime($class_details['class_on'])))); ?>
<form action="<?php echo site_url('classes/edit_class_save') ?>" class="form-area" method="post">
<ul class="form city-form">
<?php for($i=0; $i<count($class_details['teachers']); $i++) {
	$class = $class_details['teachers'][$i];
	// You don't get to edit others stuff if you don't have super privilages.
	$edit = false;
	if($class['user_id'] == $this->session->userdata('id') or $this->user_auth->get_permission('class_edit_class'))
		$edit = true;
	
	if($edit) echo form_hidden('user_id['.$i.']', $class['user_id']);
?>
<li  style="width:400px;">
<label for='user_id[<?php echo $i ?>]'>Teacher</label>

<strong><?php echo $teachers[$class['user_id']] ?></strong>
</li>

<li>
<label for='substitute_id[<?php echo $i ?>]'>Substitue</label>
<div id="sustitue<?php echo $i ?>">
<?php 
if($edit) echo form_dropdown('substitute_id['.$i.']', $substitutes, $class['substitute_id'],'id="other_city'.$i.'"'); 
else echo $substitutes[$class['substitute_id']];
?>
</div>
</li>

<li>
<label for='status[<?php echo $i ?>]'>Status</label>
<?php 
if($this->user_auth->get_permission('class_edit_class')) $possible_statuses = $statuses;
else $possible_statuses = array('projected'	=> 'Projected', 'confirmed'	=> 'Confirmed');

if($edit) echo form_dropdown('status['.$i.']', $possible_statuses, $class['status']); 
else echo $statuses[$class['status']];
?>
</li>

<li><?php echo form_hidden('user_class_id['.$i.']', $class['id']); ?></li>
<?php } ?>

<?php if(date('Y-m-d H:i:s') > $class_details['class_on']) { ?>
<li>
<label for="lesson_id">Feedback</label>
<?php if($edit) echo form_dropdown('lesson_id', $all_lessons, $class_details['lesson_id']);
	  else echo $all_lessons[$class_details['lesson_id']]; ?>
<?php } ?>
</li>
</ul>
<ul>
<li>
<?php 
echo form_hidden('class_id', $class_details['id']);
echo form_hidden('project_id', 1);
if($edit) echo '<label for="action">&nbsp;</label>' . form_submit('action', 'Edit', 'class="green button"');
?>
</li>
</ul>
</form>

<?php $this->load->view('layout/footer'); ?>
