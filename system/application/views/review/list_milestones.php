<?php 
$this->load->view('layout/header', array('title' => "Milestones"));
$status = array('Todo', 'Done');
?>
<div id="head" class="clear"><h1>Milestones</h1></div>
<?php if($this->user_auth->get_permission('review_milestone_create')) { ?>
<div id="actions">
    <a class="thickbox button green primary popup" name="Add Milestone" href="<?php echo site_url('review/new_milestone/'.$user_id) ?>">New Milestone</a>
</div>
<?php } ?>

<?php if(!$milestones) { ?>
<div class="error-message">No Milestones Found...</div>
<?php } else { ?>

<table class="data-table">
<tr><th>Milestone</th><th>Status</th><th>Timeframe</th><th>Due On</th><th colspan="2">Action</th></tr>
<?php foreach ($milestones as $milestone) { ?>
<tr><td><?php echo $milestone->name ?></td>
<td><input class="milestone" type="checkbox" value="1" id="milestone-<?php echo $milestone->id ?>" <?php if($milestone->status == '1') echo ' checked'; ?> /></td>
<td><?php echo $all_timeframes[$milestone->due_timeframe] ?></td>
<td><?php echo $milestone->due_on ?></td>
<?php if($this->user_auth->get_permission('review_milestone_edit')) { ?><td><a class="with-icon edit popup" href="<?php echo site_url('review/edit_milestone/' . $milestone->id); ?>">Edit</a></td><?php } ?>
<?php if($this->user_auth->get_permission('review_milestone_create')) { ?><td><a class="with-icon delete popup" href="<?php echo site_url('review/delete_milestone/' . $milestone->id); ?>">Delete</a></td></tr><?php } ?>
<?php } ?>
</table>
<?php } ?>

<br />
<!-- <a href="<?php echo site_url('review/list_timeframes/'.$user_id) ?>">List Timeframes</a> -->

<script type="text/javascript">
	var site_url = "<?php echo site_url() ?>";
</script>
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/review/list_milestones.js"></script>
<?php $this->load->view('layout/footer');