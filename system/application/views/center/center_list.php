<div id="content" class="clear">

<!-- Main Begins -->
<div id="main" class="clear">
<div id="head" class="clear">
	<h1><?php echo $title; ?></h1>

	<?php if($this->user_auth->get_permission('center_add')) { ?>
	<div id="actions"> 
	<a href="<?php echo site_url('center/popupaddCenter')?>" class="thickbox button primary popup" id="example" name="Add New Center">Add New Center</a>
	</div>
	<?php } ?>

</div>

<table id="tableItems" class="clear info-box-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Center Name</th>
    
    <th class="colStatus sortable">City</th>
    <th class="colStatus">Center Head</th>
   <th class="colActions">Actions</th>
</tr>
</thead>
<tbody>

<?php 
$shadeClass = 'even'; 
$statusIco = '';
$statusText = '';
foreach($details as $row) {

	if($shadeClass == 'odd') $shadeClass = 'even';
	else $shadeClass = 'odd';
?> 
<tr class="<?php echo $shadeClass; ?>" id="group">
	<td class="colCheck1"><?php echo $row->id; ?></td>
	<td class="colName left"><?php echo $row->name; 
	if($row->problem_count) print "<span class='warning icon'>!</span>";
	?><div class="center-info info-box"><ul><li><?php
		print implode('</li><li>', $row->information);
	?></li></ul></div></td>
	<td class="colCount"><?php echo $row->city_name; ?></td> 
	<td class="colStatus" style="text-align:left"><?php echo $row->user_name;?></td>
	<td class="colActions right">
	<?php if($this->user_auth->get_permission('center_edit')) { ?><a href="<?php echo site_url('center/manage/'.$row->id); ?>" class="with-icon edit" name="Manage Center: <?php echo $row->name ?>">Manage</a><?php } ?>
	</td>
</tr>

<?php }?>
</tbody>
</table>

<?php if(!count($details)) { ?>
<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>
<?php } ?>
</div>
</div>


</div>

</div>
