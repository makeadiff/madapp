<?php 
$this->load->view('layout/header', array('title' => "Milestones"));
$status = array('Todo', 'Done');
?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/sections/review/my_milestones.css" />
<div id="head" class="clear"><h1>Milestones</h1></div>

<?php if($overdue_milestones) { ?>
<h3>Overdue Milestones</h3>
<ul id="overdue">
<?php foreach ($overdue_milestones as $milestone) { ?>
	<li><input class="milestone" type="checkbox" value="1" name="milestone[<?php echo $milestone->id ?>]" id="milestone-<?php echo $milestone->id ?>" disabled />
		<label class="milestone"  id="milestone-label-<?php echo $milestone->id ?>"  for="milestone-<?php echo $milestone->id ?>"><?php echo $milestone->name ?></label>
	</li>
<?php } ?>
</ul>
<?php }

if($current_milestones) { ?>
<h3>Current Milestones</h3>
<ul id="current">
<?php foreach ($current_milestones as $key => $milestone) { ?>
	<li <?php if($milestone->status == '1') echo 'class="milestone-done"'; ?>>
		<input class="milestone" type="checkbox" value="1" name="milestone[<?php echo $milestone->id ?>]" id="milestone-<?php echo $milestone->id ?>"
			<?php if($milestone->status == '1') echo ' checked'; ?> disabled />
		<label class="milestone"  id="milestone-label-<?php echo $milestone->id ?>" for="milestone-<?php echo $milestone->id ?>"><?php echo $milestone->name ?></label>
	</li>
<?php } ?>
</ul>

<?php } else { ?>
<div class="error-message">No Milestones for the current timeframe...</div>
<?php } ?>


<?php $this->load->view('layout/footer');