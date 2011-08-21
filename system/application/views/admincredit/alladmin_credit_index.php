<div id="content" class="clear">
<!-- Main Begins -->
<div id="main" class="clear"> 

<div id="head" class="clear">
<h1><?php echo $title; ?></h1>

<div id="actions">
<?php //if($this->user_auth->get_permission('admincredit_add')) { ?>
<a href="<?php echo site_url('admincredit/index')?>" class="thickbox button green primary " name="Add Event">My Admin Credit</a>
<a href="<?php echo site_url('admincredit/addcredit')?>" class="thickbox button green primary popup" name="Add Event">Add Admin Credit</a>
<?php //} ?>
</div><br class="clear" />


</div><br />

<div id="kids_list">
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
    <th class="colName left sortable">Name</th>
	<th class="colName left sortable">Task</th>
    <th class="colStatus sortable">Credit</th>
    <th class="colName left sortable">Added On</th>
	<th class="colActions">Type</th>
	
</tr>
</thead>
<tbody>
<?php
$statusIco = '';
$statusText = '';
$count = 0;
$i=0;
foreach($details as $row) {	
	$count++;
	$i++;
	$shadeClass = 'even';
	if($count % 2) $shadeClass = 'odd';
?> 
<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colCheck1"><?php echo $i; ?></td>
    <td class="colName left"><?php if($this->session->userdata('id') == $row->userid){echo "<div style='color:#800000'>Me</div>";} else { echo $row->username;} ?></td>
    <td class="colName left"><?php echo $row->name; ?></td>
	<td class="colPosition"><?php echo $row->credit; ?></td>
    <td class="colName left"><?php echo $row->added_on; ?></td>
    <td class="colPosition"><?php echo $row->vertical; ?></td>
</tr>
<?php  }?>
</tbody>
</table>
</div>
<?php if(!$count) {
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
} ?>

</div>
<br /><br />
	
</div>
