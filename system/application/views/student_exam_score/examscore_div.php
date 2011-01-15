
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
<tr>
 <td class="colCheck1" style="width:10px;"><?php echo $id; ?></td>
    <td class="colCheck1"><?php echo $student_name; ?></td>
    
    <?php 
$norecord_flag = 1;
$shadeFlag = 0;
$shadeClass = '';
$statusIco = '';
$statusText = '';

$content = $details1->result_array();
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
    <?php //for($i=1;$i<=$count;$i++) { ?>
    
    <td class="colName left"> <a href="#"> <?php echo $row['mark']; ?>  </a></td>
    <?php //} ?>
   


<?php }?>