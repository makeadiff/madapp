<?php $this->load->view('layout/header', array('title'=>'Reports')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/hide_sidebar.css">

<div id="head" class="clear"><h1>Reports</h1></div>

<table id="main" class="data-table">
<tr>
<th>#</th>
<?php foreach($fields as $field_name=>$field_title) { ?>
<th><?php echo $field_title ?></th>
<?php } ?></tr>

<?php $count = 1; foreach($data as $row) { ?>
<tr>
<td><?php echo $count; $count++; ?></td>
<?php 
foreach($fields as $field_name=>$field_title) { ?>
<td><?php 
if($fields[$field_name] == 'Name' and isset($row->user_id)) echo '<a href="'.site_url('user/view/'.$row->user_id).'">';
echo $row->{$field_name};
if($fields[$field_name] == 'Name' and isset($row->user_id)) echo '</a>';
?></td>
<?php } ?></tr>
<?php } ?>
</table>

<a href="<?php echo site_url('report') ?>">Back</a>

<?php $this->load->view('layout/footer'); ?>
