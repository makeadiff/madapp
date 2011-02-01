<?php $this->load->view('layout/header', array('title'=>'Reports')); ?>

<table>
<tr><?php foreach($fields as $field_name=>$field_title) { ?>
<th><?php echo $field_title ?></th>
<?php } ?></tr>

<?php foreach($data as $row) { ?>
<tr><?php foreach($fields as $field_name=>$field_title) { ?>
<td><?php echo $row->{$field_name} ?></td>
<?php } ?></tr>
<?php } ?>
</table>

<a href="<?php echo site_url('report') ?>">Back</a>

<?php $this->load->view('layout/footer'); ?>
