<?php $this->load->view('layout/header', array('title'=>'Reports')); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/actions/hide_sidebar.css">

<div id="head" class="clear"><h1>Reports</h1></div>

<table id="main" class="data-table">
<tr>
<th>#</th>
<?php foreach($fields as $field_name=>$field_title) { ?>
<th><?php echo $field_title ?></th>
<?php
}
if(isset($devcon_title)) {
	print "<th>$devcon_title</th>";
}
?></tr>

<?php $count = 1; foreach($data as $row) { ?>
<tr>
<td><?php echo $count; $count++; ?></td>
<?php 
foreach($fields as $field_name=>$field_title) { ?>
<td><?php 
if($fields[$field_name] == 'Name' and isset($row->user_id)) echo '<a href="'.site_url('user/view/'.$row->user_id).'">';
if (isset($row->{$field_name})) {
    echo $row->{$field_name};
}else if($title == 'Child Count'){
    echo "Total";
}else{
    echo "0";
}
if($fields[$field_name] == 'Name' and isset($row->user_id)) echo '</a>';
?></td>
<?php 
}

if(isset($devcon)) { ?>
<td><a class="with-icon phone ajaxify ajaxify-replace" href="<?php 
	echo site_url("report/ajax_update_count/".$row->user_id."/developmental_conversation_for_low_credits_count/" . ($devcon[$row->user_id] + 1)) ?>" title="Increment Call Count"><?php
	if($devcon[$row->user_id] == 0) echo "Not Called";
	elseif ($devcon[$row->user_id] == 1) echo "Called once";
	else echo "Called " . $devcon[$row->user_id] . " times";
	?></a>
</td>
<?php } ?></tr>
<?php } ?>
</table>

<a href="<?php echo site_url('report') ?>">Back</a>
<script type="text/javascript" src="<?php echo base_url()?>js/libraries/ajaxify.js"></script>
<?php
$this->load->view('layout/footer');
