<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>
<link href="<?php echo base_url(); ?>css/sections/common/register.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script src="<?php echo base_url();?>js/datepicker.js" type="text/javascript"></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/calender.css" />
<script src="<?php echo base_url()?>js/cal.js" type="text/javascript"></script>
<?php
$sdt=1950;
$edt=date('Y') - 10;
?>
<script type="text/javascript">
jQuery(document).ready(function () {
	$('#birthday').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('1990-01-01')});
});
</script>
</head>
<body>
<div id="container">
  <div id="wraper">
    <div id="main-content">
      
	  <div id="content">
	   <?php
		if($this->session->flashdata('success')) $message['success'] = $this->session->flashdata('success');
		if($this->session->flashdata('error')) $message['error'] = $this->session->flashdata('error');
		if(!empty($error)) $message['error'] = $error;
		
		if(!empty($message['success']) or !empty($message['error'])) { ?>
		<div class="message" id="error-message" <?php echo (!empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
		<div class="message" id="success-message" <?php echo (!empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
		<?php } ?>
	  
        <form method="post" action="<?php echo site_url('common/register')?>"  name="regform" id="regForm" onsubmit="return validate();" >
			<div id="title"><h1>Register and Make A Difference</h1></div>
			
            <div class="content-row-large">
				<span>Name:</span>
				<input type="text" class="textfield" id="name" name="name" value="<?php if(isset($this->validation->name)){ echo $this->validation->name; } ?>" /><?php 
				if(!empty($this->validation->name_error)) { ?><img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" /><?php } ?>
            </div>

            <div class="content-row-large"><span>Email:</span>
                <input type="text" class="textfield" id="email" name="email" value="<?php if(!empty($this->validation->email)){ echo $this->validation->email; } ?>" /><?php 
                if(!empty($this->validation->email_error)) { ?><img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" /><?php } ?>
            </div>

            <div class="content-row-large"><span>Phone:</span>
				<input type="text" class="textfield" id="phone" name="phone" value="<?php if(isset($this->validation->phone)){ echo $this->validation->phone; } ?>" /><?php 
				if(!empty($this->validation->phone_error)) { ?><img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" /><?php } ?>
            </div>
            
            <div class="content-row-large"><span>Address:</span>
            <textarea class="textarea" name="address" id="address" rows="5" cols="40"><?php if(isset($_POST['address'])) echo $_POST['address']; ?></textarea>
            </div>
            
            <div class="content-row-large"><span>Sex:</span>
            <select class="dropdown" id="sex" name="sex">
				<option value="m" <?php if(isset($_POST['sex']) and $_POST['sex'] == 'm') echo 'selected="selected"'; ?>>Male</option>
				<option value="f" <?php if(isset($_POST['sex']) and $_POST['sex'] == 'f') echo 'selected="selected"'; ?>>Female</option>
			</select>
            </div>

            <div class="content-row-large"><span>City:</span>
            <select class="dropdown" id="city_id" name="city_id" onchange="if(this.value==0)location.href='http://hq.makeadiff.in/7-expansions';">
				<option value="">Select City</option>
				<?php foreach($cities as $id=>$name) { ?>
				<option value="<?php echo $id ?>" <?php 
					if(!empty($this->validation->city_id) and $this->validation->city_id == $id) echo 'selected="selected"'; 
				?>><?php echo $name ?></option>
				<?php } ?>
				<option value="0">Other</option>
			</select><?php 
				if(!empty($this->validation->city_id_error)) { ?><img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" /><?php } ?>
            </div>

            <div class="content-row-large"><span>Job Status:</span>
            <select class="dropdown" id="job_status" name="job_status">
				<option value="student" <?php if(isset($_POST['job_status']) and $_POST['job_status'] == 'student') echo 'selected="selected"'; ?>>Student</option>
				<option value="working" <?php if(isset($_POST['job_status']) and $_POST['job_status'] == 'working') echo 'selected="selected"'; ?>>Working</option>
				<option value="other" <?php if(isset($_POST['job_status']) and $_POST['job_status'] == 'other') echo 'selected="selected"'; ?>>Other</option>
			</select>
            </div>
            
            <!--
            <div class="content-row-large"><span>Preferred Class Day:</span>
            <select class="dropdown" id="preferred_day" name="preferred_day">
				<option value="flexible" <?php if(isset($_POST['preferred_day']) and $_POST['preferred_day'] == 'flexible') echo 'selected="selected"'; ?>>Flexible</option>
				<option value="weekday" <?php if(isset($_POST['preferred_day']) and $_POST['preferred_day'] == 'weekday') echo 'selected="selected"'; ?>>Weekdays Only</option>
				<option value="weekend" <?php if(isset($_POST['preferred_day']) and $_POST['preferred_day'] == 'weekend') echo 'selected="selected"'; ?>>Weekends Only</option>
			</select>
            </div>
            -->
            
            <div class="content-row-large"><span>Which role would you like to apply to:<br /><br /><br /></span>
            <input class="checkbox" type="checkbox" id="english_teacher" name="english_teacher" value="1" <?php if(isset($_POST['english_teacher'])) echo "checked='checked'"; ?> />
            <label for="english_teacher">English Teacher</label><br />
            
            <input class="checkbox" type="checkbox" id="dream_tee" name="dream_tee" value="1" <?php if(isset($_POST['dream_tee'])) echo "checked='checked'"; ?> />
            <label for="dream_tee">Dream Tee Volunteer</label><br />
            
            <input class="checkbox" type="checkbox" id="events" name="events" value="1" <?php if(isset($_POST['events'])) echo "checked='checked'"; ?> />
            <label for="events">Events Volunteer</label><br />
            
            <input class="checkbox" type="checkbox" id="placements" name="placements" value="1" <?php if(isset($_POST['placements'])) echo "checked='checked'"; ?> />
            <label for="placements">Placements Volunteer</label><br />
            <p>To find out more about the profiles, visit <a href="http://striking.ly/madvol">http://striking.ly/madvol</a>.</p>
            </div>

            <div class="content-row-large"><span>Date of Birth:</span>
            <input class="textfield" type="text" id="birthday" name="birthday" value="<?php if(isset($_POST['birthday'])) echo $_POST['birthday']; ?>" />
            </div>

            <div class="content-row-large"><span>Why MAD:</span>
            <textarea class="textarea" name="why_mad" rows="5" cols="40"><?php if(isset($_POST['why_mad'])) echo $_POST['why_mad']; ?></textarea>
            </div>

			<div class="content-row-large"><span>Source:</span>
            <select class="dropdown" id="source" name="source">
				<option value="friends" <?php if(isset($_POST['source']) and $_POST['source'] == 'friends') echo 'selected="selected"'; ?>>Friends</option>
				<option value="college" <?php if(isset($_POST['source']) and $_POST['source'] == 'college') echo 'selected="selected"'; ?>>College Presentation</option>
				<option value="media" <?php if(isset($_POST['source']) and $_POST['source'] == 'media') echo 'selected="selected"'; ?>>Media</option>
				<option value="internet" <?php if(isset($_POST['source']) and $_POST['source'] == 'internet') echo 'selected="selected"'; ?>>Blogs/Facebook</option>
				<option value="other" <?php if(isset($_POST['source']) and $_POST['source'] == 'other') echo 'selected="selected"'; ?>>Other</option>
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