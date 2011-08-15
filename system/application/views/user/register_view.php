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
      <div id="title"><h1>User Register</h1></div><br /><br /><br /><br />
	  <div id="content">
	   <?php
		$message['success'] = $this->session->flashdata('success');
		$message['error'] = $this->session->flashdata('error');
		if(!empty($message['success']) or !empty($message['error'])) { ?>
		<div class="message" id="error-message" <?php echo (!empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
		<div class="message" id="success-message" <?php echo (!empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
		<?php } ?>
	  
        <form method="post" action="<?php echo site_url('common/register')?>"  name="regform" id="regForm" onsubmit="return validate();" >
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
            <select class="dropdown" id="city" name="city" onchange="if(this.value==0)location.href='http://hq.makeadiff.in/functional-guidelines/expansion';">
				<option value="1">Bangalore</option>
				<option value="2">Mangalore</option>
				<option value="3">Trivandrum</option>
				<option value="4">Mumbai</option>
				<option value="5">Pune</option>
				<option value="6">Chennai</option>
				<option value="8">Vellore</option>
				<option value="9">Cochin</option>
				<option value="11">Hyderabad</option>
				<option value="12">Delhi</option>
				<option value="13">Chandigarh</option>
				<option value="14">Kolkata</option>
				<option value="15">Nagpur</option>
				<option value="16">Coimbatore</option>
				<option value="17">Vizag</option>
				<option value="18">Vijayawada</option>
				<option value="19">Gwalior</option>
				<option value="0">Other</option>
			</select>
            <?php /*
                    <select class="dropdown" id="city" name="city">
                    <?php $details = $details->result_array(); ?>
                    <?php foreach($details as $row) {
						$cityName=$row['name'];
						$city_id=$row['id'];
					?>
                    <option value="<?php echo $city_id; ?>"><?php echo $cityName; ?></option>
                  	<?php } ?>
                  	<option value="0">Other</option>
                  	</select>
             */ ?>
					<?php if(isset($city) && $city == '1') { ?>
                	<img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" style="margin-left: -15px;" />
                	<?php } ?>
            </div>
            
            <input type="hidden" name="password" value="pass" />
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