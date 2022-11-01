<div class="container-fluid">
<div class="board transparent-container">
<h1 class="title"><?= APP_NAME ?></h1>
<br />

	<?php if($this->user_auth->get_permission('user_index')) { ?>
		<div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="<?php echo site_url('user/view_users') ?>">
			<img src="<?php echo base_url(); ?>images/flat_ui/volunteers.png" alt="" /> <br>Volunteer<br>Management</a></div>
	<?php } ?>

	<?php if($this->user_auth->get_permission('center_index')) { ?>
		<div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="<?php echo site_url('center/manageaddcenters') ?>">
			<img src="<?php echo base_url(); ?>images/flat_ui/centers.png" alt="" /> <br>Center<br>Management</a>
		</div>
	<?php } ?>

	<?php if($this->user_auth->get_permission('classes_madsheet')) { ?>
		<div class="col-md-4 col-sm-6 text-center"> <a class='btn btn-primary btn-dash' href="<?php echo site_url('classes/madsheet') ?>">
			<img src="<?php echo base_url(); ?>images/flat_ui/mad_sheet.png" alt="" /> <br>ClassSheet</a>
		</div>
	<?php } ?>

	<br style="clear:both;" />
<!-- 
	<div class="col-md-3 col-sm-6 text-center">
		<a href="<?php echo site_url('edsupport/dashboard_view')?>" class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/ed_support.png"><br>Ed Support</a>
	</div>

	<div class="col-md-3 col-sm-6 text-center">
		<a href="<?php echo site_url('hr/dashboard_view')?>" class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/hr.png"><br>HC</a>
	</div>

	<div class="col-md-3 col-sm-6 text-center">
		<a href="<?php echo site_url('centersupport/dashboard_view')?>" class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/center_support.png"><br>Center Support</a>
	</div>

	<div class="col-md-3 col-sm-6 text-center">
		<a href='<?php echo site_url('finance/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/finance.png"><br>Finance</a>
	</div>

	<div class="col-md-3 col-sm-6 text-center">
		<a href='<?php echo site_url('fundraising/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/fundraising.png"><br><br>Fundraising</a>
	</div>

	<div class="col-md-3 col-sm-6 text-center">
		<a href='<?php echo site_url('resources/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/resources.png"><br>Resources</a>
	</div> -->

	<div class="col-md-6 col-sm-6 text-center">
		<a href='<?php echo site_url('profile/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/profile.png"><br>Profile</a>
	</div>

	<div class="col-md-4 col-sm-6 text-center">
		<a href="<?php echo site_url('finance/dashboard_view')?>" class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/finance.png"><br>Finance</a>
	</div>

	<div class="col-md-12 col-sm-6 text-center">
		<a href='<?php echo site_url('setting/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/settings.png"><br>Settings</a>
	</div>

</div>


</div>
</div>
