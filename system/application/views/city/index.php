<?php $this->load->view('layout/header', array('title'=>'Cities', 'message'=>$message)); ?>

<div id="head" class="clear"><h1>Cities</h1>

<?php if($this->user_auth->get_permission('city_create')) { ?>
<div id="actions"> 
<a href="<?= site_url('city/create')?>" class="button primary" id="example" name="Add City">Add City</a>
</div>
<?php } ?>
</div>

<table id="main" class="data-table tablesorter info-box-table">
<thead><tr><th>Name</th><th colspan="2">Action</th></tr></thead>
<?php foreach($all_cities as $result) { ?>
<tr><td><?php echo $result->name;
	if($result->problem_count) print "<span class='warning icon'>!</span>";
	?><div class="center-info info-box"><ul><li><?php
		print implode('</li><li>', $result->information);
	?></li></ul></div></td>
<td><a href="<?php echo site_url('city/edit/'.$result->id); ?>" class="edit with-icon">Edit</a></td>
</tr>
<?php } ?>
</table>

<?php $this->load->view('layout/footer'); ?>