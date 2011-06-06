<div style="float:left;"><h1>Add Lessons</h1></div>
<script>
function insert_lesson(){
var book_id=$('#book').val();
var lessonname=$('#lessonname').val();
if(book_id != 0 )
{
if(lessonname != '' ){
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('books/addlesson')?>",
		data: "book_id="+book_id+"&lessonname="+lessonname,
		success: function(msg){
			$('#message').html(msg);
			window.parent.get_chapterlist(0,'');
		}
		});
}else{alert("Enter Bookname");}
}else{alert("Select Booknam");}
}
</script>
<div id="message"></div>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="" method="post" style="width:500px;" onsubmit="return false"  >
<fieldset class="clear">
	
<div class="field clear" style="width:500px;"> 
			<label for="txtName">Select Book : </label>
			<select id="book" name="book" > 
			<option value="0" >- choose action -</option> 
			<?php 
			$details = $details->result_array();
			foreach($details as $row) {
			?>
			<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
			<?php } ?>
			</select>
			
</div>


<div class="field clear" style="width:500px; margin-top:10px;"> 
			<label for="txtName">LessonName: </label>
			<input id="lessonname" name="lessonname"  type="text" /> 
			
</div>

<div class="field clear" style="width:550px;"> 
		<input style="margin-left:50px; margin-top:50px;" id="btnSubmit"  onclick="javascript:insert_lesson();" class="button primary" type="submit" value="Submit" />
</div>
</fieldset>
</form>
</div>

