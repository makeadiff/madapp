<?php $this->load->view('layout/thickbox_header'); ?>

<script>
function insert_group(id)
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
		url: "<?= site_url('user_group/addgroup_name')?>",
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

<div style="float:left;"><h1>Add Group</h1></div>
<div id="message"></div>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="" method="post" onsubmit="return false" style="width:500px;">
	<fieldset class="clear">
		<div id="right-column">
        </div> 
        <div class="field clear" style="width:600px;"> 
           <label for="txtName">Group Name : </label>
           <input id="groupname" name="groupname"  type="text" /> 
    </div>
			<div class="field clear" style="width:600px; "> 
            <label for="txtName">Permissions :</label>
				<?php 
                $permission=$permission->result_array();
                foreach($permission as $row)
                {
                ?>
            <div class="field clear" style="width:600px; margin-left:100px;"> 
           <label for="txtName"><?php echo $row['name']; ?></label>
           <input type="checkbox" value="<?php echo $row['id']; ?>" id="permission" name="permission[]" /> 
    </div>
<?php } ?>
	</div>
    <div class="field clear" style="width:550px;"> 
    <input style="margin-left:50px; margin-top:50px;" id="btnSubmit" onclick="javascript:insert_group()" class="button primary" type="submit" value="Submit"  />
    </div>
    </fieldset>
    </form>
    </div>
<?php $this->load->view('layout/thickbox_footer'); ?>