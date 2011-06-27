<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Edit Book</h2>
<script>
function validate()
{
if(document.getElementById("bookname").value == '')
          {		
              alert("Bookname Missing.");
			  document.getElementById('bookname').focus();
              return false;
          }
}
</script>
<?php $book_name=$book_name->result_array(); ?>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="<?=site_url('books/updatebook')?>" method="post" onsubmit="return validate();">
<fieldset class="clear">
<?php foreach($book_name as $row){
$name=$row['name'];
$root_id=$row['id'];
}
?>
<ul class="form city-form">
<li>
<label for="txtName">BookName : </label>
<input id="bookname" name="bookname"  type="text" value="<?=$name?>"/> 
</li>
</ul>
<ul>
<li>
<input type="hidden" id="root_id" name="root_id"  value="<?=$root_id; ?>"/> 
<input id="btnSubmit" class="button green" type="submit" value="Submit" />
<a href="<?=site_url('books/manage_books')?>" class="cancel-button">Cancel</a>

</li>
</ul>
</fieldset>
</form>
</div>

