<?php $this->load->view('layout/css'); ?>
<style>
	a:hover { text-decoration:underline; }
</style>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>
<script>tb_init('a.thickbox, input.thickbox');  </script>

<?php if($successFlag == 0): ?>
	<div id="msgWarning" class="message" style="margin-left:1px;" align="left"><?php echo $msg; ?></div>
<?php elseif($successFlag == 1): ?>
	<div id="msgConfirm" class="message" style="margin-left:1px;" align="left"><?php echo $msg; ?></div>
<?php endif; ?>

<div align="center" style="margin-top:100px;">
<?php if($link != '') { ?>
<a href="<?= $link ?>"><img src="<?php echo base_url()?>images/ico/<?= $icoFile ?>" style="border:none;"> <?= $linkText ?></a>
&nbsp; &nbsp; &nbsp; 
<a href="javascript:parent.tb_remove(); parent.location.reload(1);"><img src="<?php echo base_url()?>images/ico/ico_closeThickbox.png" style="border:none;"> Close this Box</a>
<?php } else { ?>
<div style="width:280px;float:left;margin-left:315px;" align="left"><img src="<?php echo base_url()?>images/ico/ico_closeThickbox.png" style="border:none;"> <a href="javascript:parent.tb_remove(); parent.location.reload(1);">Close this Box</a></div>
<?php } ?>
</div>
