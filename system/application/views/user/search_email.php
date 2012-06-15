<?php $this->load->view('layout/header', array('title'=>'Search By Email')); ?>

<form action="<?php echo site_url('user/search_email') ?>" method="post">

<label>Email</label><?php echo form_input('email', $email); ?><br />
<label>Phone</label><?php echo form_input('phone', $phone); ?><br /><?php echo form_submit('action', "Search"); ?>
</form>

<?php if($email or $phone) { ?>
<h3>Results</h3>
<ul>
<?php foreach($data as $row) { ?>
<li><a href="<?php echo site_url('user/view/'.$row->id) ?>"><?php echo $row->name ?></a></li>
<?php } ?>
</ul>
<? } ?>

<?php $this->load->view('layout/footer'); ?>
