<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head id="ctl00_Head1">
<title><?php echo empty($title) ? 'MADApp - ' . ucfirst($this->uri->segment(1)) : $title; ?></title>
<?php $this->load->view('layout/css'); ?>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/application.js"></script>
</head>

<body>

<table width="100%" border="0" cellspacing="0" cellpadding="0">
	<tr>
<td>
<table width="100%" border="0" cellpadding="0" cellspacing="0">

<tbody>
<tr>
<td width="30%" align="left" valign="top" class="left_bg">
	&nbsp;
</td>
<td valign="top" width="40%" align="left">
<!-- BEGIN: Main Container -->
<div id="container_main">
	<div id="main_warp">

		<!-- BEGIN: Header -->
		<div id="header">
									
<!-- BEGIN: Logo -->
<div id="logo">
    <h1>
        <a href="<?php echo site_url('dashboard/dashboard_view') ?>"><span>MAD camp</span></a></h1>
</div>
<!-- END: Logo -->
<!-- BEGIN: Right Header -->
<div id="ctl00_MenuHeader1_header_right">
    <!-- BEGIN: Top Menu -->

    <div id="top_nav">    
    <a href="<?php echo site_url('auth/logout') ?>" class="button tool" style="margin-left: 10px; float:right;">Logout</a><a href="<?php echo site_url('user/edit_profile') ?>" class="button tool" style="margin-left: 10px; float:right;">Edit Profile</a>
	<a href="#"><?php echo $this->session->userdata('name'); ?></a> <?php
	$groups = $this->session->userdata('groups');
	if($groups) print ' (' . implode(',', $groups) . ')';
	?>
    </div>
    <!-- END: Top Menu -->
    <!-- BEGIN: Main Menu -->
    <div id="nav_container">
        <ul>
			<li<?php if($this->uri->segment(1) == 'dashboard') { ?> class="active"<?php } ?>><a href="<?php echo site_url('dashboard/dashboard_view') ?>">Dashboard</a></li>
			<li<?php if($this->uri->segment(1) == 'classes' and ($this->uri->segment(2) == 'index' or !$this->uri->segment(2))) { ?> class="active"<?php } ?>><a href="<?php echo site_url('classes/') ?>">Your Classes</a></li>
			<li<?php if($this->uri->segment(2) == 'madsheet') { ?> class="active"<?php } ?>><a href="<?php echo site_url('classes/madsheet') ?>">MAD Sheet</a></li>
        </ul>
        <div class="clear">
        </div>
    </div>

    <!-- END: Main Menu -->
</div>
<!-- END: Right Header -->
<div class="clear">
</div>

<!-- BEGIN: Sub Menu -->
<div id="sub_menu">
	
	<div class="menu_left">
<?php
$message['success'] = $this->session->flashdata('success');
$message['error'] = $this->session->flashdata('error');
if(!empty($message['success']) or !empty($message['error'])) { ?>
<div id="error-message" <?php echo (!empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
<div id="success-message" <?php echo (!empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
<?php } ?>
	</div>

		<div class="clear"></div>
		</div>

		<!-- END: Sub Menu -->
	</div>
	<div class="clear">                                                
	</div>
</div>

<!-- END: Header -->
<!-- BEGIN: Middle Container -->
<div id="main_middle_container" style="min-height:500px" >
<div id="main-content">

