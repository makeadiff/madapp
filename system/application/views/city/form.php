<?php 
 $this->load->view('layout/thickbox_header'); 
if(!isset($city)) $city = array('name'=>'','president_id'=>'', 'id'=>'');
?>

<div id="head" class="clear"><h1><?php echo $action . ' City' ?></h1></div>

<form action="" method="post" class="form-area" id="main">
<ul class="form city-form">
<li>
<label for='name'>City Name</label>
<input type="text" name="name" value="<?php echo set_value('name', $city['name']); ?>" /><br />
</li>
<li>
<label for='president_id'>President</label>
<?php echo form_dropdown('president_id', $president_ids, $city['president_id']); ?><br/>
</li>
</ul>

<?php 
echo form_hidden('id', $city['id']);
//$actionss = Array ("name" => "New","action"=>$action,"value" => "send", "class" => "button green");
echo '<label for="action">&nbsp;</label>';echo form_submit('action',$action);

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

