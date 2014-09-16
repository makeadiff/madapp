<?php 
$this->load->view('layout/header', array('title' => "Review Sheet: Select Person..."));
?>
<div id="head" class="clear"><h1>Review Sheet: Select Person...</h1></div>

<h3>Select Person...</h3>
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
<li><a href="<?php echo site_url('review/review_fellow/'.base64_encode($person->id).'/1/no360'); ?>"><?php echo $person->name ?></a></li>
<?php } ?>
</ul>

<?php $this->load->view('layout/footer');