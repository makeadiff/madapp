<div style="float:left;"><h1>Add Exam Mark</h1></div>
<?php 
$details = $details->result_array();
if($details) { 
?>
<div id="main" class="clear" >
<div id="message"></div>
<div id="content" class="clear">
<!-- Main Begins -->
	<div id="content" class="clear">
<div id="sub-chapter-header">
<div id="subject-div" style="margin-left:5px; margin-top:80px;">
      <?php $exam_details = $exam_details->result_array(); ?>
      <label>Select Exam : </label>
      <select name="select_exam" id="select_exam" class="medium" onchange="javascript:mark_view_divs(this.value);">
      <option value="-1" selected="selected">-- select exam --</option>
      <?php foreach($exam_details as $row): ?>
      	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
      <?php endforeach; ?>	
      </select>
     
  </div>
   <div style="float:left; margin-top:10px;margin-left:5px;">selected Exam:<span style="font-weight:bold;"><?=$exam_name?></span></div>
  <div id="loading" name="loading" style="display:none;" align="center">
    <img src="<?php echo base_url()?>images/ico/loading_1.gif" height="18" width="18" style="border: none;" /> 
    <span style="color:#000;font-weight:bold;">loading...</span>
  </div>
  </div>
  </div>
<div >
<form id="formEditor" class="mainForm clear" method="post" action="<?php echo site_url('exam/addMarks')?>"  style="width:500px;"  >
<fieldset class="clear" style="margin-top:50px;width:300px">
<table cellpadding="0"  cellspacing="5" class="clear" id="tableItems">
<thead>
<tr id="generated_rows">
	<th  class="colCheck1" width="40">sino</th>
	<th  class="colCheck1">Name</th>
	<?php 
	$i=0;
    
	$subject=$subject->result_array();
	$sub_count=count($subject);
    foreach($subject as $row) {
		$exam_id=$row['exam_id'];
   		$i++;
    ?>
    
    <input type="hidden" value="<?php echo $row['id']; ?> " id="sub_id" name="sub_name<?=$i?>">
	<th width="59" class="colCheck12"><?php echo $row['name']; ?></th>
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

<tr class="<?php echo $shadeClass; ?>" id="group" style="margin-top:10px;">
   	<td class="colCheck1" style="width:30px; text-align:center;"><?php echo $l; ?></td>
    <input type="hidden" value="<?php echo $row['id']; ?>" id="stnt_id"  name="stnt_name<?=$k?>" >
    <td class="colCheck1"><?php echo $row['name']; ?></td>
    <?php $j=0; ?>
    <?php for($i=1;$i<count($subject)+1;$i++) { $j++; ?>
    <td align="center" class="colCheck1">
    <?php //echo $l.'mark'.$j; ?>
    <input type="text" name="<?=$l?>mark<?=$j?>" style="width:23px; margin:5px 0" id="mark"></td>
    <?php }  ?>
</tr>
<?php }?>
</tbody>
</table>

<div class="field clear"> 
<input type="hidden" value="<?php echo $sub_count; ?>" id="sub_count" name="sub_count">
<input type="hidden" value="<?php echo $id_count; ?>" id="stnt_count" name="stnt_count">
<input type="hidden" value="<?php echo $exam_id; ?>" id="exam_id" name="exam_id">

<input style="margin-left:70px; margin-top:10px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
</div>

</div>
</div>

</fieldset>
</form>
</fieldset>
<?php } else { ?>
<div style="margin-top:80px;">
<div id="main" class="clear" >
<div id="content" class="clear">
<div id="sub-chapter-header">
<div id="subject-div" style="margin-left:5px;">
      <?php $exam_details = $exam_details->result_array(); ?>
      <label>Select Exam : </label>
      <select name="select_exam" id="select_exam" class="medium" onchange="javascript:mark_view_divs(this.value);">
      <option value="-1" selected="selected">-- select exam --</option>
      <?php foreach($exam_details as $row): ?>
      	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
      <?php endforeach; ?>	
      </select>
     
  </div>
   <div style="float:left; margin-top:10px; margin-left:5px;">selected Exam:<span style="font-weight:bold;"><?=$exam_name?></span></div>
   
  <div id="loading" name="loading" style="display:none;" align="center">
    <img src="<?php echo base_url()?>images/ico/loading_1.gif" height="18" width="18" style="border: none;" /> 
    <span style="color:#000;font-weight:bold;">loading...</span>
  </div>
  </div>
  </div></div>
<?php echo "<div style='background-color: #FFFF66;margin-top:45px;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no subjects and students found -</div>";} ?>
</div>



