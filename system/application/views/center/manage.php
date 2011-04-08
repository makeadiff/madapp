<?php
$title = 'Manage ' . $center_name;
$this->load->view('layout/header',array('title'=>$title));

function showMessage($count, $message) {
	if($message) $message = "($message)";
	if($count) echo '<span class="success with-icon">Completed ' . $message . '</span>'; 
	else echo '<span class="error with-icon">Not Started ' . $message . '</span>';
}

?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>
</div>

<ul id="steps-list">
<?php if($this->user_auth->get_permission('center_edit')) { ?><li><a class="thickbox popup" href="<?php echo site_url('center/popupEdit_center/'.$center_id); ?>">Edit Center Details</a> <?php 
	showMessage($details['center_head_id'], '');
	?></li><?php } ?>
	
<li><a href="<?php echo site_url('users/view_users'); ?>">Manage Volunteers</a> <?php 
	showMessage($details['total_volunteer_count'], $details['total_volunteer_count'] . " Volunteers");
	?></li>
	
<li><a href="<?php echo site_url('kids/manageaddkids'); ?>">Manage Kids</a> <?php 
	showMessage($details['kids_count'], $details['kids_count'] . " Kids");
	?></li>
	
<li><a href="<?php echo site_url('level/index/center/'.$center_id); ?>">Manage Levels</a> <?php 
	showMessage($details['level_count'], $details['level_count'] . " Levels");
	?></li>
	
<li><a href="<?php echo site_url('batch/index/center/'.$center_id); ?>">Manage Batches</a> <?php 
	showMessage($details['batch_count'], $details['batch_count'] . " Batchs");
	?></li>

<li><a href="<?php echo site_url('batch/index/center/'.$center_id); ?>">Assign Volunteers to Batches</a> <?php 
	showMessage($details['teacher_count'], $details['teacher_count'] . " volunteers assigned");
	?></li>
	
</ul>

<br /><br /><br /><br /><br /><br />
<?php if($this->user_auth->get_permission('center_delete')) { ?><a href="<?php echo site_url("center/deletecenter/".$center_id); ?>" class="confirm delete with-icon">Delete <?php echo $center_name ?> Center</a><?php } ?>

<?php
$this->load->view('layout/footer');
