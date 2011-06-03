<?php $book_name=$book_name->result_array(); ?>

<form id="formEditor" class="mainForm clear" action="<?=site_url('books/updatebook')?>" method="post" style="width:500px;" onsubmit="return validate();"  >
<fieldset class="clear">
<?php foreach($book_name as $row){
$name=$row['name'];
$root_id=$row['id'];
}
	?>

<div class="field clear" style="width:500px;"> 
			<label for="txtName">BookName : </label>
			<input id="bookname" name="bookname"  type="text" value="<?=$name?>" /> 
			
</div>

<div class="field clear" style="width:550px;"> 
<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
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