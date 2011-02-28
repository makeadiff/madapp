<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />

<?php 
$details = $details->result_array();
if($details) { 
?>

<div id="content" class="clear">
<!-- Main Begins -->
	<div id="main" class="clear" >
<form id="formEditor" class="mainForm clear" method="post" action="<?=site_url('exam/addMarks')?>"  style="width:500px;"  >
	<fieldset class="clear" style="margin-top:50px;margin-left:-80px;">

<table cellpadding="0"  cellspacing="0" style="width:auto;" class="clear" id="tableItems">
<thead>
<tr id="generated_rows">
	<th  class="colCheck1">Si No</th>
	<th  class="colName left sortable">Name</th>
	<?php 
	$i=0;
    
	$subject=$subject->result_array();
	$sub_count=count($subject);
    foreach($subject as $row)
	{
	$exam_id=$row['exam_id'];
   	$i++;
    ?>
    
    <input type="hidden" value="<?php echo $row['id']; ?> " id="sub_id" name="sub_name<?=$i?>">
	<td width="59" class="colCheck12"><?php echo $row['name']; ?></td>
    <?php }?>   
</tr>
</thead>
<tbody>

<?php 
$norecord_flag = 1;
$shadeFlag = 0;
$shadeClass = '';
$statusIco = '';
$statusText = '';

//$content = $details->result_array();
//print_r($content);
$id_count=count($details);
$k=0;
$l=0;
foreach($details as $row)
{
	$l++;
	$k++;
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
   	<td class="colCheck1" style="width:10px;"><?php echo $l; ?></td>
    <input type="hidden" value="<?php echo $row['id']; ?>" id="stnt_id"  name="stnt_name<?=$k?>" >
    <td class="colCheck1"><?php echo $row['name']; ?></td>
    <?php $j=0; ?>
    <?php for($i=1;$i<count($subject)+1;$i++) { $j++; ?>
    <td class="colCheck1">
    <?php //echo $l.'mark'.$j; ?>
    <input type="text" name="<?=$l?>mark<?=$j?>" style="width:150px; margin-bottom:5px;" id="mark"></td>
    <?php }  ?>
</tr>
<?php }?>
</tbody>
</table>

<div class="field clear"> 
		<input type="hidden" value="<?php echo $sub_count; ?>" id="sub_count" name="sub_count">
        <input type="hidden" value="<?php echo $id_count; ?>" id="stnt_count" name="stnt_count">
                <input type="hidden" value="<?php echo $exam_id; ?>" id="exam_id" name="exam_id">

     	<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
</div>

</div>
</div>

</fieldset>
</form>
<?php } else { 

echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no subjects and students found -</div>";} ?>




