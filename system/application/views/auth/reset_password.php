<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="ltr">
<head>
<title>Forgot Password</title>
<!--<link rel="stylesheet" type="text/css" href="<?php /*echo base_url()*/?>css/camp/master.css" />-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/login-custom.css" />
<link href='http://fonts.googleapis.com/css?family=Oswald:700' rel='stylesheet' type='text/css'>
</head>
<body id="pg-login" style="background-image:url(<?php echo base_url()?>images/background.jpg)">
<div class="container">
<div class="row">
<div class="form-group col-md-4 col-md-offset-4 col-sm-12">
    <br><br>
    <h1 class="title">Reset Password</h1><br>
    <?php
    $message['success'] = $this->session->flashdata('success');
    $message['error'] = $this->session->flashdata('error');
    if(!empty($message['success']) or !empty($message['error'])) { ?>
    <div class="message" id="error-message" <?php echo (!empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
    <div class="message" id="success-message" <?php echo (!empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
    <?php } ?>

    <form class="form-signin" role="form" method="post" action="<?php echo site_url('auth/reset_password/' . $code)?>">
        <p>Reset password for <?php echo $email ?>...</p>
        <?php echo form_input([
            'name'  => 'password',
            'id'    => 'password',
            'type'  => 'password',
            'placeholder'   => 'New Password',
            'class' => 'form-control'
        ]); ?>
        <?php echo form_input([
            'name'  => 'password_confirm',
            'id'    => 'password_confirm',
            'type'  => 'password',
            'placeholder'   => 'Confirm Password',
            'class' => 'form-control'
        ]); ?>
        <br>
        <?php echo form_submit('submit', 'Reset Password', 'class="btn btn-lg btn-primary btn-block"');?>

    </form>

</div>
</div>
</div>

</body>
</html>
