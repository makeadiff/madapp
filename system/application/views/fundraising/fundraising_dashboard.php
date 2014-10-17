
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">Fundraising</h1>
        <br>
        <div class="row">


                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' target="_blank" href="http://cfrapp.makeadiff.in:3000">
                        <img src="<?php echo base_url(); ?>images/flat_ui/donut.png" alt="" /><br>Donut</a></div>


            <?php if($this->user_auth->get_permission('target_setting')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/support/targets.php">
                        <img src="<?php echo base_url(); ?>images/flat_ui/target_setting.png" alt="" /><br>Target<br>Setting</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('event_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('event/event') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/events.png" alt="" /><br>Event</a></div>
            <?php } ?>


        </div>


    </div>
</div>

