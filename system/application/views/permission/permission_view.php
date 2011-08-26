<div id="content" class="clear">

<!-- Main Begins -->
<div id="main" class="clear">
<div id="head" class="clear">
<h1><?php echo $title; ?></h1>

<!-- start page actions-->
<div id="actions"> 
<a href="<?php echo site_url('permission/popupAddPermission')?>" class="thickbox button green primary popup" style="margin-bottom:10px;" name="Add Permission">Add Permission</a>
</div><br />
<!-- end page actions-->
</div>

<table cellpadding="0"  cellspacing="0" class="clear data-table" id="tableItems">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Permission</th>
    <th class="colActions">Actions</th>
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
//
$content = $details->result_array();
//
foreach($content as $row)
{
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
    <td class="colCheck1"><?php echo $row['id']; ?></td>
    <td class="colName left"><?php echo $row['name']; ?></td>
    <td class="colActions right"> 
    <a href="<?php echo site_url('permission/popupEdit_permission/'.$row['id'])?>" class="thickbox popup icon edit">Edit</a>
    <a class="actionDelete icon delete confirm" href="<?php echo site_url('permission/ajax_deletepermission/'.$row['id']) ?>">Delete</a>
    </td>
</tr>

<?php }?>
</tbody>
</table>

<?php if($norecord_flag == 1) 
{ 
	   echo "<div class='no-records'>- no records found -</div>";
} ?>

</div>
</div>
