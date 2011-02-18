<?php $this->load->view('layout/header', array('title'=>'Reports')); ?>

<div id="head" class="clear"><h1>Report</h1></div>
<br />

<a href="<?php echo site_url('report/users_with_low_credits') ?>">Show volunteers with low credits</a><br />
<a href="<?php echo site_url('report/absent') ?>">Show volunteers who were absent without a substitute</a><br />

<?php $this->load->view('layout/footer'); ?>