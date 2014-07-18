<?php $this->load->view('layout/thickbox_header');

if(empty($bank_name)) {
	$bank_name = '';
	$bank_address = '';
	$bank_account_number = '';
	$bank_ifsc_code = '';
	$bank_account_type = '';
}
?>
<h2>Bank Details</h2>

<form id="formEditor" action="<?php echo site_url('user/save_bank_details'); ?>" class="mainForm" method="post">
<ul class="form">
<li><label for="bank_name">Bank Name</label>
<input type="text" name="bank_name" value="<?php echo $bank_name ?>" /></li>

<li><label for="bank_address">Bank Address</label>
<textarea name="bank_address" rows="5" cols="30"><?php echo $bank_address ?></textarea></li>

<li><label for="bank_account_number">Account Number</label>
<input type="text" name="bank_account_number" value="<?php echo $bank_account_number ?>" /></li>

<li><label for="bank_ifsc_code">Bank IFSC Code</label>
<input type="text" name="bank_ifsc_code" value="<?php echo $bank_ifsc_code ?>" /></li>

<li><label for="bank_account_type">Account Type</label>
<select name="bank_account_type">
	<option value="saving" <?php if($bank_account_type == 'saving') echo "selected"; ?>>Saving</option>
	<option value="current" <?php if($bank_account_type == 'current') echo "selected"; ?>>Current</option>
</select></li>

</ul>
<input type="hidden" name="user_id" value="<?php echo $user_id ?>" />
<input id="btnSubmit" class="button green" type="submit" value="Save"></input>
</form>

<?php $this->load->view('layout/thickbox_footer'); ?>