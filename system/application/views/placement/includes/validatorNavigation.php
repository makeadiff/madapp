<!-- superAdmin Navigation Begins -->

<div id="top">
<div id="title" class="clear"> <a href="#">Brilliant Exam Engine</a> <span> | Validator Panel</span></div>
<div id="menu" class="clear">
<ul>
	
    <!-- Navigation Dashboard Check Begins -->
    <?php if($navId == 'root') { ?>
    <li> <a href="<?= site_url('validator/dashboard') ?>">Dashboard</a></li>
    <?php } ?>
    <!-- Navigation Dashboard Check Ends -->

    <!-- Navigation Dashboard Check Begins -->
    <?php if($navId == '0') { ?>
    <li class="active"> <a href="<?= site_url('validator/dashboard') ?>">Dashboard</a></li>
    <?php } else if($navId != 'root') { ?>
    <li> <a href="<?= site_url('validator/dashboard') ?>">Dashboard</a></li>
    <?php } ?>
    <!-- Navigation Dashboard Check Ends -->

    <!-- Navigation Manage Users Check Begins -->
    <?php if($navId == '1'): ?>
    <li class="active"> <a href="<?= site_url('validator/validate_questions') ?>">Validate Questions</a></li>
    <?php else: ?>
    <li> <a href="<?= site_url('validator/validate_questions') ?>">Validate Questions</a></li>
    <?php endif; ?>
    <!-- Navigation Manage Users Check Ends -->
    
    <!-- Navigation Manage Group Check Ends -->
        
    <!--<li> <a href="5.html">Gallery</a></li> 
    <li> <a href="6.html">Other</a></li>-->

</ul>
</div>


<!-- Toolbar Starts -->
<div id="toolbar" class="clear">
    <p id="user" style="margin-left: 110px;">Logged in as <a href="#"><?php echo $this->session->userdata('admin_username'); ?></a></p>
        <div id="buttons"><a href="<?= site_url('common/logout') ?>" class="button tool" style="margin-left: 120px;">Logout</a></div>
</div>
<!-- Toolbar Ends -->

</div>

<!-- superAdmin Navigation Begins -->
