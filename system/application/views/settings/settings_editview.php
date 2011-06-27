<?php $this->load->view('layout/thickbox_header'); ?>
<h2>Edit Settings</h2>
<div id="message" style="margin-left:-15px;"></div>
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
if(document.getElementById("value").value == '')
          {		
              alert("Settings Value Missing.");
			  document.getElementById('value').focus();
              return false;
          }
if(document.getElementById("data").value == '')
          {		
              alert("Settings Data Missing.");
			  document.getElementById('data').focus();
              return false;
          }
}
</script>
<form action="<?php echo site_url('settings/edit/'.$setting['id'])?>" method="post" class="form-area" id="main" onsubmit=" return validate();" >
<ul class="form city-form">
	<li>
	<label for='name'> Name :</label>
	<input type="text"  name="name" id="name" value="<?php echo $setting['name']; ?>" /><br/>
	</li>
	<li>
    <label for='value'>Value :</label>
    <input type="text" style="line-height:15px;" name="value" id="value" value="<?php echo  $setting['value']; ?>" /><br />
    </li>
    <li>
    <label for='data'>Data :</label>
    <input type="text" style="line-height:15px;" name="data" id="data" value="<?php echo $setting['data']; ?>" /><br />
    </li>
    </ul>
    <ul>
    <li> 
    <input id="btnSubmit" class="button green" type="submit" value="Submit" />
    <a href="<?=site_url('settings/index')?>" class="cancel-button">Cancel</a>
    </li>
    </ul>
</form>
</div>