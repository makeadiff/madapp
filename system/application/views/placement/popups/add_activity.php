<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add New Activity</h2>
<script>
function validate(id)
{
	if(document.getElementById("activityname").value == '')
          {		
              alert("Activity Name Missing.");
			  document.getElementById('activityname').focus();
              return false;
          }
          if(document.getElementById("locact").value == '')
          {		
              alert("Location Missing.");
			  document.getElementById('locact').focus();
              return false;
          }
          
          if(document.getElementById("sex").value == '')
          {		
              alert("Gender Missing.");
			  document.getElementById('sex').focus();
              return false;
          }
         
}
</script>

<div id="message"></div>
<div style="float:left; margin-top:10px;">
<form id="formEditor" class="mainForm clear" action="<?= site_url('placement/addactivity_name')?>" method="post" enctype="multipart/form-data" onsubmit="return validate();" style="width:355px;">
<fieldset class="clear">
    <ul class="form city-form">
        <li>
<label for="txtName">Activity Name : </label>
<input id="activityname" name="activityname" type="text" />
        </li>
        <li>
<label for="locact">Location : </label>
<select name="locact" id="locact">
 <option value="">Select Location</option>
 <option value="inbound">Inbound</option>
  <option value="outbound">Outbound</option>
</select>
 </li>
        <li>

<label for="skill">Skill : </label>
<input type="checkbox" value="1" id="skill" name="skill" />
</li>
        <li>

<label for="career">Career : </label>
<input type="checkbox" value="1" id="career" name="career" />
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

<label for="generalised">Generalised : </label>
<input type="checkbox" value="1" id="generalised" name="generalised" />
</li>
        <li>


<label for="specialised">specialised : </label>
<input type="checkbox" value="1" id="specialised" name="specialised" />
</li>
        <li>


<label for="field_expert">Field Expert : </label>
<input type="checkbox" value="1" id="field_expert" name="field_expert" />
</li>
        <li>
<label for="file">File : </label>
<input name="file"  id="file" type="file"><br />
</li>
        <li>
<label for="link">Link : </label>
<input name="link"  id="link" type="text">
</li>
       
        </ul>
<ul>
<li>
   
<input id="btnSubmit"  class="button green" type="submit" value="Submit"  />
<a href="<?php echo site_url('placement/manageplacement_activity') ?>" class="cancel-button">Cancel</a>
</li>
</ul>
</fieldset>
</form>
</div>

<?php $this->load->view('layout/thickbox_footer'); ?>