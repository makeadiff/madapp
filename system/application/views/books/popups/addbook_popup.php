<div style="float:left;"><h1>Add books</h1></div>
<script>
function insert_book(id)
{
var bookname=$('#bookname').val();
if(bookname == '')
{ alert("Enter Bookname");
 }else{
$.ajax({
		type: "POST",
		url: "<?php echo site_url('books/addbook')?>",
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
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="" method="post" style="width:500px;" onsubmit="return false"  >
<fieldset class="clear">
            
<div class="field clear" style="width:500px;"> 
			<label for="txtName">BookName : </label>
			<input id="bookname" name="bookname"  type="text" /> 
			
</div>

<div class="field clear" style="width:550px;"> 
		<input style="margin-left:50px; margin-top:30px;" id="btnSubmit" class="button primary" onclick="javascript:insert_book();" type="submit" value="Submit" />
</div>
</fieldset>
</form>
</div>


