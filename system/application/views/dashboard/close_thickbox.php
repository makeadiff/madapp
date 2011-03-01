<?php $this->load->view('layout/css'); ?>

<style>
	a:hover { text-decoration:underline; }
</style>

<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>
<script>	tb_init('a.thickbox, input.thickbox');  </script>

<div id="msgConfirm" class="message" style="margin-left:1px; text-align:center;" align="left"><?php echo $msg; ?></div>

<div align="center" style="margin-top:100px;">
<div style="width:280px;float:left;margin-left:450px;" align="left"><img src="<?php echo base_url()?>images/ico/ico_closeThickbox.png" style="border:none;"> <a href="javascript:parent.tb_remove(); parent.location.reload(1);">Close This Box</a></div>
</div>