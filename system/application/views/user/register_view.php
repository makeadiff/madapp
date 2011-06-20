<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>
<link href="<?php echo base_url(); ?>css/freeport/register.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
</head>
<body>
<div id="container">
  <div id="wraper">
    <div id="main-content">
      <div id="title">User Register</div>
	  <div id="content"><div style="color:#FF0000; text-align:center;"><?php echo $message; ?></div>
        <form method="post" action="<?=site_url('common/register')?>"  name="regform" id="regForm" onsubmit="return validate();" >
            <div class="content-row-large">
                   <span>Name:</span>
                   <input type="text" class="textfield" id="firstname" name="firstname" value="<?php if(isset($this->validation->firstname)){ echo $this->validation->firstname; } ?>" />
                   <?php if(!empty($this->validation->firstname_error)) { ?>
                   <img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" />
                   <?php } ?>
            </div>

            <div class="content-row-large"><span>Email:</span>
                <input type="text" class="textfield" id="email" name="email" value="<?php if(!empty($this->validation->email)){ echo $this->validation->email; } ?>" />
                  <?php if(!empty($this->validation->email_error)) { ?>
                  <img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" />
                  <?php } ?>
            </div>

            <div class="content-row-large"><span>Phone:</span>
                  	<input type="text" class="textfield" id="mobileno" name="mobileno" value="<?php if(isset($this->validation->mobileno)){ echo $this->validation->mobileno; } ?>" />
                  	<?php if(!empty($this->validation->mobileno_error)) { ?>
                  	<img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" />
                  	<?php } ?>
            </div>
				 
            <div class="content-row-large"><span>City:</span>
                    <select class="dropdown" id="city" name="city" onchange="javascript:getcenter_Name(this.value);">
                    <option value="-1">- Select -</option>
                    <?php $details = $details->result_array(); ?>
                    <?php foreach($details as $row) {
						$cityName=$row['name'];
						$city_id=$row['id'];
					?>
                    <option value="<?php echo $city_id; ?> "><?php echo $cityName; ?></option>
                  	<?php } ?>  
                  	</select>
<!--                    server side validation -->
					<?php if(isset($city) && $city == '1') { ?>
                	<img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" style="margin-left: -15px;" />
                	<?php } ?>
            </div>
            
            <input type="hidden" name="password" value="network" />
            <input type="hidden" name="center" value="0" />
            <input type="hidden" name="position" value="" />
            
            <div class="content-row-reg" style="margin-top: 30px;">
				  <input name="button" type="submit" class="reg-button" id="button" value="Register" />
           	</div>
        </form>
       
</div>
    </div>
  </div>
</div>

</body>
</html>