<?php $this->load->view('layout/header', array('title'=>'Levels in ' . $center_name)); ?>

<div id="head" class="clear">
<h1>Levels in <?php echo $center_name ?></h1>

<div id="actions">
<a id="example" class="button primary" href="<?php echo base_url() ?>index.php/level/create/center/<?php echo $center_id ?>" class="add">Create New Level in <?php echo $center_name ?></a>
</div>
</div>

<table class="data-table" id="main">
<tr><th>Level Name</th><th colspan="2">Action</th></tr>
<?php foreach($all_levels as $level) { ?>
<tr>
<td><?php echo $level->name ?></td>
<td><a href="<?php echo base_url() ?>index.php/level/edit/<?php echo $level->id ?>" class="edit with-icon">Edit</a></td>
<td><a href="<?php echo base_url() ?>index.php/level/delete/<?php echo $level->id ?>" class="confirm delete with-icon" title="Delete <?php echo addslashes($level->name) ?>">Delete</a></td>
</tr>
<?php } ?>
</table>


<?php $this->load->view('layout/footer'); ?>