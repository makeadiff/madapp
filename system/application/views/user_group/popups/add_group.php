<?php $this->load->view('layout/thickbox_header'); ?>
<script>
function validate(id)
{
if(document.getElementById("groupname").value == '')
          {		
              alert("GroupName Missing.");
			  document.getElementById('groupname').focus();
              return false;
          }

}
</script>
<div style="float:left;"><h1>Add Group</h1></div>
<div id="message"></div>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="<?= site_url('user_group/addgroup_name')?>" method="post" onsubmit="return validate();" style="width:355px;">
	<fieldset class="clear">
		<ul class="form city-form">
		<li>
           <label for="txtName">Group Name : </label>
           <input id="groupname" name="groupname"  type="text" /> 
		</li>
   		<li>
        <label for="txtName">Permissions :</label>
		<?php $permission=$permission->result_array();
              foreach($permission as $row){?>
         </li>
         <li>
         <label for="txtName"><?php echo $row['name']; ?></label>
         <input type="checkbox" value="<?php echo $row['id']; ?>" id="permission" name="permission[]" /> 
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
