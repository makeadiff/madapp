<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />

<form id="formEditor" class="mainForm clear" action="<?=site_url('project/addproject')?>" method="post" style="width:500px;" onsubmit="return validate();" >
<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">

            <div class="field clear" style="width:600px;"> 
                        <label for="txtName">Project Name : </label>
                        <input id="name" name="name"  type="text" /> 
            </div>
          
            <div class="field clear" style="width:550px;"> 
     				<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
            </div>
            </fieldset>
            </form>
            
             <script>
     function validate()
     {
        if(document.getElementById("name").value == '')
          {		
              alert("Project Name Missing.");
              return false;
          }
	}
		</script>