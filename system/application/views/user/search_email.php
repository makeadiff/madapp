<?php $this->load->view('layout/header', array('title'=>'Search By Email')); ?>

<form action="<?php echo site_url('user/search_email') ?>" method="post">

<label>Email</label><?php echo form_input('email', $email); ?><?php echo form_submit('action', "Search"); ?>
</form>

<?php if($email) { ?>
<h3>Results</h3>
<ul>
<?php foreach($data as $row) { ?>
<li><a href="<?php echo site_url('user/view/'.$row->id) ?>"><?php echo $row->name ?></a></li>
<?php } ?>
</ul>
<? } ?>

<?php $this->load->view('layout/footer'); ?>
