<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>


<form id="formEditor" class="mainForm clear" action="<?=site_url('admin/addCenter')?>" method="post" style="width:500px;" >
<fieldset class="clear" style="margin-top:70px;width:500px;margin-left:-30px;">
<div class="field clear" style="width:500px;">
<label for="selBulkActions">Select city:</label> 
<select id="city" name="city" > 
<option selected="selected" >- choose action -</option> 
<?php 
$details = $details->result_array();
foreach($details as $row)
{
?>
<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
<?php } ?>
</select>
</div>

<div class="field clear" style="width:500px;">
<label for="selBulkActions">Select Head:</label> 
<select id="user_id" name="user_id"> 
<option selected="selected" >- choose action -</option> 
<?php 
$user_name = $user_name->result_array();
foreach($user_name as $row) {
?>
    <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
<?php } ?>
</select>
</div>

<div class="field clear" style="width:500px;"> 
    <label for="txtName">Center : </label>
    <input id="center" name="center"  type="text" /> 
</div>

<div class="field clear" style="width:550px;"> 
	<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
</div>
</fieldset>
</form>