<?php 
$this->load->view('layout/css');?>
<h2>Exam Details</h2>

<style type="text/css">
label {
	float:left;
	font-weight:bold;
}
div.ans {
	float:left;
	text-align:left;
}
div.field {
	width:300px;
}
</style>

<fieldset class="clear" style="margin-top:50px;width:300px;margin-left:30px;">
	<div class="field clear"> 
		<label for="txtName">Exam Name : </label>
		<div class="ans"><?php echo $exam_name->name; ?> </div>
                      
	</div>
	<br />
	<div class="field clear">
		<label for="txtName">Subject Name : </label>
		<div class="ans"><?php 
		$subjects = $sub_name->result();
		foreach($subjects as $row) 
			echo $row->name . '<br />';
		?></div>
	</div>
</fieldset>
      