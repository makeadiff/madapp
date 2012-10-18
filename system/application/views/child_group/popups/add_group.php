<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add New Group</h2>
<script>
function validate(id)
{
	if(document.getElementById("groupname").value == '')
          {		
              alert("Group Name Missing.");
			  document.getElementById('groupname').focus();
              return false;
          }
}
</script>
<style type="text/css">
li label { width:200px !important; }
</style>


<div id="message"></div>
<div style="float:left; margin-top:10px;">
<form id="formEditor" class="mainForm clear" action="<?= site_url('user_group/addgroup_name')?>" method="post" onsubmit="return validate();" style="width:355px;">
<fieldset class="clear">
<label for="txtName">Group Name : </label>
<input id="groupname" name="groupname" type="text" /><br />
<ul class="form city-form">
<li><label for="txtName">Permissions :</label></li>
<?php 
$permission=$permission->result_array();
foreach($permission as $row){ ?>
<li>
<label for="permission-<?php echo $row['id'] ?>"><?php echo $row['name']; ?></label>
<input type="checkbox" value="<?php echo $row['id']; ?>" id="permission-<?php echo $row['id'] ?>" name="permission[]" /> 
</li>
<?php } ?>
</ul>

<ul>
<li>
   
<input id="btnSubmit"  class="button green" type="submit" value="Submit"  />
<a href="<?php echo site_url('user_group/manageadd_group')?>" class="cancel-button">Cancel</a>
</li>
</ul>
</fieldset>
</form>
</div>

<?php $this->load->view('layout/thickbox_footer'); ?>