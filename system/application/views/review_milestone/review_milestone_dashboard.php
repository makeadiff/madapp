
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">Review &amp; Milestone</h1>
        <br>
        <div class="row">
<!--
            <?php /*if($this->user_auth->get_permission('milestone_my')) { */?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php /*echo site_url('review/my_milestones') */?>">
                        <img class="dash" src="<?php /*echo base_url(); */?>images/flat_ui/my_milestones_new.png" alt="" /> <br>My<br>Milestones</a></div>
            <?php /*} */?>

            <?php /*if($this->user_auth->get_permission('milestone_list')) { */?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php /*echo site_url('review/milestone_select_people/') */?>">
                        <img src="<?php /*echo base_url(); */?>images/flat_ui/assign_milestones.png" alt="" /> <br>Assign<br>Milestones</a></div>
            <?php /*} */?>

            <?php /*if($this->user_auth->get_permission('review_data_my')) { */?>
                <div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="<?php /*echo site_url('review/my_reivew_sheet/'); */?>">
                        <img src="<?php /*echo base_url()*/?>/images/flat_ui/my_review.png" alt="" /><br>My Review<br>Sheet</a></div>
            <?php /*} */?>

            <?php /*if($this->user_auth->get_permission('review_select_person')) { */?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php /*echo site_url('review/select_people/') */?>">
                        <img src="<?php /*echo base_url(); */?>images/flat_ui/view_review_sheets.png" alt="" /> <br>View Reivew<br>Sheets</a></div>
            <?php /*} */?>

            <?php /*if($this->user_auth->get_permission('okr_my')) { */?>
                <div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/okr/">
                        <img src="<?php /*echo base_url()*/?>/images/flat_ui/okr.png" alt="" /><br>OKR</a></div>
            --><?php /*} */?>

            <?php if($this->user_auth->get_permission('reimbursement')) { ?>
                <div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/prism/public/">
                        <img class="dash" src="<?php echo base_url()?>/images/flat_ui/360.png" alt="" /><br>MAD 360</a></div>
            <?php } ?>

            <?php /*if($this->user_auth->get_permission('milestone_aggregator')) { */?><!--
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php /*echo site_url('review/aggregate_milestones'); */?>">
                        <img class="dash white" src="<?php /*echo base_url(); */?>images/flat_ui/milestone_aggregator.png" alt="" /><br /> Milestone<br /> Aggregator</a></div>
            --><?php /*} */?>



            <?php /*if($this->user_auth->get_permission('happiness_index')) { */?>
                <div class="col-md-4 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('review_milestone/happiness_index_view'); ?>">
                        <img class="dash" src="<?php echo base_url(); ?>images/flat_ui/happiness_index.png" alt="" /> <br>Happiness<br />Index</a></div>
            <?php /*} */?>






        </div>


    </div>
</div>

