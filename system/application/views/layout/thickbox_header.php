<html>
<head>
<title><?php echo empty($title) ? 'MADApp - ' . ucfirst($this->uri->segment(1)) : $title; ?></title>
<?php $this->load->view('layout/css',array('thickbox'=>true)); ?>
<script type="text/javascript" src="<?php echo base_url()?>js/iframe.js"></script>
</head>
<body class="sidebar-form">

<h3><?php echo empty($title) ? 'MADApp - ' . ucfirst($this->uri->segment(1)) : $title; ?></h3>