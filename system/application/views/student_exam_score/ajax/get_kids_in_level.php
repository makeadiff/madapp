<select name="student_id[]" id="student_id" multiple="multiple" size="10">
<?php foreach($kids as $id=>$name) { ?>
<option value="<?php echo $id ?>"><?php echo $name ?></option>
<?php } ?>
</select>

<?php foreach($kids as $id=>$name) echo form_hidden('student_names['.$id.']', $name);
