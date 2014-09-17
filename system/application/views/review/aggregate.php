<?php 
$this->load->view('layout/header', array('title' => "Aggregator!"));
?>
<div id="head" class="clear"><h1>Aggregator!</h1></div>

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

<label>&nbsp;</label><input type="submit" value="Aggregate!" class="button" />
</form>

<?php 
if($data) {
$flags = array('nothing', 'black','red','orange','yellow','green');
$level_sum = 0;
?>
<table class="data-table">
<tr><th>Question</th><th>Answer 1</th><th>Answer 3</th><th>Answer 5</th><th>Level</th></tr>

<?php foreach ($all_questions as $question_id => $question) { 
	$question_info = $data[$question_id];
	$level_sum += $question_info['aggregate_level'];
	?>
<tr class="<?php echo $flags[$question_info['aggregate_level']]; ?>"><td><?php echo $question; ?></td>
<?php foreach(array(1,3,5) as $level) { ?>
<td><?php echo $question_info['level'][$level] . " (".$question_info['level_percentage'][$level]."%)"; ?></td>
<?php } ?>
<td><?php echo $question_info['aggregate_level']; ?></td>
</tr>
<?php 
}

if($level_sum) {
$avg = round($level_sum / count($all_questions));
?>
<tr class="<?php echo $flags[$avg]; ?>"><td>Average Level </td><td colspan="4"><?php echo $avg ?></td></tr>
<?php } ?>
<tr><td>Total Responders</td><td colspan="4"><?php echo $total_answer_count ?></td></tr>
</table>
<?php 
}
$this->load->view('layout/footer');