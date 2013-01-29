<div id="content" class="clear">
<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>
            <!-- start page actions-->
            <a href="<?php echo site_url('placement/placement_view') ?>">< Placement Dashboard</a>
        	<div id="actions"> 
			<a href="<?php echo site_url('placement/popupaddevent')?>" class="thickbox button primary green popup" name="Add New Event">Add New Event</a>
<a href="<?php echo site_url('placement/popupaddfeedback')?>" class="thickbox button primary green popup" name="Add New Feedback">Add Feedback</a>
<a href="<?php echo site_url('placement/popupmarkattendance')?>" class="thickbox button primary green popup" name="Mark Attendance">Mark Attendance</a>
	
                </div>
			<!-- end page actions-->
	    </div>
		<div id="topOptions" class="clear">
		</div>
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName sortable">Event Name</th>
        <th class="colName sortable">Started On</th>
         <th class="colName sortable">Intern owner</th>
            <th class="colName sortable">City</th>
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
    <td class="colCheck1"><?php echo $i; ?></td>
    <td class="colName left"><?php echo $row['name']; ?></td>
    <td class="colName left"><?php echo $row['started_on']; ?></td>
    <td class="colName left"><?php
    foreach($user->result_array() as $userdetails):
    if($userdetails['id']==$row['user_id']){ echo $userdetails['name']; }    
    endforeach;
    ?>
        </td>
    <td class="colName left"><?php 
      foreach($city->result_array() as $citydetails):
    if($citydetails['id']==$row['city']){ echo $citydetails['name']; }    
    endforeach;    
    ?></td>
    <td class="colActions right"> 
    <a href="<?php echo site_url('placement/popupEdit_event/'.$row['id'])?>" class="thickbox icon popup edit">Edit</a>
    <a class="actionDelete icon delete confirm" href="<?php echo site_url('placement/ajax_deleteevent/'.$row['id']); ?>">Delete</a>
    </td>
</tr>

<?php }?>
</tbody>
</table>
</div>

</div>

