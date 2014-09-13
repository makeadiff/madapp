<?php 
$this->load->view('layout/header', array('title' => "Timeframes..."));
?>
<div id="head" class="clear"><h1>Timeframes...</h1></div>

<?php
$count = 0;
foreach($timeframes as $tf) {
	$count++;
	?>
<div class="tile count-<?php echo $count ?>"><a href="<?php echo site_url('review/list_milestones/'.$user_id.'/'.$tf->due_timeframe); ?>"><?php 
	echo $all_timeframes[$tf->due_timeframe] ?></a></div>
<?php } ?>

<br />
<a href="<?php echo site_url('review/list_milestones/'.$user_id) ?>">List All Milestones</a>

<?php $this->load->view('layout/footer');