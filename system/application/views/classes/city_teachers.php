<script type="text/javascript">
function update_substitue(user_id) {
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('classes/update_city_teachers')?>"+"/"+user_id+"/<?php echo $flag ?>",
		success: function(msg) {
			var name = $("#other_city_<?php echo $flag ?>").attr("name");
			msg = msg.replace(/substitute_id\[.+\]/, name);
			$('#substitute_<?php echo $flag ?>').html(msg);
		}
	});
}
</script>

<li><label for="date">Teachers: </label>
<select id="type" name="type" onchange="javascript:update_substitue(this.value)"> 
<option selected="selected" value="-1" >- Choose -</option> 
    <?php foreach($users as $row){ ?>
	<option value="<?php echo $row->id?>"><?php echo $row->name?></option> 
	 <?php } ?>
</select>
</li>