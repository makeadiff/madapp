
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">Review & Milestone</h1>
        <br>
        <div class="row">

            <?php if($this->user_auth->get_permission('milestone_my')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('review/my_milestones') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/my_milestones.png" alt="" /> <br>My<br>Milestones</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('review_data_my')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="<?php echo site_url('review/my_reivew_sheet/'); ?>">
                        <img src="<?php echo base_url()?>/images/flat_ui/my_review.png" alt="" /><br>My Review<br>Sheet</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('okr_my')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/okr/">
                        <img src="<?php echo base_url()?>/images/flat_ui/okr.png" alt="" /><br>OKR</a></div>
            <?php } ?>

            <?php /*if($this->user_auth->get_permission('happiness_index')) { */?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/stakeholder-survey/status.php">
                        <img src="<?php echo base_url(); ?>images/flat_ui/happiness_index.png" alt="" /> <br>Happiness<br>Index Status</a></div>
            <?php /*} */?>

            <?php if($this->user_auth->get_permission('milestone_list')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('review/milestone_select_people/') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/assign_milestones.png" alt="" /> <br>Assign<br>Milestones</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('review_select_person')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('review/select_people/') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/view_review_sheets.png" alt="" /> <br>View Reivew<br>Sheets</a></div>
            <?php } ?>


        </div>


    </div>
</div>

