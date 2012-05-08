<tr>
<td><?php echo $data['city_name']; ?></td>
<td><?php echo $data['totalchild']; ?></td>
<td></td>
<td><?php echo $data['maddlevels']; ?></td>
<td></td>
<td><?php echo $data['totalvolunteers']; ?></td>
<td></td>
<td></td>
<td><?php echo $data['totalmaddclasses']; ?></td>
<?php if( $data['class_substitute_count'] > $data['substitute_madd_percentage']){?>
<td style="color:#F00"><?php  echo $data['class_substitute_count']; ?></td><?php } else {?>
<td><?php  echo $data['class_substitute_count']; ?></td>
<?php } ?>
<?php if($data['class_missed_count'] > $data['missed_madd_percentage']){ ?>
<td style="color:#F00"><?php  echo $data['class_missed_count']; ?></td>
<?php } else { ?>
<td><?php  echo $data['class_missed_count']; ?></td>
<?php } ?>
<?php if($data['class_cancelled_count'] > $data['cancelled_madd_percentage']){ ?>
<td style="color:#F00"><?php  echo $data['class_cancelled_count']; ?></td>
<?php } else { ?>
<td><?php  echo $data['class_cancelled_count']; ?></td>
<?php } ?>
<td></td>

</tr>
