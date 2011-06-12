<?php $this->load->view('layout/thickbox_header'); ?>
<script>
function update_permission(id)
{
var permission=$('#permission').val();
if(permission == '')
{ alert("Enter Permission Name");
 }else{
$.ajax({
		type: "POST",
		url: "<?=site_url('permission/edit_permission')?>"+'/'+id,
		data: "permission="+permission,
		success: function(msg){
			$('#message').html(msg);
			window.parent.get_permissionlist(0,'');
			
		}
		});
}
}
</script>

<?php
$details=$content->result_array();
foreach($details as $row)
{
$root_id=$row['id'];
$name=$row['name'];
}
?>
<div style="float:left;"><h1>Edit Permission</h1></div>
<div id="message"></div>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="" method="post" style="width:500px;" onsubmit="return false">
<fieldset class="clear">

<div class="field clear" style="width:600px;"> 
		<label for="txtName">Permission Name : </label>
		<input id="permission" name="permission"  id="permission" type="text" value="<?php echo $name ?>" /> 
</div>
<div class="field clear" style="width:550px;"> 
		<input style="margin-left:50px; margin-top:50px;" id="btnSubmit" onclick="javascript:update_permission('<?=$root_id?>');" class="button primary" type="submit" value="Submit" />
</div>
</fieldset>
</form>
</div>