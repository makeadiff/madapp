<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" /
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="ltr">
<!-- Mirrored from miniplaneta.pl/encoreadmin/rc/2/3.html by HTTrack Website Copier/3.x [XR&CO'2010], Sat, 30 Oct 2010 16:18:28 GMT -->
<head>

<title>madapp Admin Login</title>

</head>
<body id="pageLogin">
<div class="field" style="color:#CC0000; text-align:center; margin-top:50px;">
	<?php echo $message;?>
	</div>
<div id="login" class="centerbox">
<h2 align="center">Login</h2>
<div class="boxInside">

	
    <?php echo form_open("auth/login");?>
    <fieldset>
	<legend>Login</legend>
    
    <div class="field"> 
      	<label for="email">Email:</label>
      	<?php  echo form_input($email);?>
      </div>
      
      <div class="field">
      	<label for="password">Password:</label>
      	<?php echo form_input($password);?>
      </div>
      
      <div class="field" style="margin-top:10px;">
	      <label for="remember">Remember Me:</label></div>
          <div  style=" float:left; margin-left:-50px; margin-top:10px;">
	      <?php echo form_checkbox('remember', '1', FALSE);?>
	 </div>
      
      <div class="field" style="margin-top:-21px; margin-left:150px;">
      <?php echo form_submit('submit', 'Login');?>
	</div>
      
    <?php echo form_close();?>

</div>

</div>
</body>

</html>