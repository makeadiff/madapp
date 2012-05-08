<?php
$ci = &get_instance();
$ci->load->view('layout/header', array('title'=>'MADApp :: 404 - Page Not found')); ?>

<h1><?php echo $heading; ?></h1><br /><br />

<p>Please check the URL you used.</p>

<?php echo $message; ?>

<?php $ci->load->view('layout/footer');
