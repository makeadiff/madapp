<?php $this->load->view('layout/css',array('thickbox'=>true)); ?>

<form id="formEditor" class="mainForm clear" action="<?= site_url('user_group/addgroup_name')?>" method="post" onsubmit="return validate();" style="width:500px;">
	<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">
    
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
     	   <input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit"  />
    </div>
    </fieldset>
    </form>
    
     <script>
     function validate()
     {
        if(document.getElementById("groupname").value == '')
          {		
              alert("Groupname missing.");
              return false;
          }
       
	}
		</script>