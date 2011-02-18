<?php $this->load->view('layout/header', array('title'=>'Cities', 'message'=>$message)); ?>

<div id="head" class="clear"><h1>Cities</h1></div>

<table id="main" class="data-table">
<tr><th>Name</th><th colspan="2">Action</th></tr>
<?php foreach($all_cities as $result) { ?>
<tr><td><?php echo $result->name ?></td>
<td><a href="<?php echo site_url('city/edit/'.$result->id); ?>">Edit</a></td>
</tr>
<?php } ?>
</table>

<?php $this->load->view('layout/footer'); ?>