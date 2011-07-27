<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Add Settings</h2>
<div id="message"></div>
<div style="float:left; margin-top:20px;">
<script>
function validate()
{
	if(document.getElementById("name").value == '')
          {		
              alert("Settings Name Missing.");
			  document.getElementById('name').focus();
              return false;
          }
}
</script>

<div id="head" class="clear"></div>
<form action="<?php echo site_url('settings/create')?>" method="post" class="form-area" id="main" onsubmit="return validate();">
<ul class="form city-form">
	<li>
	<label for='name'> Name :</label>
	<input type="text"  name="name" id="name"  /><br/>
	</li>
	<li>
	<label for='value'>Value :</label>
	<input type="text" style="line-height:15px;" name="value" id="value"  /><br />
	</li>
	<li>
	<label for='data'>Data :</label>
	<textarea name="data" id="data" rows="15" cols="30"></textarea><br />
	</li>
	</ul>
    <ul>
    <li>
		<input  id="btnSubmit" class="button green" type="submit" value="Submit" />
        <a href="<?php echo site_url('settings/index')?>" class="cancel-button">Cancel</a>
	</li>
	</ul>
</form>
</div>