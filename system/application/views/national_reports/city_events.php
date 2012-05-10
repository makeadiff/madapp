<tr>
<td><?php echo $data['city_name']; ?></td>
<td><?php echo $data['totalvolunteers']; ?></td>
<td><?php echo $data['no_process_training']; ?></td>
<?php if($data['process_training_Attendance'] < $data['process_training_user_events']) { ?>
<td style="color:#F00"><?php echo $data['process_training_Attendance']; ?></td>
<?php } else { ?>
<td><?php echo $data['process_training_Attendance']; ?></td>
<?php } ?>
<td></td>
<td></td>
<td><?php echo $data['tt_count']; ?></td>
<?php if($data['tt_count_Attendance'] < $data['tt_user_events']){ ?>
<td style="color:#F00"><?php echo $data['tt_count_Attendance']; ?></td>
<?php } else{ ?>
<td><?php echo $data['tt_count_Attendance']; ?></td>
<?php } ?>
<td><?php echo $data['cct_count']; ?></td>
<td></td>
<td></td>
<td></td>
<td></td>

</tr>
