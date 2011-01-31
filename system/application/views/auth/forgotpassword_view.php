<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="ltr">
<head>
<title>madapp Admin Login</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />

</head>
<body id="pageLogin">
<div class="field" style="color:#CC0000; text-align:center; margin-top:50px;">
	</div>
   
<div id="login" class="centerbox">
 <div style="color:#FF0000; margin-bottom:5px;"><?php echo $message;?></div>
<h2 align="center">Forgot Password</h2>
<div class="boxInside">

	
    <?php echo form_open("auth/forgotpassword");?>
    <fieldset>
	<legend>Login</legend>
    
    <div class="field"> 
      	<label for="email" >Enter your Email:</label>
         <?php echo form_input($email);?>
      </div>
      <div class="field" style="margin-top:21px; margin-left:150px;">
      <?php echo form_submit('submit', 'Submit');?>
	</div>
      </fieldset>
    <?php echo form_close();?>

</div>

</body>


</html>