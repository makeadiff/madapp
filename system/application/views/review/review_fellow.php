<?php 
$this->load->view('layout/header', array('title' => "Review Parameters for " . $user->name));
?>
<style type="text/css">
#comment-area {
	position:absolute;
	top:200px;
	left:40%;
	width:300px;
	background:#ccc;
	border:1px solid black;
	display:none;
	padding:3px;
}
.data-table a {
	color:#fff !important;
}
</style>
<div id="comment-area">
<form action="" method="post">
<textarea name="comment" id="comment" rows="5" cols="40"></textarea><br />
<input type="button" name="action" value="Cancel" onclick="cancelComment()" />
<input type="button" name="action" value="Save" onclick="saveComment()" style="float:right;" />
<input type="hidden" name="parameter_id" id="parameter_id" />
</form>
</div>

<div id="head" class="clear"><h1>Review Parameters for <?php echo $user->name; ?></h1></div>

<form action="" method="post">
<select name="cycle">
<?php foreach($all_cycles as $key => $cycle_name) { ?>
<option <?php if($key == $cycle) echo "selected"; ?> value="<?php echo $key ?>"><?php echo $cycle_name ?></option>
<?php } ?>
</select>
<input type="submit" name="action" value="Change" />
</form>

<?php if($this->user_auth->get_permission('parameter_calculate')) { ?><a class="btn-primary btn-md" href="#" id="recalculate-parameters">Recalculate</a><?php } ?>

<?php 
$flags = array('nothing', 'black','red','orange','yellow','green');
$last_grouping = '';
foreach (array('pr'=>$parameter_reviews, 'mr' => $milestone_reviews, 'sur' => $survey_reviews) as $key => $reviews) {
	if(!$reviews) continue;

	if($key == 'pr') print "<h2>Core Parameters</h2>";
	elseif($key == 'mr') print "<br /><br /><br /><hr /><h2>Milestone</h2>";
	elseif($key == 'sur') print "<br /><br /><br /><hr /><h2>Happiness Index</h2>";

	?>
	<table class="data-table">
	<tr><th>Parameter</th><th>Level</th><th>Value</th><!-- <th>Data</th> --><th>Comments</th></tr>

	<?php
	$level_sum = 0;
	$filled_milestones_count = 0;
	foreach ($reviews as $item) {
		$level = $item->level;
		if($item->value == -20) {
			$level = 0;
		} else {
			$level_sum += $item->level;
			$filled_milestones_count++;
		}
		$level_round = round($level);

		if($key == 'sur' and $last_grouping != $item->description) {
			$last_grouping = $item->description;
			?>
		<tr class="header"><td colspan="4"><?php echo $item->description ?></td></tr>
		<?php } ?>
<tr class="<?php echo $flags[$level_round]; ?>">
	<td class="parameter-name"><?php echo format($item->name); ?></td>
	<td class="parameter-level"><?php echo format($item->level); ?></td>
	<td class="parameter-value"><?php 
			if($item->input_type == 'manual' and $auth->get_permission('review_edit')) {
				echo "<a href='#' onclick='inputData({$item->id},\"{$item->name}\", \"{$item->value}\", this);' class='with-icon edit'>{$item->value}</a>";
			}
			else echo $item->value;
			
			if($item->input_type == 'percentage') echo '%';
	?></td>

<!-- 	<td class="parameter-data"><?php echo $item->data ?></td> -->
	<td class="parameter-comment"><?php if($auth->get_permission('review_comment')) { ?>
		<a href='#' onclick='comment(<?php echo $item->id ?>);' title='Add Comment' class='icon edit'>Comment</a>
		<?php } ?>
	</td></tr>
<?php }
if($level_sum and $filled_milestones_count) {
$avg = round($level_sum / $filled_milestones_count);
?>
<tr class="<?php echo $flags[$avg]; ?>"><td>Average Level </td><td colspan="3"><?php echo $avg ?></td></tr>
<?php } ?>
</table>

<?php } ?>

<br><br>

<?php
/*
if(!empty($scores)){
    echo "<br /><br /><hr /><h2>MAD 360 </h2>";
    $sum = 0;
    $count = 0;
    foreach($scores as $score) {
        echo $score->question . ' : <strong>Level ' . $score->level . "</strong><br>" . $score->answer . '<br><br>';
        $sum += $score->level;
        $count++;
    }

    echo "Average : " . round($sum/$count,1);
}
*/
?>

<script type="text/javascript">
	var site_url = "<?php echo site_url() ?>";
	var user_id = <?php echo $user->id ?>;
	var cycle = <?php echo $cycle ?>;
</script>
<script type="text/javascript" src="<?php echo base_url() ?>js/sections/review/review_fellow.js"></script>


<?php $this->load->view('layout/footer');