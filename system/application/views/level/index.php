<?php $this->load->view('layout/header', array('title'=>'Class Names in ' . $center_name)); ?>

<div id="head" class="clear">
<h1>Class Names in <?php echo $center_name ?></h1>

<div id="actions">
<a  id="example" class="thickbox button green primary popup" href="<?php echo site_url('level/create/center/'.$center_id); ?>">Create New Class Name</a>
</div><br class="clear" />

<div id="train-nav">
<ul>
<li id="train-prev"><a href="<?php echo site_url('kids/manageaddkids')?>">&lt; Manage Kids</a></li>
<?php if($this->session->userdata("active_center")) { ?>
<li id="train-top"><a href="<?php echo site_url('center/manage/'.$this->session->userdata("active_center"))?>">^ Manage Center</a></li>
<li id="train-next"><a href="<?php echo site_url('batch/index/center/'.$this->session->userdata("active_center"))?>">Manage Batches &gt;</a></li>
<?php } else { ?>
<li id="train-top"><a href="<?php echo site_url('center/manageaddcenters')?>">^ Manage Center</a></li>
<?php } ?>
</ul>
</div>
</div>

<table class="data-table" id="main">
<tr><th>Class Name</th><th>Medium</th><th colspan="2">Action</th></tr>
<?php foreach($all_levels as $level) { ?>
<tr>
<td><?php echo $level->grade . ' ' . $level->name; ?></td>
<td><?php echo $all_mediums[$level->medium_id]; ?></td>
<td><a href="<?php echo base_url() ?>index.php/level/edit/<?php echo $level->id ?>" class="thickbox  primary popup edit with-icon" >Edit</a></td>
<td><a href="<?php echo base_url() ?>index.php/level/delete/<?php echo $level->id ?>" class="confirm delete with-icon" title="Delete <?php echo addslashes($level->name) ?>">Delete</a></td>
</tr>
<?php } ?>
</table>


<?php $this->load->view('layout/footer'); ?>