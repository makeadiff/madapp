<?php $this->load->view('layout/header', array('title'=>'Search For People')); ?>

<form action="<?php echo site_url('user/search_email') ?>" method="post" class="form-area">

<label>ID</label><?php echo form_input('id', $id); ?><br />
<label>Name</label><?php echo form_input('name', $name); ?><br />
<label>Email</label><?php echo form_input('email', $email); ?><br />
<label>Phone</label><?php echo form_input('phone', $phone); ?><br />

<label>&nbsp;</label><?php echo form_submit('action', "Search"); ?>
</form><br />

<?php if($data) { ?>
<h3>Results</h3>
<ul>
<?php foreach($data as $row) { ?>
<li><a href="<?php echo site_url('user/view/'.$row->id) ?>"><?php echo $row->name ?></a></li>
<?php } ?>
</ul>
<? } ?>

<?php $this->load->view('layout/footer'); ?>
