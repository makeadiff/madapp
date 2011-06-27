<?php $this->load->view('layout/header', array('title'=>'Settings', 'message'=>$message)); ?>
<div id="head" class="clear"><h1>Settings</h1>
<?php if($this->user_auth->get_permission('setting_create')) { ?>
<div id="actions"> 
<a href="<?= site_url('settings/add_settings')?>" class="popup button primary" id="example" style="margin-bottom:10px;" name="Add City">Add Settings</a>
</div>
<?php } ?>
</div>
<div  id="setting_update" style="margin-top:50px;">
<table id="main" class="data-table tablesorter info-box-table">
<thead><tr><th style="width:550px;">Name</th><th colspan="2">Action</th></tr></thead>

<?php foreach($all_settings as $result) { ?>
<tr><td><?php echo $result->name;
	?></td>
<td><a href="<?=site_url('settings/edit_settings/'.$result->id)?>" class="popup icon edit">Edit</a>&nbsp; <a href="<?=site_url('settings/delete/'.$result->id)?>" class="actionDelete icon delete confirm"></a></td>
</tr>
<?php } ?>

</table>
</div>
<?php $this->load->view('layout/settings_footer'); ?>