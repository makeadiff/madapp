<?php foreach($events as $row) { ?>
<?php $id=$row->id ;}?>
<li><label for="users-<?php echo $id; ?>"><?php echo $name; ?></label>
<input type="hidden" value="<?php echo $id ;?>" name="event" id="event" />
<input type="checkbox"  value="<?php echo $user_id; ?>" id="users-<?php echo $user_id; ?>" name="users[]" />
</li>
