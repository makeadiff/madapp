<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<meta name="keywords" content="" />
<meta name="description" content="" />
<title>Eze apply</title>
<link href="<?php echo base_url()?>css/style.css" rel="stylesheet" type="text/css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/login.css" />
<!--[if IE 6]>
<link rel="stylesheet" type="text/css" href="css/ie6.css" />
<![endif]-->
<script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.4.2.js" ></script>
</head>

<body>
<div id="wraper">
  <div id="container">
    <div id="header">
      <div id="main">
        <div id="logo"><img src="<?php echo base_url()?>images/logo.png" /></div>
        <div id="slogan">Content for  id "slogan" Goes Here</div>
        <div id="session-show">Welcome <a href="#">Guest</a> | <a href="#" class="osx">Login</a></div>
      </div>
      <div id="navigation">
        <div id="left"></div>
        <div id="full">
          <div id="menu">
          	<ul>
                <li><a href="<?php echo site_url('common/index')?>">Home</a></li>
                <li><a href="<?php echo site_url('common/top_schools'); ?>">Top Schools</a></li>
                <li><a href="<?php echo site_url('common/register')?>">Register</a></li>
                <li><a href="#"  class="selected">Login</a></li>
              	<li><a href="<?php echo site_url('common/contact')?>">Contact us</a></li>
                <li><a href="#">FAQ</a></li>
            </ul>
          </div>
        </div>
        <div id="right"></div>
      </div>
    </div>
    <div id="main-body">
      <div id="login-body">
        <div id="Login">
          <div class="row">
            <h2 align="center">Parent Login</h2>
             <div class="field" style="color:#CC0000; text-align:center; margin-top:10px;">
	<?php echo $message;?>
	</div>
          </div>
         
          <?php echo form_open("auth/login");?>
          <div class="row">  
             <label for="username">Username:</label>
      	<?php  echo form_input($username);?>
          </div>
          <div class="row">  
              <label for="password">Password:</label>
      	<?php echo form_input($password);?>
          </div>
          
          <div class="row">
            <label for="remember">Remember Me:</label>
          <div  style=" float:left; margin-left:-50px; margin-top:10px;"></div>
	      <?php echo form_checkbox('remember', '1', FALSE);?>
          </div>
          
          <div class="row">
           <?php echo form_submit('submit', '');?>
          </div>
           <?php echo form_close();?>
        </div>
      </div>
    </div>
    <div id="main-body-bottom"></div>
    <div id="footer">
      <div id="third-row"><span class="left-align">Copyright © 2011 - 2012. All rights reserved</span><span class="right-align">Developed by <a href="http://orisysindia.com" target="_blank">OrisysIndia</a></span></div>
    </div>
  </div>
</div>

</body>
</html>
