
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">Center Support</h1>
        <br>
        <div class="row">

            <?php if($this->user_auth->get_permission('center_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('center/manageaddcenters') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/centers.png" alt="" /> <br>Center<br>Management</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('kids_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('kids/manageaddkids') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/kids.png" alt="" /> <br>Child<br>Management</a></div>
            <?php } ?>



            <?php if($this->user_auth->get_permission('event_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('event/event') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/events.png" alt="" /> <br>Event</a></div>
            <?php } ?>


        </div>


    </div>
</div>

