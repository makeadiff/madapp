<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Edit Activity</h2>
<script>
function validate()
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
<?php
$details=$details->result_array();
foreach($details as $row) {
	$root_id=$row['id'];
	$name=$row['name'];
        $location =$row['location'];
        $skill=$row['skill'];
        $sex=$row['sex'];
        $career=$row['career'];
        $generalised =$row['generalised'];
        $specialised=$row['specialised'];
        $field_expert=$row['field_expert'];
        $city_id=$row['created_by_city_id'];
        $track=$row['track'];
        $class_range=$row['class_range'];
        $file=$row['file'];
        $link=$row['link'];
}
?>

<div id="message"></div>
<div style="float:left; margin-top:20px;">
    <form id="formEditor" class="mainForm clear" action="<?php echo site_url('placement/updateactivity_name/'.$root_id)?>" method="post" onsubmit="return validate();" style="width:355px;" enctype="multipart/form-data">
<fieldset class="clear">
    <ul class="form city-form">
        <li>
<label for="txtName">Activity Name : </label>
<input id="activityname" name="activityname" type="text" value="<?php echo $name; ?>" />
</li>
        <li>
<label for="locact">Location : </label>
<select name="locact" id="locact">
 <option value="">Select Location</option>
 <option value="inbound" <?php if($location == "inbound"){ ?> selected="selected" <?php } ?>>Inbound</option>
  <option value="outbound" <?php if($location == "outbound"){ ?> selected="selected" <?php } ?>>Outbound</option>
</select>
        </li>
        <li>

<label for="skill">Skill : </label>
<input type="checkbox" value="1" id="skill" name="skill" <?php if($skill == '1'){ echo "checked"; } ?> />
</li>
        <li>

<label for="career">Career : </label>
<input type="checkbox" value="1" id="career" name="career" <?php if($career == '1'){ echo "checked"; } ?> />
</li>
        <li>

<label for="sex">Sex: </label>
<select name="sex" id="sex">
    <option value="">Select Sex</option>
    <option value="m" <?php if($sex == "m"){ ?> selected="selected" <?php } ?>>Male</option>
    <option value="f" <?php if($sex == "f"){ ?> selected="selected" <?php } ?>>Female</option>
    <option value="coed" <?php if($sex == "coed"){ ?> selected="selected" <?php } ?>>coed</option>
   
</select>
</li>
        <li>

<label for="generalised">Generalised : </label>
<input type="checkbox" value="1" id="generalised" name="generalised" <?php if($generalised == '1'){ echo "checked"; } ?>/>
</li>
        <li>


<label for="specialised">specialised : </label>
<input type="checkbox" value="1" id="specialised" name="specialised" <?php if($specialised == '1'){ echo "checked"; } ?> />
</li>
        <li>


<label for="field_expert">Field Expert : </label>
<input type="checkbox" value="1" id="field_expert" name="field_expert" <?php if($field_expert == '1'){ echo "checked"; } ?> />
</li>

<!-- -->

<!-- -->
   <li>
<label for="creator">Creator : </label>
<select name="creator" id="creator">
 <option value="">Select City name</option>
 <?php foreach($city->result_array() as $row): ?>
 <option value="<?php echo $row['id']; ?>"  <?php if($row['id'] == $city_id){?>selected="selected" <?php }?>><?php echo $row['name']; ?></option>
  <?php endforeach;?>
</select>
 </li>
 
        <li>
<label for="txtName">Track : </label>
<input id="track" name="track" type="text" value="<?php echo $track; ?>"/>
        </li>
              <li>
<label for="txtName">Class Range : </label>
<input id="class-range" name="class-range" type="text" value="<?php echo $class_range; ?>"/>
        </li>

<!-- -->
        <li>

<label for="file">File : </label>
	<input name="file"  id="file" type="file"><br />
        <a href="<?php echo  base_url(). 'uploads/'.$file ?>" target="_blank"><?php echo $file; ?></a>
<!--              <a href="<?=  site_url('placement/manage_downloads/'.$file)?>" target="_blank"><?php echo $file; ?></a>-->
        <input type="hidden" value="<?php echo $file; ?>" name="previous_file" id="previous_file"/>
        </li>
        <li>
        <label for="link">Link : </label>
        <input name="link"  id="link" type="text" value="<?php echo $link; ?>">
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

