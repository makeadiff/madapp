<html>
<head>
<title><?php echo empty($title) ? 'MADApp - ' . ucfirst($this->uri->segment(1)) : $title; ?></title>

<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
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

<!--
<li<?php if($this->uri->segment(1) == 'center') { ?> class="active"<?php } ?>><a href="<?php echo site_url('center/manageaddcenters') ?>">Add Centers</a></li>
<li<?php if($this->uri->segment(1) == 'kids') { ?> class="active"<?php } ?>><a href="<?php echo site_url('kids/manageaddkids') ?>">Add kids</a></li>
<li<?php if($this->uri->segment(1) == 'exam') { ?> class="active"<?php } ?>><a href="<?php echo site_url('exam/exam_score') ?>">Exam Scores</a></li>
-->
</ul>
</div>

<div id="toolbar" class="clear">
<div id="buttons"><a href="<?php echo site_url('auth/logout') ?>" class="button tool" style="margin-left: 120px;">Logout</a></div>
<p id="user"><a href="#"><?php echo $this->session->userdata('name'); ?></a> (<?php echo implode(',', $this->session->userdata('groups')) ?>)</p>
</div>
</div>

<div id="content">
<?php if(isset($message)) { ?>
<div id="error-message" <?php echo ($message['error']) ? '':'style="display:none;"';?>><?php echo $message['error'] ?></div>
<div id="success-message" <?php echo ($message['success']) ? '':'style="display:none;"';?>><?php echo $message['success'] ?></div>
<?php } ?>
