<?php $this->load->view('layout/thickbox_header'); ?>
<script type="text/javascript">
function init() {
	$("#form").submit(check);
	$(".leaving-reason").change(function() {
		var reason = $(this).val();
		if(reason == "other") {
			reason = "";
			$("#reason_for_leaving").show();
		} else {
			$("#reason_for_leaving").hide();
		}
		$("#reason_for_leaving").val(reason);
	});
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
<style type="text/css">
#reason_for_leaving {
	display: none;
}
</style>
<h2>Delete Student '<?php echo $name ?>'</h2>

<form class="mainForm clear" id="form" action="<?php echo site_url('kids/delete_student/' . $id)?>" method="post">
<label for="txtName">Reason for Leaving...</label><br />

<ul>
<li><input name="reason" type="radio" class="leaving-reason" id="leaving-duplicate" value="duplicate" />
<label for="leaving-duplicate">Duplicate copy of existing child</label></li>

<li><input name="reason" type="radio" class="leaving-reason" id="leaving-repatriated" value="repatriated" />
<label for="leaving-repatriated">Child was repatriated with their parents</label></li>

<li><input name="reason" type="radio" class="leaving-reason" id="leaving-disciplinary-expulsion" value="disciplinary-expulsion" />
<label for="leaving-disciplinary-expulsion">Child was sent away from the shelter due to discipline concerns</label></li>

<li><input name="reason" type="radio" class="leaving-reason" id="leaving-shifted" value="shifted" />
<label for="leaving-shifted">Child shifted to another shelter</label></li>

<li><input name="reason" type="radio" class="leaving-reason" id="leaving-cleared-12" value="cleared-12" />
<label for="leaving-cleared-12">Child cleared 12th standard and is moving to an after care city.</label></li>

<li><input name="reason" type="radio" class="leaving-reason" id="leaving-shelter-shutdown" value="shelter-shutdown" />
<label for="leaving-shelter-shutdown">Shelter shut down and kids were sent away</label></li>

<li><input name="reason" type="radio" class="leaving-reason" id="leaving-other" value="other" />
<label for="leaving-other">Other Reason...</label></li>

</ul>

<textarea rows="5" cols="24" id="reason_for_leaving" name="reason_for_leaving"><?php echo $reason_for_leaving; ?></textarea><br />
<input type="hidden" value="<?php echo $id; ?>"  id="id" name="id" />
<input id="btnSubmit" class="button green" type="submit" value="Delete Student" />

<a href="<?php echo site_url('kids/manageaddkids'); ?>" class="sec-action">Cancel</a>
</form>

<?php $this->load->view('layout/thickbox_footer'); ?>