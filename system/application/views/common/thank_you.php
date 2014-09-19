<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Thank You</title>
<style type="text/css">
body {
	background:url('<?php echo base_url() ?>images/madapp/red_background.png');
	text-align:center;
	font-family:Arial, Helvitica, sans-serif;
}
#container {
	margin:100px auto;
	text-align:left;
	height:300px;
	width:700px;
	background:#e5e5e5;
	padding:40px;
}
h3 {
	text-transform:uppercase;
}
#main {
	color:#e4003d;
	font-size:32px;
}
#info {
	font-weight:bold;
}

</style>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
</head>

<body>

<div id="container">
<h3>Thank you for choosing to Make a Difference</h3>

<p id="main">1200 new MADsters will join us this year.<br />
And yours is the <?php echo ordinal($reg_count) ?> registration.</p>

<p id="info">We recruit only twice a year.<br />
We will definitely mail you before our next recruitment drive.<br />
Looking forward to having you onboard.</p>

<p id="more">For updates on future recruitment workshop in your city, join our <a href="http://facebook.com/makeadiff">FB Page</a>.</p>

</div>

</body>
</html>
<?php
function ordinal($cdnl){
    $test_c = abs($cdnl) % 10;
    $ext = ((abs($cdnl) %100 < 21 && abs($cdnl) %100 > 4) ? 'th'
            : (($test_c < 4) ? ($test_c < 3) ? ($test_c < 2) ? ($test_c < 1)
            ? 'th' : 'st' : 'nd' : 'rd' : 'th'));
    return $cdnl.'<sup>'.$ext.'</sup>';
}
