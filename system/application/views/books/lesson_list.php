<script>
tb_init('a.thickbox, input.thickbox');

function triggerSearch() {
	q = $('#searchQuery').val();
	get_groupList('0',q);
}

function add_lesson(){
//alert(center_id);
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('books/popupadd_lesson')?>",
		success: function(msg){
		$('#sidebar').html(msg);
		}
		});
}
function edit_lessons(id)
{
	$.ajax({
		type: "POST",
		url: "<?=site_url('books/popupEdit_lesson')?>"+'/'+id,
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

		<div id="actions">
		<?php //if($this->user_auth->get_permission('kids_add')) { ?>
		<a href="javascript:add_lesson()" class="button primary" >Add  Lessons</a>
		<?php //} ?>
		</div>
	</div>
<div id="kids_list">
<table id="tableItems" class="clear" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Lesson Name</th>
    <th class="colName left sortable">Book Name</th>
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
    <td class="colName left"><?php echo $row['name']; ?></td>
    <td class="colName left"><?php echo $row['book_name']; ?></td>
    
    <td class="colActions right"> 
    <?php if($this->user_auth->get_permission(''))  
	{ ?><a href= "javascript:edit_lessons('<?=$row['id']?>');" style="cursor:pointer;background-image:url (<?php echo base_url(); ?>/images/ico/icoEdit.png)" class="group"  >Edit</a><?php } ?>
    <?php if($this->user_auth->get_permission('')) { ?><a class="actionDelete" href="javascript:deleteEntry('<?php echo $row['id']; ?>','<?php echo $currentPage; ?>')">Delete</a><?php } ?>
   
    </td>
</tr>

<?php  }?>
</tbody>
</table>
</div>
<?php if($norecord_flag == 1) 
{ 
	  if($currentPage != '0') { ?>
       <script>
      	 get_centerlist('<?php echo $currentPage-1; ?>');
	   </script>
<?php } else {
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
	  }
}    ?>


</div>


</div>

