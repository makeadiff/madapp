<?php 
$ci = &get_instance();
if(isset($ci->session)) {
	$user_id = $ci->session->userdata('id');
	if($user_id) $ci->load->view('layout/header', array('title'=>'MADApp :: Database Error'));
}
else $ci->load->view('layout/thickbox_header');
?>

<h1><?php echo $heading; ?></h1><br />

<p>There is an error in the application. Our people are looking into it.</p>

<?php echo $message; ?>

<?php
if($user_id) $ci->load->view('layout/footer');
else $ci->load->view('layout/thickbox_footer');
