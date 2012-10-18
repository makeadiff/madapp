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
          if(document.getElementById("cgroup").value == '')
          {		
              alert("Class group Missing.");
			  document.getElementById('cgroup').focus();
              return false;
          }
          if(document.getElementById("sex").value == '')
          {		
              alert("Gender Missing.");
			  document.getElementById('sex').focus();
              return false;
          }
          if(document.getElementById("actfrq").value == '')
          {		
              alert("Activity Group Missing.");
			  document.getElementById('actfrq').focus();
              return false;
          }
}
</script>
<div id="message"></div>
<div style="float:left; margin-top:10px;">
<form id="formEditor" class="mainForm clear" action="<?= site_url('placement/addgroup_name')?>" method="post" onsubmit="return validate();" style="width:355px;">
<fieldset class="clear">
<ul class="form city-form">
    <li>
<label for="txtName">Group Name : </label>
<input id="groupname" name="groupname" type="text" />
    </li>
    <li>
<label for="classgroup">Class Group : </label>
<select name="cgroup" id="cgroup">
    <option value="">Select Group</option>
    <option value="5_6">5&6</option>
    <option value="7_8">7&8</option>
    <option value="9_10">9&10</option>
    <option value="11_12">11&12</option>
   
</select>
    </li>
<li>
<label for="sex">Sex: </label>
<select name="sex" id="sex">
    <option value="">Select Sex</option>
    <option value="m">Male</option>
    <option value="f">Female</option>
    <option value="coed">coed</option>
   
</select>
</li>
<li>
<label for="center_id">Center: </label>
<select name="center_id" id="center_id">
    <option value="">Select Center</option>
    <?php foreach($center->result_array() as $row) {  ?>
   <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
    <?php } ?>
</select>
</li>

<li>
<label for="txtName">Activity Frequency: </label>
<input id="actfrq" name="actfrq" type="text" />
</li>

</ul>
<ul>
<li>
   
<input id="btnSubmit"  class="button green" type="submit" value="Submit"  />
<a href="<?php echo site_url('placement/manageaddchild_group') ?>" class="cancel-button">Cancel</a>
</li>
</ul>

</fieldset>
</form>
</div>

<?php $this->load->view('layout/thickbox_footer'); ?>