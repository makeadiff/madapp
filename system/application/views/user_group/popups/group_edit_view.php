<?php $this->load->view('layout/thickbox_header'); ?>

<script>
function update_group(id)
{
var permission = "";
$('#formEditor :checked').each(function(i, selected)
{ 
 permission+=($(selected).val()=="")?$(selected).val():$(selected).val()+",";
});
var groupname=$('#groupname').val();
if(groupname != '')
{ 
if(permission !='')
{
$.ajax({
		type: "POST",
		url: "<?=site_url('user_group/updategroup_name')?>"+'/'+id,
		data: "permission="+permission+"&groupname="+groupname,
		success: function(msg){
			$('#message').html(msg);
			window.parent.refresh_group();
		}
		});
		}else{
		alert("Select  Permission");
}
}else 
{
alert(" Enter Group Name");
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
<div style="float:left;"><h1>Edit Group</h1></div>
<div id="message"></div>
<div style="float:left; margin-top:20px;">
	<form id="formEditor" class="mainForm clear" action="" method="post" onsubmit="return false">
<fieldset class="clear">

	<div id="right-column">
	</div> 
	<div class="field clear"> 
		<label for="txtName">Group Name : </label>
		<input id="groupname" name="groupname"  type="text" value="<?php echo $name; ?>"/> 
</div>
		<div class="field clear"> 
		<label for="txtName">Permissions :</label>
			<?php 
			$j=0;
			foreach($permission as $row)
			{ 
			?>
			
		<div class="field clear"> 
		<label for="txtName"><?php echo $row['name']; ?></label>
		<?php 
		$a=0;
		for($j=0;$j<count($perm_id);$j++) {
			if($perm_id[$j]==$row['id'])
		{ $a=1;} }
		?>
		<input type="checkbox" value="<?php echo $row['id']; ?>" id="permission" name="permission[]" <?php if($a==1 ){ echo "checked"; }?>  />    
 </div>
           <?php } ?>
    <div class="field clear"> 
     	   <input style="margin-left:50px; margin-top:30px;" onclick="javascript:update_group('<?=$root_id?>');" class="button primary" type="submit" value="Submit"  />
    </div>
    </fieldset>
    </form>		
</div>

<?php $this->load->view('layout/thickbox_footer'); ?>