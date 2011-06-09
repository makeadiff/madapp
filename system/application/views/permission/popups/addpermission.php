<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script>
function insert_permission(id)
{
var permission=$('#permission').val();
if(permission == '')
{ alert("Enter Permission Name");
 }else{
$.ajax({
		type: "POST",
		url: "<?=site_url('permission/addpermission')?>",
		data: "permission="+permission,
		success: function(msg){
			$('#message').html(msg);
			window.parent.get_permissionlist(0,'');
			
		}
		});
}
}
</script>
<div style="float:left;"><h1>Add Permission</h1></div>
<div id="message"></div>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="" method="post" style="width:500px;" onsubmit="return false">
<fieldset class="clear">

<div class="field clear" style="width:600px;"> 
		<label for="txtName">Permission Name : </label>
		<input id="permission" name="permission" id="permission"  type="text" /> 
</div>
<div class="field clear" style="width:550px;"> 
		<input style="margin-left:50px; margin-top:30px;" onclick="javascript:insert_permission();" id="btnSubmit" class="button primary" type="submit" value="Submit" />
</div>
</fieldset>
</form>
</div>