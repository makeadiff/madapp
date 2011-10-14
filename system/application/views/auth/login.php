<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="ltr">
<head>
<title>MADApp Login</title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/camp/master.css" />
</head>
<body id="pg-login">

<div class="login-box">
<h1>MADApp Login</h1>
<?php
$message['success'] = $this->session->flashdata('success');
$message['error'] = $this->session->flashdata('error');
if(!empty($message['success']) or !empty($message['error'])) { ?>
<div class="message" id="error-message" <?php echo (!empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
<div class="message" id="success-message" <?php echo (!empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
<?php } ?>

<?php echo form_open("auth/login");?>

<ul class="form login-form">
<li><label for="email">Email</label><?php echo form_input($email); ?></li>
<li><label for="password">Password</label><?php echo form_input($password); ?></li>
<li><label for="remember" class="small">Remember Me</label><?php echo form_checkbox(array('name'=>'remember','id'=>'remember','value'=>'1', 'checked'=>true));?></li>
<li><a class="small" href="<?php echo site_url('auth/forgotpassword') ?>">Forgot Password?</a><?php echo form_submit('submit', 'Login', 'class="button green"');?></li>
<li></li>
</ul>

<?php echo form_close();?>
</div>

</body>

</html>