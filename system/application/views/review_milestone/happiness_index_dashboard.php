
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">Happiness Index</h1>
        <br>
        <div class="row">

            <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' target="_blank" href="http://makeadiff.in/apps/stakeholder-survey/status.php">
                    <img class="dash" src="<?php echo base_url(); ?>images/flat_ui/national.png" alt="" /> <br>National<br>Completion<br>Status</a></div>

            <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' target="_blank" href="http://makeadiff.in/apps/stakeholder-survey/city-status.php">
                    <img class="dash" src="<?php echo base_url(); ?>images/flat_ui/cities.png" alt="" /> <br>City Completion<br>Status</a></div>

            <?php if($this->user_auth->get_permission('happiness_index_aggregator')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/stakeholder-survey/admin/aggregates.php">
                        <img class="dash white" src="<?php echo base_url(); ?>images/flat_ui/happiness_index_response.png" alt="" /><br /> Happiness Index<br />Response<br />Aggregate</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('review_aggregator')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('review/aggregate'); ?>">
                        <img class="dash white" src="<?php echo base_url(); ?>images/flat_ui/aggregator.png" alt="" /> <br>Happiness Index<br /> Aggregator</a></div>
            <?php } ?>






        </div>


    </div>
</div>

