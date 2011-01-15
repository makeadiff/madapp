<script>
	
	tb_init('a.thickbox, input.thickbox');
	
	function triggerSearch()
	{
		q = $('#searchQuery').val();
		get_groupList('0',q);
	}
	
	$(document).ready(function(){
	
		
		$('#example').each(function(){
			var url = $(this).attr('href') + '?TB_iframe=true&height=500&width=800';
	
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
<a href="<?= site_url('user/popupAdduser')?>" class="thickbox button primary" id="example" name="<strong>Add User</strong>">Add User</a>
</div>
			<!-- end page actions-->

      </div>

		

<table cellpadding="0"  cellspacing="0" class="clear" id="tableItems">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Name</th>
    <th class="colStatus sortable">Email</th>
    <th class="colStatus">Mobile No</th>
    <th class="colPosition">Position Held</th>
    <th class="colPosition">City</th>
    <th class="colPosition">Center</th>
    <th class="colPosition">User Type</th>
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
//
$content = $details->result_array();
//
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
<script>
	$(document).ready(function(){
		
		$('#groupmanage-'+<?php echo $row['id']; ?>).each(function(){
			var url = $(this).attr('href') + '?TB_iframe=true&height=430&width=850';
	
			$(this).attr('href', url);
		});
		
		$('#group-'+<?php echo $row['id']; ?>).each(function(){
			var url = $(this).attr('href') + '?TB_iframe=true&height=500&width=800';
	
			$(this).attr('href', url);
		});
	
	}
	); 
</script>

<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colCheck1"><a href="#"><?php echo $row['id']; ?></a></td>
    <td class="colName left"> <a href="#"><?php echo $row['name']; ?></a></td>
    <td class="colCount"><a href=""> <?php echo $row['email']; ?></a></td> 
     <td class="colStatus" style="text-align:left"><?php echo $row['phone'];  ?></td>
     <td class="colPosition" style="text-align:left"><?php echo $row['title'];  ?></td>
    <td class="colPosition"><?php echo $row['city_name'];  ?></td>
    <td class="colPosition"><?php echo $row['center_name'];  ?></td>
    <td class="colPosition"><?php echo $row['user_type'];  ?></td>
    
    <td class="colActions right"> 
    <a href="<?= site_url('user/popupEditusers/'.$row['id'])?>" class="thickbox" style="cursor:pointer;background-image:url(<?php echo base_url(); ?>/images/ico/icoEdit.png)" id="group-<?php echo $row['id']; ?>" name="<strong>Edit User : <?= strtolower($row['name']) ?></strong>"></a> 
    <a class="actionDelete" href="javascript:deleteEntry('<?php echo $row['id']; ?>','<?php echo $currentPage; ?>');"></a>
    </td>
</tr>

<?php }?>
</tbody>
</table>

<?php if($norecord_flag == 1) 
{ 
	  if($currentPage != '0'): ?>
      <script>
      	 get_groupList('<?php echo $currentPage-1; ?>');
	   </script>
<?php else: 
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
	  endif;
}    ?>

</div>

</div>
