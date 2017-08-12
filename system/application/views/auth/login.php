<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="ltr">
<head>
<title>MADApp Login</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<!--<link rel="stylesheet" type="text/css" href="<?php /*echo base_url()*/?>css/camp/master.css" />
<link rel="stylesheet" type="text/css" href="<?php /*echo base_url()*/?>css/style.css" />-->
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap.min.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/login-custom.css" />
 <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/custom.css" />



</head>
<body id="pg-login" class="blue-red">
<div class="container">
<div class="row">
<div class="form-group col-md-4 col-md-offset-4 col-sm-12">

<h1 class="title">MADApp Login</h1><br>

<?php
if(empty($message['success'])) $message['success'] = $this->session->flashdata('success');
if(empty($message['error'])) $message['error'] = $this->session->flashdata('error');

if(!empty($message['success'])) { ?>
<div class="alert alert-success alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <strong>Success : </strong><?php echo $message['success'] ?>
</div>
<?php } ?>

<?php
if(!empty($message['error'])) { ?>
<div class="alert alert-danger alert-dismissable">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
    <strong>Error : </strong><br><?php echo $message['error'] ?>
</div>
<?php } ?>


<form class="form-signin" role="form" method="post" action="<?php echo site_url('auth/login')?>">

    <?php echo form_input($email); ?>
    <?php echo form_input($password); ?>
    <label class="checkbox">
        <input type="checkbox" name='remember' id='remember' checked='true' value="remember-me"> Remember me
    </label>
    <a class="small" href="<?php echo site_url('auth/forgotpassword') ?>">Forgot Password?</a>
    <br><br>
    <?php echo form_submit('submit', 'Login', 'class="btn btn-lg btn-primary btn-block"');?>
    <input type="hidden" name='redirect_url' value="<?php if(!empty($redirect_url)) echo $redirect_url; ?>" />
</form>

</div>
</div>
</div>
</div>


<script src="<?php echo base_url()?>js/jquery-1.9.0.js"></script>
<script src="<?php echo base_url()?>js/bootstrap.min.js"></script>
<script>
  window.intercomSettings = {
    app_id: "xnngu157",
    <?php 
      if(!empty($_SESSION['name'])) echo " name: '$_SESSION[name]',\n";
      if(!empty($_SESSION['email'])) echo " email: '$_SESSION[email]'\n";
    ?>
  };
  </script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/xnngu157';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

</body>

</html>