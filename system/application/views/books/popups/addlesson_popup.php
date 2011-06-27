<?php $this->load->view('layout/thickbox_header'); ?>
<script>
function validate()
{
if(document.getElementById("book").value == '')
          {		
              alert("Bookname Name Missing.");
			  document.getElementById('book').focus();
              return false;
          }
if(document.getElementById("lessonname").value == '')
          {		
              alert("Lesson Name Missing.");
			  document.getElementById('lessonname').focus();
              return false;
          }
}
</script>
<div style="float:left;"><h1>Add Lessons</h1></div>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="<?=site_url('books/addlesson')?>" method="post" onsubmit="return validate();" >
<fieldset class="clear">
<ul class="form city-form">
<li>
	<label for="txtName">Book : </label>
	<select id="book" name="book" >
	<?php 
	$details = $details->result_array();
	foreach($details as $row) {
	?>
	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
	<?php } ?>
	</select>
</li>


<li>
	<label for="txtName">Lesson Name: </label>
	<input id="lessonname" name="lessonname"  type="text" /> 
			
</li>
</ul>
<ul>
<li>
		<input id="btnSubmit"  class="button green" type="submit" value="Submit" />
        <a href="<?=site_url('books/manage_chapters')?>" class="cancel-button">Cancel</a>

</li>
</ul>
</fieldset>
</form>
</div>
