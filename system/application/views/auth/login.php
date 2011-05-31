<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="ltr">
<head>
<title>MADApp Login</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
<style type="text/css">
body {
	text-align:center;
	font-size:1.5em;
}
form {
	background-color:#eee;
	width:400px;
	padding:10px;
	margin:100px auto;
	text-align:left;
}
input {
	font-size:1em;
}
.small {
	font-size:16px;
}
</style>

</head>
<body id="pageLogin">
<div class="field" style="color:#CC0000; text-align:center; margin-top:50px;"><?php echo $message;?></div>
    
<?php echo form_open("auth/login");?>
<fieldset>
<legend>MADApp Login</legend>

<div class="field"> 
<label for="email">Email</label><br />
<?php echo form_input($email); ?>
</div><br /><br />

<div class="field">
<label for="password">Password</label><br />
<?php echo form_input($password); ?>
</div><br /><br />

<div class="field">
<label for="remember" class="small">Remember Me</label>
<?php echo form_checkbox(array('name'=>'remember','id'=>'remember','value'=>'1', 'checked'=>FALSE));?>
</div><br />

<div class="field">
<?php echo form_submit('submit', 'Login');?><br />
<a class="small" href="<?php echo site_url('auth/forgotpassword') ?>">Forgot Password?</a>

</div>

</fieldset>
<?php echo form_close();?>

</body>

</html>