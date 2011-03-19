<script type="text/javascript">
tb_init('a.thickbox, input.thickbox');
function get_projects(pageno){
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('project/getprojectlist') ?>",
		data: "page_no="+pageno,
		success: function(msg){
			$('#kids_list').html(msg);
		}
		});
}

$(document).ready(function(){
	$('.popup').each(function(){
		var url = $(this).attr('href') + '?TB_iframe=true&height=300&width=600';
		$(this).attr('href', url);
	});
	
	$('.group').each(function(){
		var url = $(this).attr('href') + '?TB_iframe=true&height=400&width=700';
		$(this).attr('href', url);
	});
});  
</script>

<div id="content" class="clear">

<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>

            <!-- start page actions-->
        	<div id="actions"> 
			<a href="<?php echo site_url('project/popupaddproject')?>" class="thickbox button primary popup" id="example" name="Add Projects">Add Project</a>
			</div>
			<!-- end page actions-->

	    </div>
	    
<div id="project_list">
<table id="tableItems" class="clear" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th  class="colName left sortable">Name</th>
    <th  class="colStatus sortable" style="width:150px;">Added_on</th>
    <th  class="colActions">Actions</th>
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
    <td class="colCheck1"><?php echo $i; ?></td>
    <td class="colName left"> <?php echo $row['name']; ?></td>
    <td class="colCount"><?php echo $row['added_on']; ?></td> 
    <td class="colActions right"> 
    <a href="<?php echo site_url('project/popupEdit_project/'.$row['id'])?>" class="thickbox" style="cursor:pointer;background-image:url(<?php echo base_url(); ?>/images/ico/icoEdit.png)" id="group-<?php echo $row['id']; ?>" class="group" name="Edit Project">Edit</a> 
    <a class="actionDelete" href="javascript:deleteEntry('<?php echo $row['id']; ?>','<?php echo $currentPage; ?>')">Delete</a>
    </td>
</tr>

<?php  }?>
</tbody>
</table>
</div>

<?php if($norecord_flag == 1) { 
	  if($currentPage != '0') { ?>
<script>
	get_projects('<?php echo $currentPage-1; ?>');
</script>
<?php } else {
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- No Records Found -</div>";
	}
}    ?>

</div>

</div>
