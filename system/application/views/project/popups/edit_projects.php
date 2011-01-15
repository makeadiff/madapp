<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />

<?php
$details=$details->result_array();
foreach($details as $row)
{
$root_id=$row['id'];
$name=$row['name'];
//$added_on=$row['added_on'];
}

?>
<form id="formEditor" class="mainForm clear" action="<?=site_url('project/update_project')?>" method="post" style="width:500px;" >
<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">
            <div class="field clear" style="width:600px;"> 
                        <label for="txtName">Name : </label>
                        <input id="name" name="name"  type="text"  value="<?php echo $name; ?>"/> 
                      
            </div>
            <div class="field clear" style="width:550px;">
             		<input type="hidden" value="<?php echo $root_id; ?>"  id="rootId" name="rootId" />
     				<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
            </div>
            </fieldset>
            </form>