<?php 
$this->load->view('layout/header', array('title' => "Subjects"));
?>
<div id="head" class="clear"><h1>Subjects</h1></div>
<?php if($this->user_auth->get_permission('subject_create')) { ?>
<div id="actions">
    <a class="thickbox button green primary popup" name="Add Subject" href="<?php echo site_url('subject/add') ?>">New Subject</a>
</div>
<?php } ?>

<?php if(!$subjects) { ?>
<div class="error-message">No Subjects Found...</div>
<?php } else { ?>

<table class="data-table">
<tr><th>Subject</th><th colspan="2">Action</th></tr>
<?php foreach ($subjects as $subject) { ?>
<tr>
<td><?php echo $subject->name ?></td>
<?php if($this->user_auth->get_permission('subject_edit')) { ?><td><a class="with-icon edit popup" href="<?php echo site_url('subject/edit/' . $subject->id); ?>">Edit</a></td><?php } ?>
<?php if($this->user_auth->get_permission('subject_create')) { ?><td><a class="with-icon delete" href="<?php echo site_url('subject/delete/' . $subject->id); ?>">Delete</a></td></tr><?php } ?>
<?php } ?>
</table>
<?php } ?>

<br />
<!-- <a href="<?php echo site_url('subject/list_timeframes/'.$user_id) ?>">List Timeframes</a> -->

<script type="text/javascript">
	var site_url = "<?php echo site_url() ?>";
</script>
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/subject/list_subjects.js"></script>
<?php $this->load->view('layout/footer');