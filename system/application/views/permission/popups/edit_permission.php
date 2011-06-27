<?php $this->load->view('layout/thickbox_header'); ?>
<script>
function validate()
{
if(document.getElementById("permission").value == '')
          {		
              alert("Permission Name Missing.");
			  document.getElementById('permission').focus();
              return false;
          }
}
</script>
<?php
$details=$content->result_array();
foreach($details as $row)
{
$root_id=$row['id'];
$name=$row['name'];
}
?>
<div style="float:left;"><h1>Edit Permission</h1></div>
<div id="message"></div>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="<?=site_url('permission/edit_permission/'.$root_id)?>" method="post" onsubmit="return validate();">
<fieldset class="clear">
<ul class="form city-form">
	<li>
		<label for="txtName"> Name : </label>
		<input id="permission" name="permission"   id="permission" type="text" value="<?php echo $name ?>" /> 
	</li>
    </ul>
    <ul>
    <li>
		<input id="btnSubmit"  class="button green" type="submit" value="Submit" />
</li>
</ul>
</fieldset>
</form>
</div>