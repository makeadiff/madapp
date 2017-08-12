
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">HC</h1>
        <br>
        <div class="row">
            <?php if($this->user_auth->get_permission('user_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('user/view_users') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/volunteers.png" alt="" /> <br>Volunteer<br>Management</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('hr_requirement')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/support/requirements.php">
                        <img src="<?php echo base_url()?>/images/flat_ui/volunteer_request.png" alt="" /><br>Volunteer<br>Requirements</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('hr_requirement_national')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/support/national_requirements.php">
                        <img src="<?php echo base_url()?>/images/flat_ui/volunteer_request.png" alt="" /><br>National<br>Requirements</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('admincredit_index')) { // :PERMISSION_RESET: ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/support/attrition.php">
                        <img src="<?php echo base_url(); ?>images/flat_ui/happiness_index_response.png" height="64" alt="" /> <br>Volunteer Attrition<br /> Tracker</a></div>
            <?php } ?><br />

            <?php if($this->user_auth->get_permission('event_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('event/event') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/events.png" alt="" /> <br>Event</a></div>
            <?php } ?>
        </div>
    </div>
</div>

