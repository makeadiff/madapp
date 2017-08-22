<div id="updateDiv" >
<div id="content" class="clear">

<div id="main" class="clear">
<div id="head" class="clear">
<h1><?php echo $title; ?></h1>
</div>

<style type="text/css">
#update-credit-warning {
	display: none;
}
form {
	padding: 5px;
}
label {
	font-weight: bold;
}
#error {
	color:red;
	font-weight: bold;
}
</style>
<script type="text/javascript">
function init() {
	$("#update-warning").click(function() {
		$("#update-credit-warning").toggle();
		$("#update-warning").toggle();
	});

	$("#credit-update").submit(validate);
}

function validate(e) {
	var reason = $("#reason").val();
	var original_credit = $("#original_credit").val();
	var credit = $("#credit").val();
	var error = [];
	if(!reason) {
		error.push("Please provide a reason to update the credit manually...");
	}
	if(original_credit == credit) {
		error.push("No change in credits");
	}

	if(error.length) {
		$("#error").html("<ul><li>" + error.join("</li><li>") + "</li></ul>");
		e.stopPropagation();
		return false;
	}

	return true;
}
</script>


<table class="clear data-table">
<thead>
<tr><th colspan="5">Credit History</th></tr>
<tr><th>#</th><th>Class Time</th><th>Class Status</th><th>Credit Change</th><th>Credit</th></tr>
</thead>
<tbody>

<?php foreach($credit_log as $credit) { ?>
<tr>
<td><?php echo $credit['i'] + 1 ?></td>
<td><?php echo date('d M, Y h:i A', strtotime($credit['class_on'])); ?></td>
<td><?php echo $credit['action'] ?></td>
<td><?php echo $credit['change'] ?></td>
<td><?php echo $credit['credit'] ?></td>   
</tr>   
<?php } ?>
</tbody>
</table>
<br><br>

<?php if($this->user_auth->get_permission('user_edit')) { ?>
<table class="data-table">
    <thead>
    <tr><th>Update Credit</th></tr>
    </thead>
    <tbody>
    <tr><td>
        <form action="" method="post" id="credit-update">
            <label>Current Credit: </label><?php echo $user_details->credit ?><br>
            <label for="credit">Change Credit To: </label>
            <input type="text" name="credit" id="credit" size="3" value="<?php echo $user_details->credit ?>" />
            <input type="hidden" name="original_credit" id="original_credit" value="<?php echo $user_details->credit ?>" />
            <br><br>
            <p>Manually changing the credit should only be done as a last resort. What is your reason for doing this?</p>
            <textarea name="reason" id="reason" rows="5" cols="70" placeholder="Reason?"></textarea><br /><br>
            <input type="submit" class="button green " name="action" value="Update" /><br />
            <div id="error"></div>
        </form>
    </td></tr>
    </tbody>
</table>
<?php } ?>

</div>
</div>
</div>
