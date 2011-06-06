<div style="float:left;"><h1>Edit books</h1></div>
<script>
function update_book(id)
{
var bookname=$('#bookname').val();
if(bookname == '')
{ alert("Enter Bookname");
 }else{
$.ajax({
		type: "POST",
		url: "<?=site_url('books/updatebook')?>"+'/'+id,
		data: "bookname="+bookname,
		success: function(msg){
			$('#message').html(msg);
			window.parent.get_booklist(0,'');
			
		}
		});
}
}
</script>

<div id="message"></div>
<?php $book_name=$book_name->result_array(); ?>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="" method="post" style="width:500px;" onsubmit="return false"  >
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

<div class="field clear"  style="width:550px;"> 
		<input style="margin-left:40px; margin-top:30px;" id="btnSubmit" class="button primary" onclick="javascript:update_book('<?php echo $root_id; ?>');" type="submit" value="Submit" />
</div>
</fieldset>
</form>
</div>

