<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript">
function update_substitue(user_id)
{
		$.ajax({
            type: "POST",
           	url: "<?= site_url('classes/update_city_teachers')?>"+'/'+user_id,
           	 success: function(msg){
			 	<?php if($flag==0){ ?>
           		$('#sustitue0').html(msg);
				<?php }elseif($flag==1) { ?>
				$('#sustitue1').html(msg);
				<?php } else {?>
				$('#sustitue2').html(msg);
				<?php } ?>
           		 }
            	});
}
</script>

<li><label for="date">Teachers: </label>
<select id="type" name="type"  onchange="javascript:update_substitue(this.value)"> 
<option selected="selected" value="-1" >- Choose -</option> 
    <?php foreach($users as $row){ ?>
	<option value="<?=$row->id?>"><?=$row->name?></option> 
	 <?php } ?>
</select>
</li>