<div id="content" class="clear">
<!-- Main Begins -->
<div id="main" class="clear"> 
	<div id="head" class="clear">
   
		<h1><?php echo $title; ?></h1>

		<div id="actions">
		<?php if($this->user_auth->get_permission('books_add')) { ?>
		<a href="<?php echo site_url('books/popupaddbooks') ?>" style="margin-bottom:10px;" class="popup button primary">Add Book</a>
		<?php } ?>
		</div>
	</div>
<div>
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName sortable">Name</th>
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
    
    <td class="colActions"> 
    <?php if($this->user_auth->get_permission('books_edit')) { ?><a href="<?php echo site_url('books/popupEdit_books/'.$row['id']); ?>" class="popup icon edit">Edit</a><?php } ?>
    <?php if($this->user_auth->get_permission('books_delete')) { ?><a class="actionDelete icon delete confirm" href="<?php echo site_url('books/ajax_deletebook/'.$row['id']); ?>">Delete</a><?php } ?>
    </td>
</tr>
<?php  }?>
</tbody>
</table>
</div>
<?php if($norecord_flag == 1) echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>"; ?>

</div>
</div>

