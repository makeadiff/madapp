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
}
?>


<?php 
$permission=$permission->result_array();

$group_permission=$group_permission->result_array();
$i=0;
foreach($group_permission as $roll)
	{
 	$perm_id[$i]=$roll['permission_id'];
	$i++;
	}
?> 

         <form id="formEditor" class="mainForm clear" action="<?= site_url('user_group/updategroup_name')?>" method="post" style="width:500px;">
	<fieldset class="clear" style="margin-top:50px;width:500px;margin-left:-30px;">
    
		
        <div class="field clear" style="width:600px;"> 
           <label for="txtName">Group Name : <?php echo $name; ?></label>
    </div>
			<div  class="field clear" style="width:680px;"> 
            <label for="txtName">Permissions :</label>
				<?php 
                $j=0;
                foreach($permission as $row)
                { 
                ?>
                
            <div class="field clear" style="width:600px;"> 
          <label for="txtName"><?php echo $row['name']; ?></label>
           <?php 
		    $a=0;
		   	for($j=0;$j<count($perm_id);$j++) {
		  	 if($perm_id[$j]==$row['id'])
		    { $a=1;} }
		    ?>
          <?php if($a==1) {?>
          <img src="<?php echo base_url(); ?>/images/ico/tick-icon.png" style="border:none;"/>
           <?php } ?>
</div></div>
           <?php } ?>
   
   </fieldset>
   </form>		
   			