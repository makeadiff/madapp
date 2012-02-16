<select name="level_id" id="level_id" onchange="getKids();">
<option value="0">Select Level</option>
<?php foreach($levels as $l) { ?>
<option value="<?php echo $l->id ?>"><?php echo $l->name ?></option>
<?php } ?>
</select>
