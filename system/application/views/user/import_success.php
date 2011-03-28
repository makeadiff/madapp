<?php 
$this->load->view('layout/header', array('title' => "Data Imported"));
?>
<div id="head" class="clear"><h1>Data Imported</h1></div>

<ul>
<li><a href="<?php echo site_url('user/view_users'); ?>">View Users</a></li>
<li><a href="<?php echo site_url('user/import'); ?>">Import More Users</a></li>
</ul>

<?php $this->load->view('layout/footer');