<?php $this->load->view('layout/header', array('title'=>'Settings', 'message'=>$message)); ?>

<div id="head" class="clear"><h1>Settings</h1>

<?php if($this->user_auth->get_permission('setting_create')) { ?>
<div id="actions"> 
<a href="<?= site_url('settings/create')?>" class="button primary" id="example" name="Add City">Add Settings</a>
</div>
<?php } ?>
</div>

<table id="main" class="data-table tablesorter info-box-table">
<thead><tr><th style="width:500px;">Name</th><th colspan="2">Action</th></tr></thead>
<?php foreach($all_settings as $result) { ?>
<tr><td><?php echo $result->name;
	?></td>
<td><a href="<?php echo site_url('settings/edit/'.$result->id); ?>" class="edit with-icon">Edit</a>&nbsp; <a href="<?php echo site_url('settings/delete/'.$result->id); ?>" class="delete with-icon">Delete</a></td>
</tr>
<?php } ?>
</table>

<?php $this->load->view('layout/footer'); ?>