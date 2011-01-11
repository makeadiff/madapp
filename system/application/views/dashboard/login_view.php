<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" lang="en" dir="ltr">
<!-- Mirrored from miniplaneta.pl/encoreadmin/rc/2/3.html by HTTrack Website Copier/3.x [XR&CO'2010], Sat, 30 Oct 2010 16:18:28 GMT -->
<head>

<title>madapp Admin Login</title>

</head>
<body id="pageLogin">
<div id="login" class="centerbox">
<h1>Madapp <span>| Admin Panel</span></h1>
<h2>Admin login</h2>
<div class="boxInside">

<?php if($error != ''){?>
<div id="msgError" class="message clear"><?php echo $error; ?></div>
<?php } ?>

<?php echo form_open('common/login'); ?>
<fieldset>
	<legend>Login</legend>
    
    <div class="field"> 
    	<label for="txtUsername">Username:</label> <input  name="username" class="text" type="text" />
    </div>
    
    <div class="field"> 
    	<label for="txtPassword">Password:</label> <input name="password"  class="text" type="password" />
    </div>
    
    <div id="loginActions" class="clear"> 
    	<input id="btnLogin" class="button primary" type="submit" value="Login" /> 
	</div>
</fieldset>
</form>

</div>
</div>
</body>

</html>