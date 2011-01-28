<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<?php
$details=$content->result_array();
foreach($details as $row)
{
$root_id=$row['id'];
$name=$row['name'];
}
?>
	<form id="formEditor" class="mainForm clear"action="<?=site_url('permission/edit_permission')?>" method="post" style="width:500px;" onsubmit="return validate();">
	<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">
    
	<div class="field clear" style="width:600px;"> 
           <label for="txtName">Permission Name : </label>
           <input id="permission" name="permission"  type="text" value="<?php echo $name ?>" /> 
    </div>
    <div class="field clear" style="width:550px;"> 
    	   <input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
     	   <input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
    </div>
    </fieldset>
    </form>
    
     <script>
     function validate()
     {
        if(document.getElementById("permission").value == '')
          {		
              alert("Permission Name Missing.");
              return false;
          }
	}
		</script>