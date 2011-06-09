<table id="tableItems" class="clear data-table" style="margin-top:45px;" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable" style="width:375px; text-align:center">Group Name</th>
    <th class="colName left sortable" style="width:375px; text-align:center">Permissions</th>
    <th class="colActions"  style="width:225px;">Actions</th>
</tr>
</thead>
<tbody>
<?php 
$norecord_flag = 1;
$shadeFlag = 0;
$shadeClass = ''; 
$statusIco = '';
$statusText = '';
$content = $details->result_array();
$i=0;
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
<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colCheck1"><?php echo $i; ?></a></td>
    <td class="colName left" style="text-align:center"><?php echo strtolower($row['name']); ?></a></td>
    <td class="colName left" style="text-align:center"><img src="<?php echo base_url(); ?>/images/ico/ico_key.gif" style="border:none;"/> <a href="<?=site_url('user_group/view_permission/'.$row['id']) ?> " class="thickbox" id="groupmanage-<?php echo $row['id']; ?>" name="<strong>Permissions of <?= strtolower($row['name']) ?></strong>"> View Permissions</a></td>
    <td class="colActions right"> 
   <a href="javascript:edit_group('<?=$row['id']?>');" class="icon edit">Edit</a> 
    <a class="actionDelete icon delete" href="javascript:deleteEntry('<?php echo $row['id']; ?>')">Delete</a>
    </td>
</tr>
<?php }?>
</tbody>
</table>