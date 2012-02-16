<?php $this->load->view('layout/header', array('title'=>$title)); ?>

<div id="head" class="clear">
<h1><?php echo $title ?></h1>

<table class="data-table">
<tr><th>Exam Name</th><th>Level</th><th>Date</th><th>Scores</th></tr>
<?php foreach($events as $e) { ?>
<tr><td><?php echo $e->name ?></td><td><?php echo ucfirst($e->level) ?></td>
<td><?php echo date('dS M, Y', strtotime($e->exam_on)); ?></td>
<td><a href="<?php echo site_url('exam/view_scores/'.$e->id) ?>">View Scores</a></td></tr>
<?php } ?>
</table>

<?php $this->load->view('layout/footer');