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
}
</script>
<?php
$details=$details->result_array();
foreach($details as $row) {
	$root_id=$row['id'];
	$name=$row['name'];
}
?>
<?php 
$permission=$permission->result_array();
$group_permission=$group_permission->result_array();
$i=0;
$perm_id = array();
foreach($group_permission as $roll) {
 	$perm_id[$i]=$roll['permission_id'];
	$i++;
}
?> 
<div id="message"></div>
<div style="float:left; margin-top:20px;">
	<form id="formEditor" class="mainForm clear" action=" <?=site_url('user_group/updategroup_name/'.$root_id)?>" method="post" onsubmit="return validate();" style="width:355px;">
<fieldset class="clear">
<ul class="form city-form">
		<li>
		<label for="txtName">Group Name : </label>
		<input id="groupname" name="groupname"  type="text" value="<?php echo $name; ?>"/> 	
        </li>
       	<li>
		<label for="txtName">Permissions :</label>
			<?php 
			$j=0;
			foreach($permission as $row)
			{ 
			?>
		</li>
        <li>	
		<label for="txtName"><?php echo $row['name']; ?></label>
		<?php 
		$a=0;
		for($j=0;$j<count($perm_id);$j++) {
			if($perm_id[$j]==$row['id'])
		{ $a=1;} }
		?>
		<input type="checkbox" value="<?php echo $row['id']; ?>" id="permission" name="permission[]" <?php if($a==1 ){ echo "checked"; }?>  />  
           <?php } ?>
           </li> 
           </ul>
           <ul>
           <li>
            <input id="btnSubmit"  class="button green" type="submit" value="Submit"  />
          <a href="<?=site_url('user_group/manageadd_group')?>" class="cancel-button">Cancel</a>
</li>
</ul>
    </fieldset>
    </form>		
</div>

