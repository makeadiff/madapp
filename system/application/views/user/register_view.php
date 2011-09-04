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
      <div id="title"><h1>Register and Make A Difference</h1></div>
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
				<?php foreach($cities as $id=>$name) { ?>
				<option value="<?php echo $id ?>"><?php echo $name ?></option>
				<?php } ?>
				<option value="0">Other</option>
			</select>
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