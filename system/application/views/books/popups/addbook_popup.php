<form id="formEditor" class="mainForm clear" action="<?php echo site_url('books/addbook')?>" method="post" style="width:500px;" onsubmit="return validate();"  >
<fieldset class="clear">
            
<div class="field clear" style="width:500px;"> 
			<label for="txtName">BookName : </label>
			<input id="bookname" name="bookname"  type="text" /> 
			
</div>

<div class="field clear" style="width:550px;"> 
		<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
</div>
</fieldset>
</form>


<script>
function validate()
{
if(document.getElementById("city").value == '0')
	{		
		alert("Select a City.");
		return false;
	}
if(document.getElementById("center").value == '')
	{
		alert("Center Missing.");
		return false;
	}
}
</script>