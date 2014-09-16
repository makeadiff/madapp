<?php 
$this->load->view('layout/header', array('title' => "Review Sheet: Select Person..."));
?>
<div id="head" class="clear"><h1>Review Sheet: Select Person...</h1></div>

<h3>Select Person...</h3>

<!-- <form action="" method="post">
<input type="text" name="name" value="<?php echo isset($_REQUEST['name']) ? $_REQUEST['name'] : ''; ?>" />
<input type="submit" name="action" value="Search..." />
</form> -->

<table class="data-table">
<tr><th>Name</th><th>City</th><th>Region</th><th>Vertical</th><th>Group</th></tr>
<?php foreach ($fellows as $person) { ?>
<tr>
<td><a href="<?php echo site_url('review/review_fellow/'.base64_encode($person->id).'/1/no360'); ?>"><?php echo $person->name ?></a></td>
<td><?php echo $person->city_name ?></td>
<td><?php echo $person->region_id ?></td>
<td><?php echo $person->vertical_id ?></td>
<td><?php echo $person->group_name ?></td>
</tr>
<?php } ?>
</table>

<?php $this->load->view('layout/footer');