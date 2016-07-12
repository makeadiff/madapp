<?php $this->load->view('layout/thickbox_header'); ?>
<script type="text/javascript">
function init() {
	$("#formEditor").submit(check);
}

function check(e) {
	if(!($("#reason_for_leaving").val())) {
		e.stopPropagation();
		alert("Please make sure you have a reason for leaving.");
		return false;
	}
	return true;
}

</script>
<h2>Delete Student '<?php echo $name ?>'</h2>

<form class="mainForm clear" id="formEditor" action="<?php echo site_url('kids/delete_student/' . $id)?>" method="post">
<label for="txtName">Reason for Leaving...</label><br />
<textarea rows="5" cols="24" id="reason_for_leaving" name="reason_for_leaving"><?php echo $reason_for_leaving; ?></textarea><br />
<input type="hidden" value="<?php echo $id; ?>"  id="id" name="id" />
<input id="btnSubmit" class="button green" type="submit" value="Delete Student" />

<a href="<?php echo site_url('kids/manageaddkids'); ?>" class="sec-action">Cancel</a>
</form>

<?php $this->load->view('layout/thickbox_footer'); ?>