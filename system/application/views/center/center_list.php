<script type="text/javascript">
tb_init('a.thickbox, input.thickbox');

function triggerSearch() {
	q = $('#searchQuery').val();
	get_groupList('0',q);
}

$(document).ready(function(){
	$('#example').each(function(){
		var url = $(this).attr('href') + '?TB_iframe=true&height=400&width=700';

		$(this).attr('href', url);
	});
	
	$('.groupmanage').each(function(){
		var url = $(this).attr('href') + '?TB_iframe=true&height=430&width=850';

		$(this).attr('href', url);
	});
	
	$('.group').each(function(){
		var url = $(this).attr('href') + '?TB_iframe=true&height=400&width=700';

		$(this).attr('href', url);
	});
});  
</script>

<div id="content" class="clear">

<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>

            <?php if($this->user_auth->get_permission('center_add')) { ?>
        	<div id="actions"> 
			<a href="<?php echo site_url('center/popupaddCneter')?>" class="thickbox button primary" id="example" name="Add New Center">Add New Center</a>
			</div>
			<?php } ?>

	    </div>

		<div id="topOptions" class="clear">

		</div>

<table id="tableItems" class="clear" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<!--<th class="colCheck"> <input id="cbSelectAll" type="checkbox" onclick="toggleChecked(this.checked)" /></th>-->
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Center Name</th>
    
    <th class="colStatus sortable">City</th>
    <th class="colStatus">View Levels</th>
    <th class="colStatus">View Batches</th>
    <th class="colStatus">Center Head</th>
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
<tr class="<?php echo $shadeClass; ?>" id="group">
<!--    <td class="colCheck"> <input name="cbSelect[]" type="checkbox" value="<?php echo $row['id']; ?>"/></td> -->    
	<td class="colCheck1"><?php echo $row['id']; ?></td>
	<td class="colName left"><?php echo $row['name']; ?></td>
	<td class="colCount"><?php echo $row['city_name']; ?></td> 
	<td class="colCount"><a href="<?php echo site_url('level/index/center/'.$row['id']) ?>">Levels</a></td>
	<td class="colCount"><a href="<?php echo site_url('batch/index/center/'.$row['id']) ?>">Batches</a></td>
	<td class="colStatus" style="text-align:left"><?php echo $row['user_name'];?></td>
	<!--<td class="colPosition"></td>-->
	<td class="colActions right">
	<a href="<?php echo site_url('center/popupEdit_center/'.$row['id'])?>" class="thickbox" style="cursor:pointer;background-image:url(<?php echo base_url(); ?>/images/ico/icoEdit.png)" id="group-<?php echo $row['id']; ?>" class="group" name="<strong>Edit Center : <?= strtolower($row['name']) ?></strong>">Edit</a> 
	<a class="actionDelete" href="javascript:deleteEntry('<?php echo $row['id']; ?>','<?php echo $currentPage; ?>')">Delete</a></td>
</tr>

<?php }?>
</tbody>
</table>

<?php if($norecord_flag == 1) 
{ 
	  if($currentPage != '0'): ?>
       <script>
      	 get_centerlist('<?php echo $currentPage-1; ?>');
	   </script>
<?php else: 
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
	  endif;
}    ?>



</div>


</div>
