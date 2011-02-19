<?php 
$norecord_flag = 1;
$shadeFlag = 0;
$shadeClass = '';
$statusIco = '';
$statusText = '';
$i=0;
$content = $details->result_array();
foreach($content as $row)
{	$i++;
$norecord_flag = 0;
	if($shadeFlag == 0)
	  {
  		$shadeClass = 'even';
		$shadeFlag = 1;
  	  }
	else if($shadeFlag == 1)
	  {
  		$shadeClass = 'odd';		
			$shadeFlag = 0;
  	  }
?>
<tr class="<?php echo $shadeClass; ?>">
    <td class="colCheck1"><a href="#"><?php echo $row['id']; ?></a></td>
    <td class="colName left"> <a href="#"><?php echo $row['name']; ?></a></td>
    <td class="colCount"><a href=""> <?php echo $row['email']; ?></a></td> 
    <td class="colStatus" style="text-align:left"><?php echo $row['phone'];  ?></td>
    <td class="colPosition" style="text-align:left"><?php echo $row['title'];  ?></td>
    <td class="colPosition"><?php echo $row['city_name'];  ?></td>
    <td class="colPosition"><?php echo $row['center_name'];  ?></td>
    <td class="colPosition"><?php echo $row['user_type'];  ?></td>
</tr>
<?php  } ?> 
