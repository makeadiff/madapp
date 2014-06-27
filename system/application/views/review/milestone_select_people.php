<?php 
$this->load->view('layout/header', array('title' => "Select People..."));
?>
<div id="head" class="clear"><h1>Select People...</h1></div>

<ul>
<?php foreach ($people as $person) { ?>
<li><a href="<?php echo site_url('review/list_milestones/'.$person->id); ?>"><?php echo $person->name ?></a></li>
<?php } ?>
</ul>

<?php $this->load->view('layout/footer');