<html>
<head>
<title><?php echo $title ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>static_files/css/style.css">
</head>
<body>
<div id="loading">loading...</div>
<div id="header">
<h1 id="logo"><a href="<?php echo base_url() ?>">MADApp</a></h1>
</div>

<div id="content">
<?php if(isset($message)) { ?>
<div id="error-message" <?php echo ($message['error']) ? '':'style="display:none;"';?>><?php echo $message['error'] ?></div>
<div id="success-message" <?php echo ($message['success']) ? '':'style="display:none;"';?>><?php echo $message['success'] ?></div>
<?php } ?>
