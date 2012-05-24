<td>
<?php 
if(sizeof($marks) > 0) {
	foreach($marks as $row) { 
		echo $row->name.':'.$row->mark . '<br />';
	} 
} else {
	echo '&nbsp;';
} ?>
</td>
