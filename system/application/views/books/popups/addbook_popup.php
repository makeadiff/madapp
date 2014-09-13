<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Book</h2>
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

<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="<?=site_url('books/addbook')?>" method="post" onsubmit="return validate();" >
<fieldset class="clear">
<ul class="form city-form">
<li>
<label for="txtName">BookName : </label>
<input id="bookname" name="bookname"  type="text"  /> 
</li>
</ul>
<ul>
<li>
<input  id="btnSubmit" class="button green" type="submit" value="Submit" />
<a href="<?=site_url('books/manage_books')?>" class="cancel-button">Cancel</a>

</li>
</ul>
</fieldset>
</form>
</div>


