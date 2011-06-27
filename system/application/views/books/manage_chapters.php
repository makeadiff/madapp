<div id="content" class="clear">

<!-- Main Begins -->
<div id="main" class="clear"> 
	<div id="head" class="clear">
		<div id="actions">
		<?php if($this->user_auth->get_permission('chapters_add')) { ?>
		<a href="<?php echo site_url('books/popupadd_lesson'); ?>" style="margin-bottom:10px;" class="button primary popup">Add Lessons</a>
		<?php } ?>
		</div>
	</div>
<div id="kids_list">
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName sortable">Lesson Name</th>
    <th class="colName sortable">Book Name</th>
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
    <td class="colName"><?php echo $row['name']; ?></td>
    <td class="colName"><?php echo $row['book_name']; ?></td>
    
    <td class="colActions right"> 
    <?php if($this->user_auth->get_permission('chapters_edit')) { ?><a href="<?php echo site_url('books/popupEdit_lesson/'.$row['id']); ?>" class="edit icon popup">Edit</a><?php } ?>
    <?php if($this->user_auth->get_permission('chapters_delete')) { ?><a class="actionDelete icon delete confirm" href="<?php echo site_url('books/ajax_deletelesson/'.$row['id']); ?>">Delete</a><?php } ?>
   
    </td>
</tr>

<?php  }?>
</tbody>
</table>
</div>
<?php if($norecord_flag == 1) 
   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
?>


</div>
</div>
