<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo empty($title) ? 'MADApp - ' . ucfirst($this->uri->segment(1)) : $title; ?></title>
<?php $this->load->view('layout/css'); ?>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.tablesorter.min.js"></script>
</head>

<body id="pg-<?php echo $this->uri->segment(1) . '-' . $this->uri->segment(2); ?>">
<div class="wrap">

    <!-- HEAD BEGINS -->
    <div class="head">
     <div class="line">	
       <div class="logo left"><a href="<?php echo site_url('dashboard/dashboard_view') ?>">MADAp&#946;</a></div>
       <div class="welcome right">
       <?php

       //To ensure that when city is changed, page is not redirected to dashboard

		if($this->user_auth and $this->user_auth->get_permission('change_city')) {
			$all_cities = idNameFormat($this->db->query("SELECT id, name FROM City ORDER BY name")->result());
			$url = site_url('dashboard/dashboard_view');
			
			if($this->uri->segment(1) == 'kids') $url = site_url('kids/manageaddkids');
			if($this->uri->segment(1) == 'centers') $url = site_url('center/manageaddcenters');
			if($this->uri->segment(1) == 'user') $url = site_url('user/view_users');
			if($this->uri->segment(1) == 'classes') $url = site_url('classes/madsheet');
			
			echo form_open($url);
			echo form_dropdown('city_id', $all_cities, $this->session->userdata('city_id'));
			echo form_dropdown('year', array('2011'=>'2011','2012'=>'2012','2013'=>'2013'), $this->session->userdata('year')); //:HARDCODE:
			echo form_submit('action', "Change");
			echo form_close();
		}
		?>
       
       <a href="<?php echo site_url('auth/logout') ?>" class="tool" style="margin-left: 10px; float:right;">Logout</a>
	<?php echo $this->session->userdata('name');
	$groups = $this->session->userdata('groups');
	if($groups) print ' (' . implode(',', $groups) . ')';
	?></div>
     </div>
     
     <div class="line">
       <h1><?php echo empty($title) ? 'MADApp - ' . ucfirst($this->uri->segment(1)) : $title; ?></h1>
       <?php
		$message['success'] = $this->session->flashdata('success');
		$message['error'] = $this->session->flashdata('error');
		if(!empty($message['success']) or !empty($message['error'])) { ?>
		<div class="message" id="error-message" <?php echo (!empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
		<div class="message" id="success-message" <?php echo (!empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
		<?php } ?>
       <div class="tools right">
       	 <a href="<?php echo site_url('dashboard/dashboard_view') ?>" class="dash <?php if($this->uri->segment(1) == 'dashboard') echo 'active'; ?>" title="Dashboard">Dashboard</a>
         <a href="<?php echo site_url('classes/madsheet') ?>#" class="sheet <?php if($this->uri->segment(2) == 'madsheet') echo 'active'; ?> " title="MAD Sheet">MAD Sheet</a>
         <a href="<?php echo site_url('classes/') ?>#" class="class <?php if($this->uri->segment(1) == 'classes' and ($this->uri->segment(2) == 'index' or !$this->uri->segment(2))) echo 'active'; ?>" title="Classes">Classes</a>
         <a href="<?php echo site_url('user/edit_profile') ?>" class="profile" title="Profile">Profile</a>
         <!-- <a href="#" class="setting" title="Settings">Settings</a> -->
       </div>
     </div>
     
    </div>
    <!-- HEAD ENDS -->
    <!-- BODY BEGINS -->
    <div class="line">
     <div class="main">

     <!-- MODULE BEGINS -->
