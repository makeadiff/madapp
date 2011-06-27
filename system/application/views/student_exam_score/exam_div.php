<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script>
function mark_view_div(exam_id)
{
	if(exam_id != '-1') {
			$('#loading').show();
			$('#score_div').show();
            $.ajax({
            type: "POST",
            url: "<?= site_url('exam/ajax_getexam_details')?>",
            data: "exam_id="+exam_id,
            success: function(msg){
           		$('#loading').hide();
            	$('#score_div').html(msg);
            }
            });
	}
	else
	{
	$('#score_div').hide();
	}
}
	
</script> 
<div id="content" class="clear">
<div id="sub-chapter-header">
<div id="subject-div" style="margin-left:5px;">
      <?php $exam_details = $exam_details->result_array(); ?>
      <label>Select Exam : </label>
      <select name="select_exam" id="select_exam" class="medium" onchange="javascript:mark_view_div(this.value);">
      <option value="-1" selected="selected">-- select exam --</option>
      <?php foreach($exam_details as $row): ?>
      	<option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
      <?php endforeach; ?>	
      </select>
  </div>
  <div id="loading" name="loading" style="display:none;" align="right">
    <img src="<?php echo base_url()?>images/ico/loading_1.gif" height="18" width="18" style="border: none;margin-left: ;" /> 
    <span style="color:#000;font-weight:bold;">loading...</span>
  </div>
  
  </div>
  </div>
  <div id="score_div">
  </div>