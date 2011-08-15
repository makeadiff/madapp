<?php $this->load->view('layout/thickbox_header'); ?>
<style>
input[type=text], select, textarea{
float:right;
}
.fields{
float:left;
width:300px;
padding-bottom:5px;
padding-top:5px;
}
</style>
<script>
$(function() {
	$("#centers").change(function() {
		get_kidslist(this.value);
	});
});

function populat_textbox() {
	var sub_no = $('#sub_no').val();
	if(isNaN(sub_no))
		{
		alert("This is not a number");
		document.getElementById('sub_no').focus(); 
		return false;
		}
	$.ajax({
            type: "POST",
            url: "<?= site_url('exam/ajax_sbjectbox')?>",
            data: "sub_no="+sub_no,
            success: function(msg){
           		$('#loading').hide();
            	$('#subject').html(msg);
            }
     });
}
function get_kidslist(center_id)
{
	
	$.ajax({
            type: "POST",
            url: "<?= site_url('exam/get_kidslist')?>",
            data: "center_id="+center_id,
            success: function(msg){
           		$('#loading').hide();
            	$('#kids').html(msg);
            }
            });
}
function dataGrabber()
{
	var name = $('#name').val();
	var sub_no = $('#sub_no').val();
	var center = $('#centers').val();
	
	var agents = "";
	$('#kids :checked').each(function(i, selected)
	{ 
	agents+=($(selected).val()=="")?$(selected).val():$(selected).val()+",";
	});
	//get subject name
	if(name=='') {
		alert("Name missing");
	}
	else if(sub_no=='')
	{
		alert("Enter Subject");
	}
	else if(center =='0')
	{
		alert("Select Center");
	}
	else if(agents =='0')
	{
		alert("Select Kids");
	}
	
	else
	{
		var choiceText = [];
		var cText = '';
		for(var i=1; i<=sub_no; i++)
		{
		choiceText[i] = $('#choice-text-'+i).val();
		}
		for(var i=1; i<choiceText.length; i++) 
		{
			if(choiceText[i] == '')
			{
				choiceText[i] = 'nil';	 	
			}
			cText = cText+choiceText[i]+',';
			
		}
	$.ajax({
		type: "POST",
		url: "<?php echo site_url('exam/input_exam_mark_details')?>",
		data: "agents="+agents+'&name='+name+'&choice_text='+cText+'&center='+center,
		success: function(msg){
		$('#message').html(msg);
		$('#refresh').fadeOut('slow');
		window.parent.get_examlist();
		}
		});
		}
}
</script>
<div id="message"></div>
<h2>Add New Exam</h2>
        
<form name="form" id="formEditor" class="form-area clear"  onclick="return false"   action="" method="post"  >
<ul class="form city-form">
<li>
<label for="txtName">Exam Name : </label>
<input id="name" name="name"  type="text" /> 
</li>
<li>
<label for="txtName">No of Subjects:</label>
<input id="sub_no" name="sub_no"  type="text" onkeyup="javascript:populat_textbox();" /> 
</li>	
<li><div  id="subject" > 
</div>
</li>
	<li>
	<label for="selBulkActions">Select center:</label> 
	<select id="centers" name="center">
	<option  value="0" >- Select -</option> 
	<?php foreach($centers as $cen) { ?>
	<option  value="<?php echo $cen->id ?>"><?php echo $cen->name ?></option> 
	<?php } ?>
	</select>
	</li>
<li>
<label for="selBulkActions">Kids:</label>
<select id="kids" name="kids"  style="width:142px; height:80px;" multiple >
</select>
</li>
</ul>
<ul>
<li>
<input  id="btnSubmit" class="button green" type="submit" value="Submit" onclick="javascript:return dataGrabber();"/>
</li>
</ul>
</form>

<?php $this->load->view('layout/thickbox_footer'); ?>