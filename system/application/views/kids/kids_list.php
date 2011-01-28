<script>
	
	tb_init('a.thickbox, input.thickbox');
	
	function triggerSearch()
	{
		q = $('#searchQuery').val();
		get_groupList('0',q);
	}
	
	$(document).ready(function(){
	
		
		$('#example').each(function(){
			var url = $(this).attr('href') + '?TB_iframe=true&height=400&width=700';
	
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
<a href="<?= site_url('kids/popupaddKids')?>" class="thickbox button primary" id="example" name="<strong>Add Kids</strong>">Add  Kids</a>
</div>
			<!-- end page actions-->

	    </div>

		<div id="topOptions" class="clear">

		</div>

<table id="tableItems" class="clear" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Name</th>
    <th class="colStatus sortable">Birth Day</th>
    <th class="colStatus">Center</th>
<th class="colStatus">Image</th>
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
    <td class="colCheck1"><a href="#"><?php echo $i; ?></a></td>
    <td class="colName left"> <a href="#"><?php echo $row['name']; ?></a></td>
    <td class="colCount"><a href=""><?php echo $row['birthday']; ?></a></td> 
     <td class="colStatus" style="text-align:left"><?php echo $row['center_name'];?></td>
<td class="colPosition">  
<img src="<?=base_url().'pictures/'.$row['photo']; ?>" width="50" height="50" /> </td>


    <td class="colActions right"> 
    <a href="<?= site_url('kids/popupEdit_kids/'.$row['id'])?>" class="thickbox" style="cursor:pointer;background-image:url(<?php echo base_url(); ?>/images/ico/icoEdit.png)" id="group-<?php echo $row['id']; ?>" name="<strong>Edit student : <?= strtolower($row['name']) ?></strong>">Edit</a> 
    <a class="actionDelete" href="javascript:deleteEntry('<?php echo $row['id']; ?>','<?php echo $currentPage; ?>')">Delete</a>
    </td>
</tr>

<?php  }?>
</tbody>
</table>

<?php if($norecord_flag == 1) 
{ 
	  if($currentPage != '0'): ?>
       <script>
      	 get_centerlist('<?php echo $currentPage-1; ?>');
	   </script>
<?php else: 
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
	  endif;
}    ?>


</div>


</div>

