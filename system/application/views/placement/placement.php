<link href="<?php echo base_url(); ?>css/dashboard.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/libraries/ajaxify.js"></script>

<div id="content" class="clear">
<div id="main" class="clear">
	<div id="head" class="clear" style="border-bottom:none;">
	<div style="font-size:14px;background-color:#FFF89D;height:15px;padding-top:18px;padding-bottom:20px;padding-left:10px;">
		Welcome, <a class="popup" href="<?php echo site_url('user/view/'.$current_user->id); ?>"><?php echo $this->session->userdata('name'); ?></a>. 
			<?php if(in_array(9, array_keys($this->session->userdata('groups')))) { ?>Current Credits: <strong><?php echo $current_user->credit ?></strong>.<?php } ?>
			<?php if(in_array(14, array_keys($this->session->userdata('groups')))) { ?>Admin Credits: <strong><?php echo $current_user->admin_credit ?></strong>.<?php } ?>
			</div>
	</div><br />

<?php if($upcomming_classes) {
foreach($upcomming_classes as $class) { ?>
<div class="upcomming">You have a class at <strong><?php echo $class->name ?></strong> on <?php echo date('M d\<\s\u\p\>S\<\/\s\u\p\>(D), h:i A', strtotime($class->class_on)) ?>. 
	<a href="<?php echo site_url('classes/edit_class/'.$class->id); ?>">Substitute</a></div>
<?php } 
} ?>
	
<div id="quick" class="clear">
	
	<div class="quickLink"> <a href="<?php echo site_url('placement/manageaddchild_group') ?>">
	<img src="<?php echo base_url(); ?>images/ico/reports.png" alt="" /> <span>Child Group</span></a></div>

	
	<div class="quickLink"> <a href="<?php echo site_url('placement/manageplacement_activity') ?>">
	<img src="<?php echo base_url(); ?>images/ico/user_groups.jpeg" alt="" /> <span>Activity</span></a></div>
	
	
	
	<div class="quickLink"> <a href="#">
	<img src="<?php echo base_url(); ?>images/ico/group.jpeg" alt="" /> <span>Events</span></a></div>
	
	
	
	<div class="quickLink"> <a href="#">
	<img src="<?php echo base_url(); ?>images/ico/permission.jpeg" alt="" /> <span>Calender</span></a></div>
	
	
	
	<div class="quickLink"> <a href="#">
	<img src="<?php echo base_url(); ?>images/ico/reports.png" alt="" /> <span>Reports</span></a></div>
	


	
</div>
</div>
</div>



