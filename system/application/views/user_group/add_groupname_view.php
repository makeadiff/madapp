<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/thickbox.css">

<script>
	function deleteEntry(entryId)
	{
		var bool = confirm("Are you sure you want to delete this?")
		if(bool)
		{
			$.ajax({
			type : "POST",
			url  : "<?= site_url('user_group/ajax_deletegroup') ?>",
			data : 'entry_id='+entryId,
			
			success : function(data)
			{		
			 	document.location.reload();
			}
			
			});
		}
	}	
</script>

<div style="height:20px;padding-top: 5px;">
<div id="loading" name="loading" style="display: none;">
    <img src="<?php echo base_url()?>images/ico/loading.gif" height="25" width="25" style="border: none;margin-left: 300px;" /> loading...
</div>
</div>
<div id="updateDiv" >



<div id="content" class="clear">

<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>

            <!-- start page actions-->
        	<div id="actions"> 
			<a href="<?php site_url('user_group/popupaddgroup')?>" class="thickbox button primary popup" id="example" name="Add New Group">Add New Group</a>
			</div>
			<!-- end page actions-->

	    </div>

		<div id="topOptions" class="clear">

		</div>

<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
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
//
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
    <a href="<?php echo site_url('user_group/popupEdit_group/'.$row['id'])?>" class="thickbox icon popup edit" name="<strong>Edit Group : <?= strtolower($row['name']) ?></strong>">Edit</a> 
    <a class="actionDelete icon delete" href="javascript:deleteEntry('<?php echo $row['id']; ?>')">Delete</a>
    </td>
</tr>

<?php }?>
</tbody>
</table>

<?php if($norecord_flag == 1) 
{ 
   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
}    ?>
</div>

</div>

</div>
</div>

