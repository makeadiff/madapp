<?php 
$ci = &get_instance();
$user_id = $ci->session->userdata('id');
if($user_id) $ci->load->view('layout/header', array('title'=>'MADApp :: PHP Error'));
else $this->load->view('layout/thickbox_header');
?>

<h1><?php echo $heading; ?></h1>

<p>There is an error in the application. Our people are looking into it.</p>

<?php echo $message; ?>

<?php
if($user_id) $this->load->view('layout/footer');
else $this->load->view('layout/thickbox_footer');
