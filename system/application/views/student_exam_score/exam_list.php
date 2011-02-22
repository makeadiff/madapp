<script>
	
	tb_init('a.thickbox, input.thickbox');
	
	function triggerSearch()
	{
		q = $('#searchQuery').val();
		get_groupList('0',q);
	}
	
	$(document).ready(function(){
	
		
		$('#example').each(function(){
			var url = $(this).attr('href') + '?TB_iframe=true&height=400&width=900';
	
			$(this).attr('href', url);
		});
		
	
	}
	);  
	
</script>

<div id="content" class="clear">

<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>

            <!-- start page actions-->
        	<div id="actions"> 
<a href="<?= site_url('exam/add_exam')?>" class="thickbox button primary" id="example" name="<strong>Add New Exam</strong>">Add New Exam</a>
</div>
			<!-- end page actions-->

	    </div>

		<div id="topOptions" class="clear">

		</div>

<table id="tableItems" class="clear" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable" style="width:375px; text-align:center">Exam Name</th>
    <th class="colName left sortable" style="width:375px; text-align:center">Details</th>
    
<!--    <th class="colActions"  style="width:225px;">Actions</th>
--></tr>
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
<script>
	$(document).ready(function(){
		
		$('#groupmanage-'+<?php echo $row['id']; ?>).each(function(){
			var url = $(this).attr('href') + '?TB_iframe=true&height=430&width=850';
	
			$(this).attr('href', url);
		});
		
		$('#group-'+<?php echo $row['id']; ?>).each(function(){
			var url = $(this).attr('href') + '?TB_iframe=true&height=400&width=700';
	
			$(this).attr('href', url);
		});
	
	}
	); 
</script>
<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colCheck1"><?php echo $i; ?></a></td>
    <td class="colName left" style="text-align:center"><?php echo strtolower($row['name']); ?></a></td>
    
	<td class="colName left" style="text-align:center"> <a href="<?=site_url('exam/view_exam_details/'.$row['id']) ?> " class="thickbox" id="groupmanage-<?php echo $row['id']; ?>" name="<strong>Details of <?= strtolower($row['name']) ?></strong>"> View Details</a></td>
    
    <!--<td class="colActions right"> 
    <!--<a href="<?//= //site_url($row['id'])?>" class="thickbox" style="cursor:pointer;background-image:url(<?php //echo base_url(); ?>/images/ico/icoEdit.png)" id="group-<?php //echo $row['id']; ?>" name="<strong>Edit Group : <?//=strtolower($row['name']) ?></strong>">Edit</a> 
    <a class="actionDelete" href="javascript:deleteEntry('<?php //echo $row['id']; ?>','<?php //echo $currentPage; ?>')">Delete</a>
    </td>-->
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
