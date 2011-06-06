<?php $this->load->view('layout/header', array('title'=>'Settings', 'message'=>$message)); ?>
<script>
function addsettings()
{
$.ajax({
		type: "POST",
		url: "<?= site_url('settings/add_settings')?>",
		success: function(msg){
			$('#sidebar').html(msg);
		}
		});

}
function get_settingslist()
{
		//alert("hi");
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('settings/setting_list_refresh')?>",
			success: function(msg){
			$('#setting_update').html(msg);
			}
			});
}
function edit_settings(id)
{
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('settings/edit_settings');?>"+'/'+id,
			success: function(msg){
			$('#sidebar').html(msg);
			}
			});
}
function deleteEntry(entryId)
	{
		var bool = confirm("confirm delete!")
		if(bool)
		{
			$.ajax({
			type : "POST",
			url  : "<?=site_url('settings/delete')?>"+'/'+entryId,
			success : function(data)
			{		
			 	get_settingslist();
			}
			
			});
		}
	}	
</script>
<div id="head" class="clear"><h1>Settings</h1>
<?php if($this->user_auth->get_permission('setting_create')) { ?>
<div id="actions"> 
<a href="javascript:addsettings();" class="button primary" id="example" style="margin-bottom:10px;" name="Add City">Add Settings</a>
</div>
<?php } ?>
</div>
<div  id="setting_update" style="margin-top:50px;">
<table id="main" class="data-table tablesorter info-box-table">
<thead><tr><th style="width:500px;">Name</th><th colspan="2">Action</th></tr></thead>

<?php foreach($all_settings as $result) { ?>
<tr><td><?php echo $result->name;
	?></td>
<td><a href="javascript:edit_settings('<?=$result->id?>');" class="edit with-icon">Edit</a>&nbsp; <a href="javascript:deleteEntry('<?=$result->id?>');" class="delete with-icon">Delete</a></td>
</tr>
<?php } ?>

</table>
</div>
<?php $this->load->view('layout/settings_footer'); ?>