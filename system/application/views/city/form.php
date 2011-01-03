<?php 
$this->load->view('layout/header', array('title' => $action . ' City'));

if(!isset($city)) $city = array('name'=>'','president_id'=>'', 'id'=>'');
?>

<h1><?php echo $action . ' City' ?></h1>

<form action="" method="post" class="form-area">
<label for='name'>City Name</label>
<input type="text" name="name" value="<?php echo set_value('name', $city['name']); ?>" /><br />

<label for='president_id'>President</label>
<?php echo form_dropdown('president_id', $president_ids, $city['president_id']); ?><br/>

<?php 
echo form_hidden('id', $city['id']);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action);
?>
</form><br />

<?php if($action == 'Edit') { ?>
<div class="more-links">
<ul>
<li><a href="<?php echo base_url() ?>index.php/center/index/city/<?php echo $city['id'] ?>">Centers in <?php echo $city['name'] ?></a></li>
<li><a href="<?php echo base_url() ?>index.php/user/index/city/<?php echo $city['id'] ?>">Volunteers in <?php echo $city['name'] ?></a></li>
</ul>
</div>
<?php } ?>

<?php $this->load->view('layout/footer');