<script>
function view_details(id)
{
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('exam/view_exam_details') ?>"+'/'+id,
		//data: "pageno="+page_no+"&q="+search_query,
		success: function(msg){
		$('#loading').hide();
		$('#sidebar').html(msg);
		}
	});
}
function add_exam(id)
{
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('exam/add_exam')?>"+'/'+id,
		//data: "pageno="+page_no+"&q="+search_query,
		success: function(msg){
		$('#loading').hide();
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
<a href="javascript:add_exam();" class="button primary" >Add New Exam</a>
</div>
	<!-- end page actions-->

</div>

<table id="tableItems" style="margin-top:50px;" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
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
	<td class="colName left"><a href="javascript:view_details('<?=$row['id']?>')"> View Details</a></td>
	<td class="colName left"><a href="<?php echo site_url('exam/delete/'.$row['id']) ?>" class="confirm" title="Delete '<?php echo $row['name']; ?>' Exam">Delete</a></td>
</tr>

<?php }?>
</tbody>
</table>

<?php if($norecord_flag == 1) 
{ 
	  if($currentPage != '0'): ?>
       <script>
      	 get_examlist('<?php echo $currentPage-1; ?>');
	   </script>
<?php else: 
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
	  endif;
}    ?>
</div>

</div>
