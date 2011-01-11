<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/custom.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />

<style>
	a:hover { text-decoration:underline; }
</style>

<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>
<script>	tb_init('a.thickbox, input.thickbox');  </script>

<?php if($successFlag == 0): ?>
	<div id="msgWarning" class="message" style="margin-left:1px;" align="left"><?php echo $msg; ?></div>
<?php elseif($successFlag == 1): ?>
	<div id="msgConfirm" class="message" style="margin-left:1px;" align="left"><?php echo $msg; ?></div>
<?php endif; ?>

<div align="center" style="margin-top:100px;">
<?php if($link != ''): ?>
<div style="width:365px;float:left;" align="right"><img src="<?php echo base_url()?>images/ico/<?= $icoFile ?>" style="border:none;"> <a href="<?= $link ?>"><?= $linkText ?></a></div>
<?php endif; ?>
<?php if($link != ''): ?>
<div style="width:280px;float:left;margin-left:10px;" align="left"><img src="<?php echo base_url()?>images/ico/ico_closeThickbox.png" style="border:none;"> <a href="javascript:parent.tb_remove();">close thickbox</a></div>
<?php else: ?>
<div style="width:280px;float:left;margin-left:315px;" align="left"><img src="<?php echo base_url()?>images/ico/ico_closeThickbox.png" style="border:none;"> <a href="javascript:parent.tb_remove(); parent.location.reload(1);">close thickbox</a></div>
<?php endif; ?>
</div>