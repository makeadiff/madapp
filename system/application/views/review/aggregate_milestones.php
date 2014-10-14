<?php 
$this->load->view('layout/header', array('title' => "Aggregate Milestones"));
?>
<div id="head" class="clear"><h1>Aggregate Milestones</h1></div>

<form action="" method="post" class="form-area">
<label for="region_id">Region</label>
<select name="region_id">
<?php foreach ($all_regions as $id => $name) { ?><option value="<?php echo $id ?>" <?php if($id == $region_id) echo 'selected'; ?>><?php echo $name ?></option><?php } ?>
</select><br />

<label for="city_id">City</label>
<select name="city_id">
<?php foreach ($all_cities as $id => $name) { ?><option value="<?php echo $id ?>" <?php if($id == $city_id) echo 'selected'; ?>><?php echo $name ?></option><?php } ?>
</select><br />

<label for="vertical_id">Vertical</label>
<select name="vertical_id">
<?php foreach ($all_verticals as $id => $name) { ?><option value="<?php echo $id ?>" <?php if($id == $vertical_id) echo 'selected'; ?>><?php echo $name ?></option><?php } ?>
</select><br />

<label for="group_type">Users</label>
<select name="group_type">
<?php foreach ($all_types as $id => $name) { ?><option value="<?php echo $id ?>" <?php if($id == $group_type) echo 'selected'; ?>><?php echo $name ?></option><?php } ?>
</select><br />

<label for="status">Status</label>
<select name="status">
<?php foreach ($all_status as $id => $name) { ?><option value="<?php echo $id ?>" <?php if($id == $status) echo 'selected'; ?>><?php echo $name ?></option><?php } ?>
</select><br />


<label>&nbsp;</label><input type="submit" value="Aggregate Milestones" class="button" />
</form><br />

<?php 
if($data) {
$flags = array('nothing', 'black','red','orange','yellow','green');

?>
<table class="data-table">
<tr><th>Count</th><th>Name</th><th>City</th><th>Milestones</th><th>Status</th></tr>
<?php 
$count = 0;
foreach ($data as $row) { $count++; ?>
<tr>
<td><?php echo $count ?></td>
<td><?php echo $row->name ?></td>
<td><?php echo $row->city_name ?></td>
<td><?php echo $row->milestone ?></td>
<td><?php echo $all_status[$row->status] ?></td>
</tr>
<?php } ?>
</table>

<?php
}
$this->load->view('layout/footer');