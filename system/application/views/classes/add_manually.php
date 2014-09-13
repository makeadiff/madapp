<?php $this->load->view('layout/thickbox_header', array('title'=>'Add Class Manually')); ?>
<h2>Add Class Manually</h2>

<form action="<?php echo site_url('classes/add_manually_save')?>" method="post">

<ul class="form">
<li><label for="class_date">Date: </label>
<input id="class_date" name="class_date" type="text" value="" /></li>

<li><input class="button green" type="submit" value="Add Classes" />
<a href="<?php echo site_url('task/index') ?>" class="sec-action">Cancel</a></li>
</ul>

<input type="hidden" name="center_id" value="<?php echo $center_id ?>" />
<input type="hidden" name="batch_id" value="<?php echo $batch_id ?>" />
</form>

<?php $this->load->view('layout/thickbox_footer'); ?>
