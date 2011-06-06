<div style="float:left;"><h1>Add Settings</h1></div>
<div id="message"></div>
<div style="float:left; margin-top:20px;">
<script>
function insert_settings()
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
					url: "<?php echo site_url('settings/create')?>",
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

<div id="head" class="clear"></div>
<form action="" method="post" class="form-area" id="main" onsubmit="return false">
<div style=" margin-top:10px;">
<label for='name'> Name :</label>
<input type="text"  name="name" id="name"  /><br/>
</div>
<div style=" margin-top:10px;">
<label for='value'>Value :</label>
<input type="text" style="line-height:15px;" name="value" id="value"  /><br />
</div>
<div style=" margin-top:10px;">
<label for='data'>Data :</label>
<input type="text" style="line-height:15px;" name="data" id="data"  /><br />
</div>
<div style=" margin-top:10px; margin-left:50px;">
<?php 
//echo form_hidden('id', $setting['id']);
//echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action);
?>
<div class="field1 clear" style="width:500px;"> 
		<input style="margin-left:15px; margin-top:30px;" id="btnSubmit" class="button primary" onclick="javascript:insert_settings();" type="submit" value="Submit" />
</div>
</div>
</form><br />
</div>