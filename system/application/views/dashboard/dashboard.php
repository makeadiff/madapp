<link href="<?php echo base_url(); ?>css/dashboard.css" type="text/css" rel="stylesheet" />
<script type="text/javascript" src="<?php echo base_url(); ?>js/ajaxify.js"></script>

<div id="content" class="clear">
<div id="main" class="clear">
	<div id="head" class="clear" style="border-bottom:none;">
	<div style="font-size:14px;background-color:#FFF89D;height:15px;padding-top:18px;padding-bottom:20px;padding-left:10px;">
			Welcome, <?php echo $this->session->userdata('name'); ?>.</div>
	</div><br />
	
<?php if($upcomming_classes) {
foreach($upcomming_classes as $class) { ?>
<div class="upcomming">You have a class at <strong><?php echo $class->name ?></strong> on <?php echo date('M d\<\s\u\p\>S\<\/\s\u\p\>(D), h:i A', strtotime($class->class_on)) ?>. 
	<?php if($class->status == 'projected') { ?><a href="<?php echo site_url('classes/confirm_class/'.$class->id); ?>" class="ajaxify ajaxify-replace">Confirm</a> / <?php } ?>
	<a href="<?php echo site_url('classes/edit_class/'.$class->id); ?>">Substitute</a></div>
<?php } 
} ?>
	
<div id="quick" class="clear" style="margin-top:-1px;">
	<?php if($this->user_auth->get_permission('city_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('city/') ?>">
	<img src="<?php echo base_url(); ?>images/ico/city.jpeg" alt="" /> <span>Cities</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('project_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('project/manage_project') ?>">
	<img src="<?php echo base_url(); ?>images/ico/Project-icon.png" alt="" /> <span>Projects</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('center_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('center/manageaddcenters') ?>">
	<img src="<?php echo base_url(); ?>images/ico/center_right.png" alt="" /> <span>Centers</span></a></div>
	<?php } ?>
	
	<br />
	
	<?php if($this->user_auth->get_permission('classes_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('classes/') ?>">
	<img src="<?php echo base_url(); ?>images/ico/class.png" alt="" /> <span>Classes</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('report_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('report/') ?>">
	<img src="<?php echo base_url(); ?>images/ico/reports.png" alt="" /> <span>Reports</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('classes_madsheet')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('classes/madsheet') ?>">
	<img src="<?php echo base_url(); ?>images/ico/stock_new-spreadsheet.png" alt="" /> <span>MAD Sheet</span></a></div>
	<?php } ?>
	
	<br />

	<?php if($this->user_auth->get_permission('kids_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('kids/manageaddkids') ?>">
	<img src="<?php echo base_url(); ?>images/ico/user.png" alt="" /> <span>Kids</span></a></div>
	<?php } ?>
	
	
	<?php if($this->user_auth->get_permission('exam_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('exam/manage_exam') ?>">
	<img src="<?php echo base_url(); ?>images/ico/exam_icon.jpg" alt="" /> <span>Exams</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('exam_marks_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('exam/exam_score') ?>">
	<img src="<?php echo base_url(); ?>images/ico/mark.png" alt="" /> <span>Exam Marks</span></a></div>
	<?php } ?>
		
	<br />
	
	<?php if($this->user_auth->get_permission('user_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('user/view_users') ?>">
	<img src="<?php echo base_url(); ?>images/ico/user_groups.jpeg" alt="" /> <span>View Users</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('user_group_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('user_group/manageadd_group') ?>">
	<img src="<?php echo base_url(); ?>images/ico/group.jpeg" alt="" /> <span>User Groups</span></a></div>
	<?php } ?>
	
	<?php if($this->user_auth->get_permission('permission_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('permission/manage_permission') ?>">
	<img src="<?php echo base_url(); ?>images/ico/permission.jpeg" alt="" /> <span>Permissions</span></a></div>
	<?php } ?>
	<br />
    <?php if($this->user_auth->get_permission('books_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('books/manage_books') ?>">
	<img src="<?php echo base_url(); ?>images/ico/book.jpeg" alt="" /> <span>Books</span></a></div>
	<?php } ?>
    <?php if($this->user_auth->get_permission('chapters_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('books/manage_chapters') ?>">
	<img src="<?php echo base_url(); ?>images/ico/book.jpeg" alt="" /> <span>Chapters</span></a></div>
	<?php } ?>
    <?php if($this->user_auth->get_permission('setting_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('settings/index') ?>">
	<img src="<?php echo base_url(); ?>images/ico/book.jpeg" alt="" /> <span>Settings</span></a></div>
	<?php } ?>
    	<br />
        <?php if($this->user_auth->get_permission('setting_index')) { ?>
	<div class="quickLink"> <a href="<?php echo site_url('user/credithistory') ?>">
	<img src="<?php echo base_url(); ?>images/ico/credit.jpg" alt="" /> <span>Credit History</span></a></div>
	<?php } ?>
    	
</div>
</div>
</div>



