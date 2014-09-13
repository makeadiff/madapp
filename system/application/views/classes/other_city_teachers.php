<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript">
function get_teachers(city_id)
{
	if(city_id == -1) $('#shown').hide();
	else {
		$('#shown').show();
	
		$.ajax({
			type: "POST",
			url: "<?php echo site_url('classes/city_teachers')?>"+'/'+city_id+'/<?php echo $flag ?>',
			success: function(msg){$('#shown').html(msg);}
		});
	}
}
</script>
<ul class="form city-form">
<li><label for="date">City: </label>
<select id="user" name="user" onchange="javascript:get_teachers(this.value)"> 
<option selected="selected" value="-1" >- Choose -</option> 
<?php foreach($cities as $row){ ?>
	<option value="<?php echo $row->id?>"><?php echo $row->name?></option> 
<?php } ?>
</select>
</li>
<li>
<div id="shown">

</div>
</li>
 </ul>
