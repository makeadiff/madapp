<!DOCTYPE html>
<html class="no-js">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title><?php echo empty($title) ? 'MADApp - ' . ucfirst($this->uri->segment(1)) : $title; ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bootstrap.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/custom.css" />
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery.tablesorter.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/jquery-1.9.0.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/bootstrap.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url()?>js/uservoice.js"></script>

    <script>document.documentElement.className = document.documentElement.className.replace('no-js','js');</script>
</head>

<body id="pg-<?php echo $this->uri->segment(1) . '-' . $this->uri->segment(2); ?>" class="blue-red">
<nav class="navbar navbar-default navbar-static-top" role="navigation">
    <div class="container-fluid">
    <div class="navbar-header">
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-collapse-1">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
        </button>

        <a class="navbar-brand" href="<?php echo site_url('dashboard/dashboard_view') ?>">MADApp</a>

    </div>
    <div class="collapse navbar-collapse" id="navbar-collapse-1">
        <ul class="nav navbar-nav navbar-right">
            <li>
                <?php

                //To ensure that when city is changed, page is not redirected to dashboard

                if($this->user_auth and $this->user_auth->get_permission('change_city')) {
                    $all_cities = idNameFormat($this->db->query("SELECT id, name FROM City ORDER BY name")->result());
                    $url = site_url('edsupport/dashboard_view');

                    if($this->uri->segment(1) == 'kids') $url = site_url('kids/manageaddkids');
                    if($this->uri->segment(1) == 'centers') $url = site_url('center/manageaddcenters');
                    if($this->uri->segment(1) == 'user') $url = site_url('user/view_users');
                    if($this->uri->segment(1) == 'classes') $url = site_url('classes/madsheet');

                ?>
                    <form class="navbar-form navbar-right" method="post" action="<?php $url?>">
                        <?php
                        echo form_dropdown('city_id', $all_cities, $this->session->userdata('city_id'));
                        echo form_dropdown('year', array('2011'=>'2011','2012'=>'2012','2013'=>'2013','2014'=>'2014'), $this->session->userdata('year')); //:HARDCODE:
                        echo form_submit('action', "Change");
                        ?>
                    </form>
                <?php
                }
                ?>
            </li>

            <li><a>
                    <?php echo $this->session->userdata('name');
                    $groups = $this->session->userdata('groups');
                    if($groups) print ' (' . implode(',', $groups) . ')';
                    ?>
                </a></li>
            <li><a href="<?php echo site_url('auth/logout') ?>">Logout</a></li>

        </ul>

    </div>
    </div>
</nav>

<?php
$message['success'] = $this->session->flashdata('success');
$message['error'] = $this->session->flashdata('error');
if(!empty($message['success']) or !empty($message['error'])) { ?>
    <div class="message" id="error-message" <?php echo (!empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
    <div class="message" id="success-message" <?php echo (!empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
<?php } ?>

