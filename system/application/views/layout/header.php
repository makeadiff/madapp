<html>
<head>
<title><?php echo empty($title) ? 'MADApp - ' . ucfirst($this->uri->segment(1)) : $title; ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/thickbox.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/application.js"></script>
</head>

<body>
<div id="wrapper">

<div id="top">
<div id="title" class="clear"><a href="<?= site_url('dashboard/dashboard_view') ?>">MADApp</a> V2.0<sup>beta</sup></div>
<div id="menu" class="clear">
<ul>
<li<?php if($this->uri->segment(1) == 'dashboard') { ?> class="active"<?php } ?>><a href="<?php echo site_url('dashboard/dashboard_view') ?>">Dashboard</a></li>
<li<?php if($this->uri->segment(1) == 'classes' and ($this->uri->segment(2) == 'index' or !$this->uri->segment(2))) { ?> class="active"<?php } ?>><a href="<?php echo site_url('classes/') ?>">Your Classes</a></li>
<li<?php if($this->uri->segment(2) == 'madsheet') { ?> class="active"<?php } ?>><a href="<?php echo site_url('classes/madsheet') ?>">MAD Sheet</a></li>

</ul>
</div>

<div id="toolbar" class="clear">
<div id="buttons"><a href="<?php echo site_url('auth/logout') ?>" class="button tool" style="margin-left: 120px;">Logout</a></div>
<p id="user"><a href="#"><?php echo $this->session->userdata('name'); ?></a><?php
	$groups = $this->session->userdata('groups');
	if($groups) print ' (' . implode(',', $groups) . ')';
?></p>
</div>
</div>

<div id="content">
<?php if(!empty($message)) { ?>
<div id="error-message" <?php echo (empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
<div id="success-message" <?php echo (empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
<?php } ?>
