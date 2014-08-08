<?php 
$this->load->view('layout/header', array('title' => "Select People..."));
?>
<div id="head" class="clear"><h1>Select People...</h1></div>

<form action="" method="post">
<label for="name">Name</label>
<input type="text" name="name" id="name" value="<?php echo $this->input->post('name'); ?>" />
<input type="submit" name="action" value="Search" />
</form>

<ul>
<?php foreach ($people as $person) { ?>
<li><a href="<?php echo site_url('review/list_milestones/'.$person->id); ?>"><?php echo $person->name ?></a></li>
<?php } ?>
</ul>

<?php $this->load->view('layout/footer');