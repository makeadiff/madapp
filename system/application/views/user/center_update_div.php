<span>Center:</span>
<select class="dropdown" id="center" name="center">
<option value="-1">- Select -</option>
<?php $centers=$centers->result_array(); ?>
<?php foreach($centers as $row)
{
	$center=$row['name'];
	$center_id=$row['id'];
?>
<option value="<?php echo $center_id; ?>"><?php echo $center; ?></option>
<?php }?>
</select>
<?php if(isset($center) && $center == '1') { ?>
<img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" style="margin-left: -15px;" />
<?php } ?>
           
