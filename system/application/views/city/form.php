<?php $this->load->view('layout/thickbox_header'); ?>
 <h2>Add/Edit City</h2>
 <?php
if(!isset($city)) $city = array('name'=>'','id'=>'');
?>

<div id="head" class="clear"><h1><?php echo $action . ' City' ?></h1></div>

<form action="" method="post" class="form-area" id="main">
<ul class="form city-form">
<li>
<label for='name'>City Name</label>
<input type="text" name="name" value="<?php echo set_value('name', $city['name']); ?>" /><br />
</li>
</ul>

<?php 
echo form_hidden('id', $city['id']);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action, 'class="button green"');

?>
</form><br />

<?php if($action == 'Edit') { ?>
<div class="more-links">
<ul>
<li><a href="<?php echo site_url('center/manageaddcenters/city/'.$city['id']) ?>">Centers in <?php echo $city['name'] ?></a></li>
<li><a href="<?php echo site_url('user/index/city/'.$city['id']); ?>">Volunteers in <?php echo $city['name'] ?></a></li>
</ul>
</div>
<?php } ?>

