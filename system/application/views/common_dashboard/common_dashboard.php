<html class="no-js">
<head>
    <title>MADApp</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/custom.css" />
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.9.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/uservoice.js"></script>


    <script>document.documentElement.className = document.documentElement.className.replace('no-js','js');</script>

</head>

<body class="blue-red">

<nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <a class="navbar-brand" href="">MADApp</a>

    </div>
    <div class="collapse navbar-collapse" id="navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">

            <li><a><?php echo $this->session->userdata('name');?></a></li>
            <li><a href="<?php echo site_url('auth/logout') ?>">Logout</a></li>
        </ul>

    </div>
</nav>

<div class="container-fluid">
<div class="board transparent-container">
<h1 class="title">MADApp</h1>
<br>
<div class="row">
    <div class="col-md-3 col-sm-6 text-center">
        <a href="<?php echo site_url('edsupport/dashboard_view')?>" class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/ed_support.png"><br>Ed Support</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href="<?php echo site_url('hr/dashboard_view')?>" class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/hr.png"><br>HR</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href="<?php echo site_url('centersupport/dashboard_view')?>" class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/center_support.png"><br>Center Support</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href='<?php echo site_url('finance/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/finance.png"><br>Finance</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href='http://makeadiff.in/apps/propel/public' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/propel.png"><br>Propel</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href='<?php echo site_url('pr/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/pr.png"><br>PR</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href='<?php echo site_url('discover/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/discover.png"><br><br>Discover</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href='<?php echo site_url('fundraising/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/fundraising.png"><br><br>Fundraising</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href='<?php echo site_url('review_milestone/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/review.png"><br>Review &<br>Milestones</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href='<?php echo site_url('resources/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/resources.png"><br>Resources</a>
    </div>

    <div class="col-md-3 col-sm-6 text-center">
        <a href='<?php echo site_url('profile/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/profile.png"><br>Profile</a>
    </div>


    <div class="col-md-3 col-sm-6 text-center">
        <a href='<?php echo site_url('setting/dashboard_view')?>' class='btn btn-primary btn-dash '><img src="<?php echo base_url()?>/images/flat_ui/settings.png"><br>Settings</a>
    </div>

</div>


</div>
</div>

<script>$('body').fadeIn(1000);</script>

</body>
</html>