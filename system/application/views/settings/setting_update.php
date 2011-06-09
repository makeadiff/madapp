<table id="main" class="data-table tablesorter info-box-table">
<thead><tr><th style="width:500px;">Name</th><th colspan="2">Action</th></tr></thead>

<?php foreach($all_settings as $result) { ?>
<tr><td><?php echo $result->name;
	?></td>
<td><a href="javascript:edit_settings('<?=$result->id?>');" class="edit with-icon">Edit</a>&nbsp; <a href="javascript:deleteEntry('<?=$result->id?>');" class="delete with-icon">Delete</a></td>
</tr>
<?php } ?>

</table>