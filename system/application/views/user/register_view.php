<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Register</title>
<link href="<?php echo base_url(); ?>css/register.css" rel="stylesheet" type="text/css" />
<!--[if IE]><link href="css/ie.css" rel="stylesheet" type="text/css" /><![endif]-->
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
</head>
<body>
<script type="text/javascript">

function getcenter_Name(center)
	{
	 		xmlHttp=GetXmlHttpObject();
            var id=document.getElementById('subjectid');
			
            if (xmlHttp==null)
            {
                alert ("Your browser does not support AJAX!");
                return;
            }
            var url="<?=site_url('common/getcenter_name')?>";
            url=url+"/"+center;
            xmlHttp.onreadystatechange=returnOnValues;
            xmlHttp.open("GET",url,true);
            xmlHttp.send(null);
        }
		
function GetXmlHttpObject()
        {
            var xmlHttp1=new XMLHttpRequest("Microsoft.XMLHTTP");
            if(xmlHttp1!=null)
                return xmlHttp1;
            else
            {
                xmlHttp1=new XMLHttpRequest("Msxml2.XMLHTTP");
                if(xmlHttp1!=null)
                    return null;
            }
        }
function returnOnValues()
	{
           
            if(xmlHttp.readyState==4 && xmlHttp.status==200)
            {
            document.getElementById('center').innerHTML=xmlHttp.responseText;
			}
	}
</script>
<div id="container">
  <div id="wraper">
    <div id="logo"><a href="#"><img src="<?php echo base_url(); ?>images/brilliant.jpg" /></a></div>
    <div id="main-content">
      <div id="title">User Register</div>
	  <div id="content">
        <form method="post" action="<?=site_url('common/register')?>"  name="regform" id="regForm" onsubmit="return validate();" >
            <div class="content-row-large">
                   <span>Name:</span>
                   <input type="text" class="textfield" id="firstname" name="firstname" value="<?php if(isset($this->validation->firstname)){ echo $this->validation->
				   firstname; } ?>" />										
                   <?php if(!empty($this->validation->firstname_error)) { ?>
                   <img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" />
                   <?php } ?>
            </div>
            

            <div class="content-row-large"><span>Email:</span>
                <input type="text" class="textfield" id="email" name="email" value="<?php if(!empty($this->validation->email)){ echo $this->validation->email; } ?>" />
                  <?php if(!empty($this->validation->email_error)) { ?>
                  <img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" />
                  <?php } ?>
            </div>
               

            <div class="content-row-large"><span>Password:</span>
                  	<input type="password" class="textfield" id="password" name="password" />
            </div>

            <div class="content-row-large"><span>Retype Password:</span>
                  	<input type="password" class="textfield" id="repassword" name="repassword" />
                  	<?php if(!empty($this->validation->repassword_error)) { ?>
                  	<img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" />
                  	<?php } ?>
            </div>

            <div class="content-row-large"><span>Mobile No :</span>
                  	<input type="text" class="textfield" id="mobileno" name="mobileno" value="<?php if(isset($this->validation->mobileno)){ echo $this->validation->
				  	mobileno; } ?>" />
                  	<?php if(!empty($this->validation->mobileno_error)) { ?>
                  	<img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" />
                  	<?php } ?>
            </div>
          
				 
            <div class="content-row-large">
                  	<span>City:</span>
                    <select class="dropdown" id="city" name="city" onchange="javascript:getcenter_Name(this.value);">
                    <option value="-1">- Select -</option>
                    <?php $details=$details->result_array(); ?>
                    <?php foreach($details as $row)
		  					{
			  				$cityName=$row['name'];
							$city_id=$row['id'];
					?>
                    <option value="<?php echo $city_id; ?> "><?php echo $cityName; ?></option>
                  	<?php } ?>  
                  	</select>
<!--                    server side validation
-->                    <?php if(isset($city) && $city == '1') { ?>
                	<img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" style="margin-left: -15px;" />
                	<?php } ?>
            </div>
            
             <div class="content-row-large" id="center">
             <span>Center:</span>
                <select class="dropdown" id="center" name="center">
				<option value="-1">- Select -</option>
                </select>
                <?php if(isset($center) && $center == '1') { ?>
<img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" style="margin-left: -15px;" />
<?php } ?>
            </div>
            
            <div class="content-row-large">
                   <span>Position:</span>
                   <input type="text" class="textfield" id="position" name="position" value="<?php if(isset($this->validation->position)){ echo $this->validation->
				   position; } ?>" />
                   <?php if(!empty($this->validation->position_error)) { ?>
                   <img src="<?php echo base_url(); ?>images/not-available.png" title="Not available" />
                   <?php } ?>                   
            </div>
            
            <div class="content-row-reg" style="margin-top: 30px;">
				  <input name="button" type="submit" class="reg-button" id="button" value="Register" />
           	</div>
        </form>
       
</div>
    </div>
  </div>
</div>

<!--<script>
     function validate()
     {
		
        if(document.getElementById("firstname").value == '')
          {		
              alert("Firstname Missing.");
              return false;
          }
       if(document.getElementById("lastname").value == '')
          {
              alert("Lastname Missing.");
              return false;
          }
       if(document.getElementById("username").value == '')
          {
              alert("Username Missing.");
              return false;
          }
       if(document.getElementById("password").value == '')
          {
              alert("Password Missing.");
              return false;
          }
       if(document.getElementById("repassword").value == '')
          {
              alert("Retype Password.");
              return false;
          }
       if(document.getElementById("password").value != document.getElementById("repassword").value)
          {
              alert("Password Mismatch.");
              return false;
          }
       if(document.getElementById("email").value == '')
          {
              alert("Email Missing.");
              return false;
          }
       if(document.getElementById("selectdate").value == '-1' || document.getElementById("selectmonth").value == '-1' || document.getElementById("selectyear").value == '-1')
          {
              alert("Enter a valid DOB.");
              return false;
          }
        if(document.getElementById("radioGender").value == '-1')
          {
              alert("Select a Gender.");
              return false;
          }
        if(document.getElementById("address").value == '')
          {
              alert("Address Missing.");
              return false;
          }
        if(document.getElementById("landline").value == '')
          {
              alert("Parent Number Missing.");
              return false;
          }
        if(document.getElementById("mobileno").value == '')
          {
              alert("Mobile Number Missing.");
              return false;
          }
     }
       
    </script>-->


</body>
</html>