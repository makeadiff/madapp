<div id="content" class="clear">
<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>
            <!-- start page actions-->
        	<div id="actions"> 
<a href="<?php echo site_url('exam/add_exam')?>" class="popup button green primary" name="Add New Exam">Add New Exam</a>
</div>
	<!-- end page actions-->

</div>

<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">#</th>
	<th class="colName left sortable">Exam Name</th>
    <th class="colName left sortable">Details</th>
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
    <td class="colName left"><?php echo $row['name']; ?></a></td>
	<td class="colName left"><a href="<?php echo site_url('exam/view_exam_details/'.$row['id']) ?> " class="popup primary" id="groupmanage-<?php echo $row['id']; ?>" name="Details of <?= strtolower($row['name']) ?>"> View Details</a></td>
	<td class="colName left"><a href="<?php echo site_url('exam/delete/'.$row['id']) ?>" class="confirm" title="Delete '<?php echo $row['name']; ?>' Exam">Delete</a></td>
</tr>

<?php }?>
</tbody>
</table><br />

<?php if($norecord_flag == 1) {
	   echo "<div class='no-records'>No records found</div>";
} ?>
</div>

</div>
