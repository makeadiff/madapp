
<div class="container-fluid">
    <div class="board transparent-container">
        <h1 class="title">Profile</h1>
        <br>
        <div class="row">


        <div class="col-md-6 col-sm-6 text-center"> <a  class='btn btn-primary btn-dash' href="http://makeadiff.in/apps/profile/fb.php?user_id=<?php echo $current_user->id;?>">
                <img src="<?php echo base_url(); ?>images/flat_ui/mad_cred.png" alt="" /> <br>MAD Cred</a></div>

        <div class="col-md-6 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="<?php echo site_url('user/view/'.$current_user->id); ?>">
                <img src="<?php echo base_url()?>/images/flat_ui/edit_profile.png" alt="" /><br>Edit Profile</a></div>


        </div>


    </div>
</div>

