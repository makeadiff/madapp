
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">Settings</h1>
        <br>
        <div class="row">

            <?php if($this->user_auth->get_permission('project_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('project/manage_project') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/projects.png" alt="" /> <br>Project<br>Management</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('city_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('city/') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/cities.png" alt="" /> <br>City<br>Management</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('user_group_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="<?php echo site_url('user_group/manageadd_group'); ?>">
                        <img src="<?php echo base_url()?>/images/flat_ui/user_groups.png" alt="" /><br>User Group<br>Management</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('permission_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="<?php echo site_url('permission/manage_permission') ?>">
                        <img src="<?php echo base_url()?>/images/flat_ui/permissions.png" alt="" /><br>Permission<br>Management</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('setting_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('settings/index') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/settings.png" alt="" /> <br>Parameter<br>Setting</a></div>
            <?php } ?><br />


        </div>


    </div>
</div>

