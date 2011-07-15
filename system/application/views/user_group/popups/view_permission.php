<?php $this->load->view('layout/thickbox_header'); ?>

<?php
$details=$details->result_array();
foreach($details as $row) {
	$root_id=$row['id'];
	$name=$row['name'];
}
$permission=$permission->result_array();

$group_permission=$group_permission->result_array();
$i=0;
foreach($group_permission as $roll)
	{
 	$perm_id[$i]=$roll['permission_id'];
	$i++;
	}
?>

<form id="formEditor" class="mainForm clear" action="<?php echo site_url('user_group/updategroup_name')?>" method="post">
	<fieldset class="clear">
    
		
        <div class="field clear"> 
           <h2>Group Name : <?php echo $name; ?></h2>
    	</div>
			<div  class="field clear"> 
          <p style="clear:both; ">
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
		  	 if(isset($perm_id[$j]) and $perm_id[$j]==$row['id'])
		    { $a=1;} 
		    }
		    ?>
          <?php if($a==1) {?>
          <img src="<?php echo base_url(); ?>/images/ico/tick-icon.png" style="border:none; float:left;"/>
           <?php } ?>
			</div><br />
        
           <?php } ?>
            </p>
           </div>
  
   </fieldset>
   </form>		
   			