<?php 
$norecord_flag = 1;
$shadeFlag = 0;
$shadeClass = '';
$statusIco = '';
$statusText = '';
$i=0;
$content = $details->result_array();
foreach($content as $row) {	
	$norecord_flag = 0;
	if($shadeFlag == 0) {
  		$shadeClass = 'even';
		$shadeFlag = 1;
  	} else {
  		$shadeClass = 'odd';		
		$shadeFlag = 0;
  	}
?>
<tr class="<?php echo $shadeClass; ?>">
    <td class="colCheck1"><?php echo $row['id']; ?></td>
    <td class="colName left"><?php echo stripslashes($row['name']); ?></td>
    <td class="colCount"><?php echo $row['email']; ?></td> 
    <td class="colStatus" style="text-align:left"><?php echo $row['phone'];  ?></td>
    <td class="colPosition" style="text-align:left"><?php echo $row['title'];  ?></td>
    <td class="colPosition"><?php echo $row['city_name'];  ?></td>
    <td class="colPosition"><?php echo $row['center_name'];  ?></td>
    <td class="colPosition"><?php echo ucfirst($row['user_type']);  ?></td>
    <td class="colActions right"> 
    <?php if($this->user_auth->get_permission('user_edit')) { ?><a href="<?php echo site_url('user/popupEditusers/'.$row['id'])?>" class="thickbox popup" style="cursor:pointer;background-image:url(<?php echo base_url(); ?>/images/ico/icoEdit.png)" id="group-<?php echo $row['id']; ?>" name="Edit User : <?= strtolower($row['name']) ?>"></a><?php } ?>
    <?php if($this->user_auth->get_permission('user_delete')) { ?><a class="actionDelete" href="javascript:deleteEntry('<?php echo $row['id']; ?>','<?php echo $currentPage; ?>');"></a><?php } ?>
    </td>
</tr>
<?php  } ?> 
