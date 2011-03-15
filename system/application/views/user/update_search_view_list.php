<?php 
$norecord_flag = 1;
$shadeFlag = 0;
$shadeClass = '';
$statusIco = '';
$statusText = '';
$i=0;
$content = $details->result_array();
foreach($content as $row) {	
	$i++;
	$norecord_flag = 0;
	if($shadeFlag == 0) {
  		$shadeClass = 'even';
		$shadeFlag = 1;
  	} else if($shadeFlag == 1) {
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
    <td class="colPosition" style="text-align:left"><?php echo $row['credit'];  ?></td>
    <td class="colPosition"><?php echo $row['center_name'];  ?></td>
    <td class="colPosition"><?php echo $row['user_type'];  ?></td>
    <td class="colPosition"><?php if($row['photo']) { ?><img src="<?php echo base_url().'pictures/'.$row['photo']; ?>" width="50" height="50" /><?php } ?></td>
    <td class="colActions right">
    <?php if($this->user_auth->get_permission('user_edit')) { ?><a href="<?php echo site_url('user/popupEditusers/'.$row['id'])?>" class="thickbox popup" style="cursor:pointer;background-image:url(<?php echo base_url(); ?>/images/ico/icoEdit.png)" id="group-<?php echo $row['id']; ?>" name="Edit User : <?= strtolower($row['name']) ?>"></a><?php } ?>
    <?php if($this->user_auth->get_permission('user_delete')) { ?><a class="actionDelete" href="javascript:deleteEntry('<?php echo $row['id']; ?>','1');"></a><?php } ?>
	</td>
</tr>
<?php  } ?> 
