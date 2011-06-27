<div id="content" class="clear">

<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
            <!-- start page actions-->
        	<div id="actions"> 
			<a href="<?php echo site_url('project/popupaddproject')?>" style="margin-bottom:10px;" class="thickbox button primary popup" id="example" name="Add Projects">Add Project</a>
			</div>
			<!-- end page actions-->

	    </div>
	    
<div id="project_list">
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th  class="colName sortable">Name</th>
    <th  class="colStatus sortable" style="width:150px;">Added On</th>
    <th  class="colActions">Actions</th>
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
    <td class="colCheck1"><?php echo $i; ?></td>
    <td class="colName"><?php echo $row['name']; ?></td>
    <td class="colCount"><?php echo $row['added_on']; ?></td> 
    <td class="colActions right"> 
    <a href="<?php echo site_url('project/popupEdit_project/'.$row['id'])?>" class="thickbox popup icon edit" name="Edit Project">Edit</a> 
    <a class="actionDelete icon delete confirm" href="<?php echo site_url('project/ajax_deleteproject/'.$row['id']) ?>">Delete</a>
    </td>
</tr>

<?php  }?>
</tbody>
</table>
</div>

<?php if($norecord_flag == 1) {
   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- No Records Found -</div>";
} ?>

</div>

</div>
