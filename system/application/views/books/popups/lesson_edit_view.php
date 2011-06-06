<div style="float:left;"><h1>Edit Lessons</h1></div>
<?php $book_name=$book_name->result_array(); ?>
<script>
function update_lesson(id){
var book_id=$('#book').val();
var lessonname=$('#lessonname').val();
if(book_id != 0 )
{
if(lessonname != '' ){
	$.ajax({
		type: "POST",
		url: "<?=site_url('books/update_lesson')?>"+'/'+id,
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
			<?php foreach($book_name as $row){
			$name=$row['name'];
			$root_id=$row['id'];
			$book_id=$row['book_id'];
			}
			 ?>
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Select Book : </label>
                        <select id="book" name="book" > 
            			<option value="0" >- choose action -</option> 
						<?php 
                		$details = $details->result_array();
                		foreach($details as $row) {
                		?>
                        <?php if($row['id']==$book_id){?>
                		<option value="<?php echo $row['id']; ?>" selected="selelcted"><?php echo $row['name']; ?></option> 
                        <?php }else{?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
               		 	<?php } }?>
            			</select>
                      
            </div>
            <div class="field clear" style="width:600px; margin-top:10px;"> 
                        <label for="txtName">BookName : </label>
                        <input id="lessonname" style="height:20px;" name="lessonname"  type="text" value="<?=$name?>" /> 
            </div>
            <div class="field clear" style="width:550px;"> 
     				<input style="margin-left:50px; margin-top:30px;" onclick="javascript:update_lesson('<?=$root_id?>');" id="btnSubmit" class="button primary" type="submit" value="Submit" />
            </div>
            </fieldset>
            </form>
            
         </div>