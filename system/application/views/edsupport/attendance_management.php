

<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">Attendance Management</h1>
        <br>
        <div class="row">

            <?php if($this->user_auth->get_permission('user_credithistory')) { ?>
                <div class="col-md-3 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('user/credithistory') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/credit_history.png" alt="" /> <br>My Credit<br>History</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('classes_batch_view')) { ?>
                <div class="col-md-3 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('classes/batch_view') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/batch_view.png" alt="" /><br>Mark Attendance<br>(Mentor View)</a></div>
            <?php } ?>


            <?php if($this->user_auth->get_permission('classes_index')) { ?>
                <div class="col-md-3 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('classes/') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/classes.png" alt="" /> <br>Classes</a></div>
            <?php } ?>

            <?php if($this->user_auth->get_permission('classes_madsheet')) { ?>
                <div class="col-md-3 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="<?php echo site_url('classes/madsheet') ?>">
                        <img src="<?php echo base_url(); ?>images/flat_ui/mad_sheet.png" alt="" /> <br>MAD Sheet</a></div>
            <?php } ?>




        </div>


    </div>
</div>

