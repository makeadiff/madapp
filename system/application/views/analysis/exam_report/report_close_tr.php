<td><?php echo $attendance;?></td>
<td><?php if(sizeof($marks) > 0) {
	$sum = 0;
	foreach($marks as $row) { 
		$sum += $row->mark;
	} 
	echo $sum;
} else {
	echo '&nbsp;';
} ?></td>
</tr>
