<link href="<?php echo base_url(); ?>css/dashboard.css" type="text/css" rel="stylesheet" />
<div id="content" class="clear">
<div id="main" class="clear">
	<div id="head" class="clear" style="border-bottom:none;">
	<div style="font-size:14px;background-color:#FFF89D;height:15px;padding-top:18px;padding-bottom:20px;padding-left:10px;">
		Welcome, <?php echo $this->session->userdata('name'); ?>.</div>
</div>
	
<div id="quick" class="clear" style="margin-top:-15px;">
	<?php if($this->user_auth->get_permission('city_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('city/') ?>">
	<img src="<?php echo base_url(); ?>images/ico/city.jpeg" alt="" /> <span>City</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('project_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('project/manage_project') ?>">
	<img src="<?php echo base_url(); ?>images/ico/Project-icon.png" alt="" /> <span>Projects</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('center_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('center/manageaddcenters') ?>">
	<img src="<?php echo base_url(); ?>images/ico/center_right.png" alt="" /> <span>Centers</span></a></div>
	<?php } ?>
	
	<br />
	
	<?php if($this->user_auth->get_permission('classes_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('classes/') ?>">
	<img src="<?php echo base_url(); ?>images/ico/class.png" alt="" /> <span>Classes</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('report_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('report/') ?>">
	<img src="<?php echo base_url(); ?>images/ico/reports.png" alt="" /> <span>Report</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('classes_madsheet')) { ?>
	<div class="quickLink"> <a href="<?= site_url('classes/madsheet') ?>">
	<img src="<?php echo base_url(); ?>images/ico/stock_new-spreadsheet.png" alt="" /> <span>MADSheet</span></a></div>
	<?php } ?>
	
	<br />

	<?php if($this->user_auth->get_permission('kids_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('kids/manageaddkids') ?>">
	<img src="<?php echo base_url(); ?>images/ico/user.png" alt="" /> <span>Kids</span></a></div>
	<?php } ?>
	
	
	<?php if($this->user_auth->get_permission('exam_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('exam/manage_exam') ?>">
	<img src="<?php echo base_url(); ?>images/ico/exam_icon.jpg" alt="" /> <span>Exams </span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('exam_marks_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('exam/exam_score') ?>">
	<img src="<?php echo base_url(); ?>images/ico/mark.png" alt="" /> <span>Exam Mark</span></a></div>
	<?php } ?>
		
	<br />
	
	<?php if($this->user_auth->get_permission('user_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('user/view_users') ?>">
	<img src="<?php echo base_url(); ?>images/ico/user_groups.jpeg" alt="" /> <span>View Users</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('user_group_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('user_group/manageadd_group') ?>">
	<img src="<?php echo base_url(); ?>images/ico/group.jpeg" alt="" /> <span>User Groups</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('permission_index')) { ?>
	<div class="quickLink"> <a href="<?= site_url('permission/manage_permission') ?>">
	<img src="<?php echo base_url(); ?>images/ico/permission.jpeg" alt="" /> <span>Permissions</span></a></div>
	<?php } ?>
</div>
</div>
</div>



