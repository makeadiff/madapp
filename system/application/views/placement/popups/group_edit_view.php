<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Edit Group</h2>
<script>
function validate()
{
if(document.getElementById("groupname").value == '')
          {		
              alert("GroupName Missing.");
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
<?php
$details=$details->result_array();
foreach($details as $row) {
	$root_id=$row['id'];
	$name=$row['name'];
        $group =$row['group'];
        $center_id=$row['center_id'];
        $sex=$row['sex'];
        $actfrq=$row['activity_frequency'];
}
?>

<div id="message"></div>
<div style="float:left; margin-top:20px;">
	<form id="formEditor" class="mainForm clear" action="<?php echo site_url('placement/updategroup_name/'.$root_id)?>" method="post" onsubmit="return validate();" style="width:355px;">
<fieldset class="clear">
    <ul class="form city-form">
        <li>
<label for="groupname">Group Name : </label>
<input id="groupname" name="groupname"  type="text" value="<?php echo $name; ?>"/>
</li>
        <li>
<label for="classgroup">Class Group : </label>
<select name="cgroup" id="cgroup">
    <option value="">Select Group</option>
    <option value="5_6" <?php if($group == '5_6'){  ?> selected="selected" <?php } ?> >5&6</option>
    <option value="7_8" <?php if($group == '7_8'){ ?> selected="selected" <?php } ?> >7&8</option>
    <option value="9_10" <?php if($group == '9_10'){ ?> selected="selected" <?php } ?> >9&10</option>
    <option value="11_12" <?php if($group == '11_12'){ ?> selected="selected" <?php } ?> >11&12</option>
   
</select>
</li>
        <li>

<label for="sex">Sex: </label>
<select name="sex" id="sex">
    <option value="">Select Sex</option>
    <option value="m" <?php if($sex == 'm'){  ?> selected="selected" <?php } ?> >Male</option>
    <option value="f" <?php if($sex == 'f'){ ?> selected="selected" <?php } ?> >Female</option>
    <option value="coed" <?php if($sex == 'coed'){ ?> selected="selected" <?php } ?> >coed</option>
   
</select>
</li>
        <li>

<label for="center_id">Center: </label>
<select name="center_id" id="center_id">
    <option value="">Select Center</option>
    <?php foreach($center->result_array() as $row) {  ?>
    <option value="<?php echo $row['id']; ?>" <?php if($row['id'] == $center_id) { ?> selected="selected" <?php } ?>><?php echo $row['name']; ?></option>
    <?php } ?>
</select>
</li>
        <li>

<label for="txtName">Activity Frequency: </label>
<input id="actfrq" name="actfrq" type="text" value="<?php echo $actfrq; ?>" />
</li>
       

    </ul>
		<ul>
		<li><input id="btnSubmit"  class="button green" type="submit" value="Submit"  />
		<a href="<?=site_url('placement/manageaddchild_group')?>" class="cancel-button">Cancel</a>
		</li>
		</ul>
    </fieldset>
    </form>		
</div>

