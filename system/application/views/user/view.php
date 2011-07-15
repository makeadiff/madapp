<?php $this->load->view('layout/header', array('title'=>'Volunteer details for ' . $user->name)); ?>


<?php if($user->photo) { ?><img src="<?php echo base_url().'pictures/'.$photo; ?>" width="100" style="float:left;" height="100" /><?php } ?>

<h2><a href="mailto:<?php echo $user->email ?>;"><?php echo $user->name ?></h2>

<p>Phone: <strong><?php echo $user->phone ?></strong></p>

<p><?php echo $user->address; ?></p>

<p>Chapter : <strong><?php echo $all_cities[$user->city_id] ?></strong></p>

<p>Joined On : <strong><?php echo $user->joined_on; ?></strong></p> 

<?php if($user->left_on != '0000-00-00') { ?><p> Left : <strong><?php echo $user->left_on; ?></strong></p><?php } ?>

<p>User Type : <strong><?php echo ucfirst($user->user_type); ?></strong></p>

<?php $this->load->view('layout/footer'); ?>