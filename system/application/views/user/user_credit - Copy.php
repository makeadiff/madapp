<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/thickbox.css">
<div id="updateDiv" >
<div id="content" class="clear">

<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>
	    </div>
		<div id="topOptions" class="clear">
		</div>
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
    <th class="colName left sortable" style="width:375px; text-align:center">Credit</th>
</tr>
</thead>
<tbody>

<?php 
//
$norecord_flag = 1;
$content = $details->result_array();
$i=0;
foreach($content as $row)
{	
	$norecord_flag = 0;
?> 
<tr  id="group">
<!--    <td class="colCheck1"><?php //echo $i; ?></a></td>
  <td class="colName left" style="text-align:center">-->  
    <?php 
    $credit = 3;
	$current_user_id=$this->session->userdata('id');
	if ($row['user_id'] == $current_user_id && $row['substitute_id'] == 0 && $row['status'] == 'absent')
	{	
		$i++;
		echo $row['class_id'];
		echo "<td class='colCheck1'>$i</a></td>";
        $credit = $credit - 2;
        print "<td class='colName left' style='text-align:center'>Class123 on '".$row['class_on']."' - Absent - lost 2 credits - $credit</td>";
    }
	else if ($row['user_id'] == $current_user_id && $row['substitute_id'] != 0 )
	{
		$i++;
		echo "<td class='colCheck1'>$i</a></td>";
		echo $row['class_id'];
		$credit = $credit - 1;
        print "<td class='colName left' style='text-align:center'>Class o1n '".$row['class_on']."' - Substituted by $Name_of_Substitute - lost 1 credit - $credit</td>";
	}
	else if($row['substitute_id'] == $current_user_id && $row['status'] == 'absent')
	{
		$i++;
		echo "<td class='colCheck1'>$i</a></td>";
		echo $row['class_id'];
        $credit = $credit - 2;
        print "<td class='colName left' style='text-align:center'>Substitute Class on '".$row['class_on']."' - Absent - lost 2 credits - $credit<td>";
    
    }
	else if ($row['substitute_id'] == $current_user_id && $row['status'] == 'attended')
	{
		$i++;
		echo "<td class='colCheck1'>$i</a></td>";
		echo $row['class_id'];
        $credit = $credit + 1;
        print "<td class='colName left' style='text-align:center'>Substitute Class on '".$row['class_on']."' - Took Class - gained 1 credit - $credit</td>";
	}
    
    ?>
    </td>
  
</tr>

<?php }?>
</tbody>
</table>

<?php if($norecord_flag == 1) 
{ 
   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
}    ?>
</div>

</div>

</div>
</div>

