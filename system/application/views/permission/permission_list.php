<script>
function add_permission()
{
	$.ajax({
		type: "POST",
		url: "<?= site_url('permission/popupAddPermission')?>",
		success: function(msg){
			$('#sidebar').html(msg);
		}
		});
}

function edit_permission(id)
{
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('permission/popupEdit_permission')?>"+'/'+id,
		success: function(msg){
			$('#sidebar').html(msg);
		}
		});
}
</script>


<div id="content" class="clear">

<!-- Main Begins -->
<div id="main" class="clear">
<div id="head" class="clear">
<h1><?php echo $title; ?></h1>

<!-- start page actions-->
<div id="actions"> 
<a href="javascript:add_permission();" class=" button primary popup" name="Add Permission">Add Permission</a>
</div><br />
<!-- end page actions-->
</div>

<table cellpadding="0" style="margin-top:10px;"  cellspacing="0" class="clear data-table" id="tableItems">
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
    <a href="javascript:edit_permission('<?=$row['id']?>');" class="popup icon edit"?></strong>">Edit</a>
    <a class="actionDelete icon delete" href="javascript:deleteEntry('<?php echo $row['id']; ?>','<?php echo $currentPage; ?>');">Delete</a>
    </td>
</tr>

<?php }?>
</tbody>
</table>

<?php if($norecord_flag == 1) 
{ 
	  if($currentPage != '0'): ?>
      <script>
      	 get_groupList('<?php echo $currentPage-1; ?>');
	   </script>
<?php else: 
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
	  endif;
}    ?>



</div>



</div>
