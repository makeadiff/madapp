<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>

<form id="formEditor" class="mainForm clear" action="<?=site_url('user/adduser')?>" method="post" onsubmit="return validate();" style="width:500px;" >
<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">

		<div class="field clear" style="width:500px;"> 
                        <label for="txtName">Name : </label>
                        <input id="name" name="name"  type="text" /> 
                      
            </div>
            
            <div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select Group:</label> 
            <select id="group" name="group"> 
            <option selected="selected" value="-1" >- choose action -</option> 
				<?php 
                $user_group = $user_group->result_array();
                foreach($user_group as $row)
                { 
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
                <?php } ?>
            </select>
            </div>
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Position : </label>
                        <input id="position" name="position"  type="text" /> 
                      
            </div>
			
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Email : </label>
                        <input id="email" name="email"  type="text" /> 
                      
            </div>
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Password : </label>
                        <input id="password" name="password"  type="password" /> 
                      
            </div>
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Password : </label>
                        <input id="cpassword" name="cpassword"  type="password" /> 
                      
            </div>
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Phone : </label>
                        <input id="phone" name="phone"  type="text" /> 
                      
            </div>
            
			<div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select city:</label> 
            <select id="city" name="city" > 
            <option selected="selected" value="-1" >- choose action -</option> 
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
            <label for="selBulkActions">Select center:</label> 
            <select id="center" name="center"> 
            <option selected="selected" value="-1" >- choose action -</option> 
				<?php 
                $center = $center->result_array();
                foreach($center as $row)
                {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
                <?php } ?>
            </select>
            </div>
            
            
            <div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select project:</label> 
            <select id="project" name="project"> 
            <option selected="selected" >- choose action -</option> 
				<?php 
                $project = $project->result_array();
                foreach($project as $row)
                {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
                <?php } ?>
            </select>
            </div>
           
             <div class="field clear" style="width:500px;"> 
                        <label for="txtName">User Type : </label>
                        <input id="type" name="type"  type="text" /> 
                      
            </div>
            
            <div class="field clear" style="width:550px;"> 
     				<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
            </div>
            </fieldset>
            </form>
  
  <script language="javascript">
function validate()
{
	  if(document.getElementById("name").value == '')
          {
              alert("Name Missing.");
              return false;
          }
	  if(document.getElementById("group").value == '-1')
          {
              alert("Select Group.");
              return false;
          }
	  if(document.getElementById("email").value == '')
          {
              alert("Select Email.");
              return false;
          }
		  
		  

	   if(document.getElementById("password").value == '')
          {
              alert("Password Missing.");
              return false;
          }
       if(document.getElementById("cpassword").value == '')
          {
              alert("Retype Password.");
              return false;
          }
       
		  
		  if(document.getElementById("city").value == '-1')
          {
              alert("Select City.");
              return false;
          }
		  if(document.getElementById("center").value == '-1')
          {
              alert("Select Center.");
              return false;
          }
if(document.getElementById("password").value != document.getElementById("cpassword").value)
          {
              alert("Password Mismatch.");
              return false;
          }

}
</script>          