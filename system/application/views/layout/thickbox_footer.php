<?php
$message['success'] = $this->session->flashdata('success');
$message['error'] = $this->session->flashdata('error');
if(!empty($message['success']) or !empty($message['error'])) { ?>
<div class="message" id="error-message" <?php echo (!empty($message['error'])) ? '':'style="display:none;"';?>><?php echo (empty($message['error'])) ? '':$message['error'] ?></div>
<div class="message" id="success-message" <?php echo (!empty($message['success'])) ? '':'style="display:none;"';?>><?php echo (empty($message['success'])) ? '': $message['success'] ?></div>
<br />
<?php } ?>

</body>
</html>
