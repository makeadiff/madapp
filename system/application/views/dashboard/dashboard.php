<?php include_once('session_timeout.php'); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/thickbox.css">
    <div id="content" class="clear">
    	<div id="main" class="clear">
    		<div id="head" class="clear" style="border-bottom:none;">
            <div style="font-size:14px;background-color:#FFF89D;height:15px;padding-top:18px;padding-bottom:20px;padding-left:10px;">
            	welcome<?php echo $this->session->userdata('name'); ?> ,</div>
    	</div>
    		
    	<div id="quick" class="clear" style="margin-top:-15px;">
        	<div class="quickLink"> <a href="<?= site_url('center/manageaddcenters') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/icoPublish.png" alt="" /> <span>Add Centers</span></a></div>
        
        	<div class="quickLink"> <a href="<?= site_url('kids/manageaddkids') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/user.png" alt="" /> <span>Add Kids</span></a></div>
        	<div class="quickLink"> <a href="<?= site_url('exam/manage_exam') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/exam_icon.jpg" alt="" /> <span>Add Exams </span></a></div>
        	<div class="quickLink"> <a href="<?= site_url('user_group/manageadd_group') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/group.jpeg" alt="" /> <span>Add Group Name</span></a></div>
        	<div class="quickLink"> <a href="<?= site_url('user/manageadd_user') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/user.jpeg" alt="" /> <span>Add User</span></a></div>
        
        	<div class="quickLink"> <a href="<?= site_url('exam/exam_score') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/mark.png" alt="" /> <span>Add Exam Mark</span></a></div>
        <div class="quickLink"> <a href="<?= site_url('permission/manage_permission') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/permission.jpeg" alt="" /> <span>Add Permission</span></a></div>
        
         <div class="quickLink"> <a href="<?= site_url('project/manage_project') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/Project-icon.png" alt="" /> <span>Projects</span></a></div>
        
        <div class="quickLink"> <a href="<?= site_url('user/view_users') ?>" class="thickbox " name="" id="example">
             <img src="<?php echo base_url(); ?>images/ico/user_groups.jpeg" alt="" /> <span>View Users</span></a></div>
        
       </div>
    	</div>
    </div>



