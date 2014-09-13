<table id="tableItems" class="clear data-table" style="margin-top:45px;" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName sortable">Group Name</th>
    <th class="colName sortable">Permissions</th>
    <th class="colActions">Actions</th>
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
    <td class="colName"><?php echo $row['name']; ?></a></td>
    <td class="colName"><img src="<?php echo base_url(); ?>/images/ico/ico_key.gif" /><a href="<?php echo site_url('user_group/view_permission/'.$row['id']) ?> " class="thickbox popup" name="Permissions of <?php echo $row['name']);?>"> View Permissions</a></td>
    <td class="colActions right"> 
   <a href="javascript:edit_group('<?=$row['id']?>');" class="icon edit">Edit</a> 
    <a class="actionDelete icon delete" href="javascript:deleteEntry('<?php echo $row['id']; ?>')">Delete</a>
    </td>
</tr>
<?php }?>
</tbody>
</table>