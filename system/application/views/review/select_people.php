<?php 
$this->load->view('layout/header', array('title' => "Select Fellows..."));
?>
<div id="head" class="clear"><h1>Select Fellows...</h1></div>

<p>All fellows in city...</p>
<!--
<form action="<?php echo site_url('review/review_fellows'); ?>" method="post">
<select name="fellow_list" multiple="multiple" style="height:300px;">
<?php foreach ($fellows as $person) { ?>
<option value="<?php echo $person->id; ?>"><?php echo $person->name ?></option>
<?php } ?>
</select><br />
<input type="submit" name="action" value="Review..." />
</form>
<p class="with-icon info">To select multiple fellows, Ctrl+Click on the fellow.</p>
-->

<ul>
<?php foreach ($fellows as $person) { ?>
<li><a href="<?php echo site_url('review/review_fellow/'.$person->id); ?>"><?php echo $person->name ?></a></li>
<?php } ?>
</ul>

<?php $this->load->view('layout/footer');