<div style="float:left;"><h1>Edit Settings</h1></div>
<div id="message" style="margin-left:-15px;"></div>
<div style="float:left; margin-top:20px;">
<script>
function update_settings(id)
{
var name=$('#name').val();
var value=$('#value').val();
var data=$('#data').val();

if(name != '')
{ 
	if(value != '')
	{
		if(data != '')
			{
			$.ajax({
					type: "POST",
					url: "<?php echo site_url('settings/edit')?>"+'/'+id,
					data: "name="+name+"&value="+value+"&data="+data,
					success: function(msg){
						$('#message').html(msg);
						window.parent.get_settingslist();
						}
				});
}else{alert("Enter Data");
document.getElementById('data').focus();
}
}else{alert("Enter Value");
document.getElementById('value').focus();
}		
}else { alert("Enter Name");
document.getElementById('name').focus();
}
}
</script>
<form action="" method="post" class="form-area" id="main" onSubmit="return false">
<div style=" margin-top:10px;">
<label for='name'> Name :</label>
<input type="text"  name="name" id="name" value="<?php echo $setting['name']; ?>" /><br/>
</div>
<div style=" margin-top:10px;">
<label for='value'>Value :</label>
<input type="text" style="line-height:15px;" name="value" id="value" value="<?php echo  $setting['value']; ?>" /><br />
</div>
<div style=" margin-top:10px;">
<label for='data'>Data :</label>
<input type="text" style="line-height:15px;" name="data" id="data" value="<?php echo $setting['data']; ?>" /><br />
</div>
<div style=" margin-top:10px; margin-left:50px;">
<?php 
//echo form_hidden('id', $setting['id']);
//echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action);
?>
<div class="field1 clear" style="width:500px;"> 
<input style="margin-left:15px; margin-top:30px;" id="btnSubmit" class="button primary" onclick="javascript:update_settings('<?=$setting['id']?>');" type="submit" value="Submit" />
</div>
</div>
</form><br />
</div>