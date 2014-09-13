<?php
if(function_exists('get_instance')) {
	$ci = &get_instance();
	$user_id = $ci->session->userdata('id');
	if($user_id) $ci->load->view('layout/header', array('title'=>'MADApp :: PHP Error'));
	else $ci->load->view('layout/thickbox_header');
}
?>

<h1><?php echo $heading; ?></h1><br />

<p>There is an error in the application. Our people are looking into it.</p>

<?php echo $message; ?>

<?php
if(function_exists('get_instance')) {
	if($user_id) $ci->load->view('layout/footer');
	else $ci->load->view('layout/thickbox_footer');
}
