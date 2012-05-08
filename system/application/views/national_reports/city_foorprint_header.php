<?php $this->load->view('layout/header', array('title'=>'National Reports')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/hide_sidebar.css">

<div id="head" class="clear"><h1>National Reports</h1></div>

<table id="main" class="data-table">
<tr><?php foreach($fields as $field_name=>$field_title) { ?>
<th><?php echo $field_title ?></th>
<?php } ?></tr>
