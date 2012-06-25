<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>
<link href="<?php echo base_url(); ?>css/sections/common/register.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js"></script>
<?php
$sdt=1950;
$edt=date('Y') - 10;
?>
<script>
jQuery(document).ready(function () {
	$('#birthday').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('1990-01-01')});
});
</script>
</script>
</head>
<body>
<div id="container">
  <div id="wraper">
    <div id="main-content">
      
	  <div id="content">
	   <?php
		$message['success'] = $this->session->flashdata('success');
		$message['error'] = $this->session->flashdata('error');
		if(!empty($message['success']) or !empty($message['error'])) { ?>
		<div class="message" id="error-message" <?php echo (!empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
		<div class="message" id="success-message" <?php echo (!empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
		<?php } ?>
	  
        <form method="post" action="<?php echo site_url('common/register')?>"  name="regform" id="regForm" onsubmit="return validate();" >
			<div id="title"><h1>Register and Make A Difference</h1></div>
			
            <div class="content-row-large">
				<span>Name:</span>
				<input type="text" class="textfield" id="name" name="name" value="<?php if(isset($this->validation->name)){ echo $this->validation->name; } ?>" />
				<?php if(!empty($this->validation->name_error)) { ?>
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
				<input type="text" class="textfield" id="phone" name="phone" value="<?php if(isset($this->validation->phone)){ echo $this->validation->phone; } ?>" />
				<?php if(!empty($this->validation->phone_error)) { ?>
				<img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" />
				<?php } ?>
            </div>
            
            <div class="content-row-large"><span>Address:</span>
            <textarea class="textarea" name="address" id="address" rows="5" cols="40"></textarea>
            </div>
            
            <div class="content-row-large"><span>Sex:</span>
            <select class="dropdown" id="sex" name="sex">
				<option value="m">Male</option>
				<option value="f">Female</option>
			</select>
            </div>

				 
            <div class="content-row-large"><span>City:</span>
            <select class="dropdown" id="city_id" name="city_id" onchange="if(this.value==0)location.href='http://hq.makeadiff.in/7-expansions';">
				<?php foreach($cities as $id=>$name) { ?>
				<option value="<?php echo $id ?>" <?php 
					if(!empty($this->validation->city_id) and $this->validation->city_id == $id) echo 'selected="selected"'; 
				?>><?php echo $name ?></option>
				<?php } ?>
				<option value="0">Other</option>
			</select>
            </div>

            <div class="content-row-large"><span>Job Status:</span>
            <select class="dropdown" id="job_status" name="job_status">
				<option value="student">Student</option>
				<option value="working">Working</option>
				<option value="other">Other</option>
			</select>
            </div>

            <div class="content-row-large"><span>Preferred Class Day:</span>
            <select class="dropdown" id="preferred_day" name="preferred_day">
				<option value="flexible">Flexible</option>
				<option value="weekday">Weekdays Only</option>
				<option value="weekend">Weekends Only</option>
			</select>
            </div>

            <div class="content-row-large"><span>Date of Birth:</span>
            <input class="textfield" type="text" id="birthday" name="birthday" />
            </div>

            <div class="content-row-large"><span>Why MAD:</span>
            <textarea class="textarea" name="why_mad" rows="5" cols="40"></textarea>
            </div>

			<div class="content-row-large"><span>Source:</span>
            <select class="dropdown" id="source" name="source">
				<option value="friends">Friends</option>
				<option value="college">College Presentation</option>
				<option value="media">Media</option>
				<option value="internet">Blogs/Facebook</option>
				<option value="other">Other</option>
			</select>
            </div>

            <input type="hidden" name="password" value="pass" />
            <input type="hidden" name="center" value="0" />
            <input type="hidden" name="position" value="" />
            <?php if(!empty($user_id)) { ?><input type="hidden" name="user_id" value="<?php echo $user_id ?>" /><?php } ?>
            
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