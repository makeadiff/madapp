<li><label for="users-<?php echo $user_id; ?>"><?php echo $name; ?></label>
<input type="checkbox" class="users" value="<?php echo $user_id; ?>" id="users-<?php echo $user_id; ?>" <?php if($selected) echo 'checked="checked"'; ?> name="users[]" /></li>
