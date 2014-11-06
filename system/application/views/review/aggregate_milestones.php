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


<label>&nbsp;</label><input type="submit" name="action" value="Aggregate Milestones" class="button" />
</form><br />

<?php 
if($data) {
$flags = array('nothing', 'black','red','orange','yellow','green');

?>
<table class="data-table">
<tr><th>Count</th><th>Name</th><th>City</th><th>Milestones</th><th>Date Due</th><th>Date Completed</th><th>Status</th>
<?php if($this->user_auth->get_permission('review_milestone_edit')) { ?><th>Edit</th> <?php } ?> </tr>
<?php 
$count = 0;
foreach ($data as $row) { $count++; ?>
<tr>
<td><?php echo $count ?></td>
<td><a href="<?php echo site_url('user/popupEditusers/'.$row->user_id); ?>"><?php echo $row->name ?></a></td>
<td><?php echo $row->city_name ?></td>
<td><?php echo $row->milestone ?></td>
<td><?php echo date('j-M ',strtotime($row->due_on))?></td> 
<td><?php if($row->status) echo date('j-M ',strtotime($row->done_on)); ?></td>
<td><?php if($row->status == '0'  and  $row->due_on <= date('Y-m-d')) echo "Not completed";
			else echo $all_status[$row->status]; ?></td>
<?php if($this->user_auth->get_permission('review_milestone_edit')) { ?><td><a class="with-icon edit popup" href="<?php echo site_url('review/edit_milestone/' . $row->id); ?>">Edit</a></td><?php } ?>
</tr>
<?php } ?>
</table>

<?php
}
$this->load->view('layout/footer');