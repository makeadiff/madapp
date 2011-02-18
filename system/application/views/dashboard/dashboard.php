<?php include_once('session_timeout.php'); ?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/thickbox.css">
    <div id="content" class="clear">
    	<div id="main" class="clear">
    		<div id="head" class="clear" style="border-bottom:none;">
            <div style="font-size:14px;background-color:#FFF89D;height:15px;padding-top:18px;padding-bottom:20px;padding-left:10px;">
            	welcome  <?php echo $this->session->userdata('name'); ?></div>
    	</div>
    		
    	<div id="quick" class="clear" style="margin-top:-15px;">
    		<div class="quickLink"> <a href="<?= site_url('city/') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/Project-icon.png" alt="" /> <span>City</span></a></div>
    		<div class="quickLink"> <a href="<?= site_url('project/manage_project') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/Project-icon.png" alt="" /> <span>Projects</span></a></div>
            <div class="quickLink"> <a href="<?= site_url('center/manageaddcenters') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/icoPublish.png" alt="" /> <span>Centers</span></a></div>
            
            <br />
            
            <!--<div class="quickLink"> <a href="<?= site_url('batch/') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/Project-icon.png" alt="" /> <span>Batches</span></a></div>
    		<div class="quickLink"> <a href="<?= site_url('level/') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/Project-icon.png" alt="" /> <span>Levels</span></a></div>-->
            <div class="quickLink"> <a href="<?= site_url('classes/') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/icoPublish.png" alt="" /> <span>Classes</span></a></div>
            <div class="quickLink"> <a href="<?= site_url('report/') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/icoPublish.png" alt="" /> <span>Report</span></a></div>
            <div class="quickLink"> <a href="<?= site_url('classes/madsheet') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/icoPublish.png" alt="" /> <span>MADSheet</span></a></div>
            
            <br />
        
        	<div class="quickLink"> <a href="<?= site_url('kids/manageaddkids') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/user.png" alt="" /> <span>Kids</span></a></div>
        	<div class="quickLink"> <a href="<?= site_url('exam/manage_exam') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/exam_icon.jpg" alt="" /> <span>Exams </span></a></div>
            <div class="quickLink"> <a href="<?= site_url('exam/exam_score') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/mark.png" alt="" /> <span>Exam Mark</span></a></div>
             
            <br />
             
            <div class="quickLink"> <a href="<?= site_url('user/view_users') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/user_groups.jpeg" alt="" /> <span>View Users</span></a></div>
        	<div class="quickLink"> <a href="<?= site_url('user_group/manageadd_group') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/group.jpeg" alt="" /> <span>User Groups</span></a></div>
            <div class="quickLink"> <a href="<?= site_url('permission/manage_permission') ?>" class="thickbox">
            <img src="<?php echo base_url(); ?>images/ico/permission.jpeg" alt="" /> <span>Permissions</span></a></div>
        
         	
        
        	
        
       </div>
    	</div>
    </div>



