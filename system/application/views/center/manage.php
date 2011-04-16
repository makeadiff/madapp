<?php
$title = 'Manage ' . $center_name;
$this->load->view('layout/header',array('title'=>$title));

function showMessage($count, $message, $type='') {
	if($message) $message = "($message)";
	
	$threshold = 0; // For most cases, the threshold is 0. We just need one item.
	if($type == 'kids') $threshold = 90;
	if($type == 'volunteers') $threshold = 30;
	
	if($count > $threshold) echo '<span class="success with-icon">Completed ' . $message . '</span>'; 
	else echo '<span class="error with-icon">Incomplete ' . $message . '</span>';
}

?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>
</div>

<ul id="steps-list">
<?php if($this->user_auth->get_permission('center_edit')) { ?><li><a class="thickbox popup" href="<?php echo site_url('center/popupEdit_center/'.$center_id); ?>">Step 1) Edit Center Details</a> <?php 
	showMessage($details['center_head_id'], '');
	?></li><?php } ?>
	
<li><a href="<?php echo site_url('user/view_users'); ?>">Step 2) Manage Volunteers</a> <?php 
	showMessage($details['total_volunteer_count'], $details['total_volunteer_count'] . " Volunteers", 'volunteers');
	?></li>
	
<li><a href="<?php echo site_url('kids/manageaddkids'); ?>">Step 3) Manage Kids</a> <?php 
	showMessage($details['kids_count'], $details['kids_count'] . " Kids", 'kids');
	?></li>
	
<li><a href="<?php echo site_url('level/index/center/'.$center_id); ?>">Step 4) Manage Levels</a> <?php 
	showMessage($details['level_count'], $details['level_count'] . " Levels");
	?></li>
	
<li><a href="<?php echo site_url('batch/index/center/'.$center_id); ?>">Step 5) Manage Batches</a> <?php 
	showMessage($details['batch_count'], $details['batch_count'] . " Batchs");
	?></li>

<li><a href="<?php echo site_url('batch/index/center/'.$center_id); ?>">Step 6) Assign Volunteers to Batches</a> <?php 
	showMessage($details['teacher_count'], $details['teacher_count'] . " volunteers assigned");
	?></li>
	
</ul>

<br /><br /><br /><br /><br /><br />
<?php if($this->user_auth->get_permission('center_delete')) { ?><a href="<?php echo site_url("center/deletecenter/".$center_id); ?>" class="confirm delete with-icon">Delete <?php echo $center_name ?> Center</a><?php } ?>

<?php
$this->load->view('layout/footer');
