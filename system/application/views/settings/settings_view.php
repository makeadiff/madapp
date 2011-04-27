<?php 
$this->load->view('layout/header', array('title' => $action . ' Settings'));

if(!isset($setting)) $setting = array('name'=>'','value'=>'','data'=>'', 'id'=>'');
?>

<div id="head" class="clear"><h1><?php echo $action . ' Setting' ?></h1></div>

<form action="" method="post" class="form-area" id="main">
<div style=" margin-top:10px;">
<label for='name'> Name :</label>
<input type="text"  name="name" value="<?php echo set_value('name', $setting['name']); ?>" /><br />
</div>
<div style=" margin-top:10px;">
<label for='value'>Value :</label>
<input type="text" style="line-height:15px;" name="value" value="<?php echo set_value('name', $setting['value']); ?>" /><br />
</div>
<div style=" margin-top:10px;">
<label for='data'>Data :</label>
<input type="text" style="line-height:15px;" name="data" value="<?php echo set_value('name', $setting['data']); ?>" /><br />
</div>
<div style=" margin-top:10px; margin-left:50px;">
<?php 
echo form_hidden('id', $setting['id']);
echo '<label for="action">&nbsp;</label>';echo form_submit('action', $action);
?>
</div>
</form><br />



<?php $this->load->view('layout/footer');