<
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">PR</h1>
        <br>
        <div class="row">

            <?php if($this->user_auth->get_permission('pr_requirement')) { ?>
            <div class="col-md-4 col-sm-6 text-center">
                <a href="http://makeadiff.in/apps/support/pr_requirement.php" class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/pr_support_request.png"><br>PR<br>Requirement</a>
            </div>

            <?php } ?>

            <?php if($this->user_auth->get_permission('pr_content_submission')) { ?>
                <div class="col-md-4 col-sm-6 text-center">
                    <a href="http://makeadiff.in/apps/support/pr_content_submission.php" class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/content_submission.png"><br>Content<br>Submission</a>
                </div>

            <?php } ?>

            <?php if($this->user_auth->get_permission('event_index')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('event/event') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/events.png" alt="" /><br>Event</a></div>
            <?php } ?>

        </div>


    </div>
</div>
