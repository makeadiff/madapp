<?php
$this->load->view('layout/header',array('title'=>$title));
?>
<script type="text/javascript">
function init() {
	$(".data-table").tablesorter({
		headers: { 
			0: {sorter: false}, 
			1: {sorter: false},
			3: {sorter: false},
			4: {sorter:'dateField'},
		}
	});
}

$.tablesorter.addParser({
	id: 'dateField', 
	is: function(s) {
		// return false so this parser is not auto detected 
		return false; 
	}, 
	format: function(date) {
		date = date.replace(/(\d+).. (.+), (\d+)/, function(all, day, month, year) {
			var month_names = {	"Jan":"01","Feb":"02","Mar":"03","Apr":"04","May":"05","Jun":"06",
								"Jul":"07","Aug":"08","Sep":"09","Oct":"10","Nov":"11","Dec":"12"};
			var date = year + "-" + month_names[month] + "-" + day;
			return date;
		});
		return date;
	}, 
	// set type, either numeric or text 
	type: 'text'
});
</script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/sections/users/view_users.css">

<div id="content" class="clear">

<div id="main" class="clear">
<div id="head" class="clear">
<h1><?php echo $title; ?></h1>

</div>
<?php if($this->user_auth->get_permission('center_edit')) { ?>
<div id="train-nav">
<ul>
<?php if($this->session->userdata("active_center")) { ?>
<li id="train-prev"><a href="<?php echo site_url('center/manage/'.$this->session->userdata("active_center"))?>">&lt; Edit Shelter Details</a></li>
<li id="train-top"><a href="<?php echo site_url('center/manage/'.$this->session->userdata("active_center"))?>">^ Manage Shelter</a></li>
<li id="train-next"><a href="<?php echo site_url('kids/index')?>">Manage Kids &gt;</a></li>
<?php } else { ?>
<li id="train-prev">&nbsp;</li>
<li id="train-top"><a href="<?php echo site_url('center/manageaddcenters')?>">^ Manage Shelter</a></li>
<li id="train-next"><a href="<?php echo site_url('kids/index')?>">Manage Kids &gt;</a></li>
<?php } ?>
</ul>
</div><br />
<?php } ?><br />

<div id="actions">
<?php if($this->user_auth->get_permission('user_add')) { ?>
<a href="<?php echo site_url('user/popupAdduser')?>" class="thickbox button green primary popup" name="Add User">Add User</a><br /><br />
<a class="with-icon add" href="<?php echo site_url('user/import'); ?>">Import Users...</a> | 
<?php if($this->user_auth->get_permission('user_export')) { ?><a class="with-icon save" href="<?php echo site_url('user/export/'.$query_string); ?>">Export</a> &nbsp; &nbsp;<?php } ?>
<?php } ?>
</div>

<form action="<?php echo site_url('user/view_users'); ?>" method="post" id="filters" class="area">
<table>
<tr>
<td style="vertical-align:top;"><div class="field clear">
	<label for="city_id" class="above">City </label><br />
	<select name="city_id" id="city_id">
		<option value="0">Any City</option>
		<?php
		foreach($all_cities as $this_city_id => $this_city_name) { ?>
		<option value="<?php echo $this_city_id; ?>" <?php 
			if(!empty($city_id) and $city_id == $this_city_id) echo 'selected="selected"';
		?>><?php echo $this_city_name; ?></option>
		<?php } ?>
		</select>
	<p class="error clear"></p> 
	</div>

	<?php if($this->user_auth->get_permission('see_applicants')) { ?><br />
	<div class="field clear">
	<label for="user_type" class="above">User Type</label><br />
	<select name="user_type" id="user_type">
		<option value="0">All Type</option>
		<?php
		$types = array('applicant' => "Applicant", 'volunteer' => 'Volunteer', 'well_wisher' => 'Well Wisher', 'alumni' => 'Alumni', 'let_go' => 'Let Go', 'other' => 'Others');
		foreach($types as $user_type_key => $user_type_name) { ?>
		<option value="<?php echo $user_type_key; ?>" <?php 
			if(!empty($user_type) and $user_type_key == $user_type) echo 'selected="selected"';
		?>><?php echo $user_type_name; ?></option>
		<?php } ?>
	</select>
	<p class="error clear"></p> 
	</div>
	<?php } ?>

	<label for="credit" class="above">Credit </label><br />
	<select name="credit" id="credit">
		<option value="">All Credit</option>
		<option value="<0" <?php if(!empty($credit) and $credit === '<0') echo 'selected="selected"'; ?>>Less than Zero</option>
		<option value="<=0" <?php if(!empty($credit) and $credit === '<=0') echo 'selected="selected"'; ?>>Zero Or Less</option>
		<option value="<=1" <?php if(!empty($credit) and $credit === '<=1') echo 'selected="selected"'; ?>>One Or Less</option>
		<option value=">=0" <?php if(!empty($credit) and $credit === '>=0') echo 'selected="selected"'; ?>>Zero Or More</option>
		<option value=">=0" <?php if(!empty($credit) and $credit === '>=0') echo 'selected="selected"'; ?>>More than 0</option>
		<option value="<3" <?php if(!empty($credit) and $credit === '<3') echo 'selected="selected"'; ?>>Less than 3</option>
		<option value=">=3" <?php if(!empty($credit) and $credit === '>=3') echo 'selected="selected"'; ?>>Three or More</option>
	</select>
	<p class="error clear"></p> 
	</div>
</td>
		
<td style="vertical-align:top;"><div  class="field clear" style="margin-left:20px; margin-bottom:10px;">
	<label for="user_group"  class="above">Group </label><br />
	
	<select name="user_group[]" id="user_group" style="width:200px; height:130px;" multiple>
	<?php
	foreach($all_user_group as $id=>$gname) { ?>
	<option value="<?php echo $id; ?>"<?php 
		if(in_array($id, $user_group)) echo 'selected="selected"';
	?>><?php echo $gname; ?></option>
	<?php } ?>
	</select>
	<p class="error clear"></p>
	</div>
</td>
<td style="vertical-align:top;"><div  class="field clear" style="margin-left:20px;">

<label for="search_id">ID</label><?php echo form_input('search_id', $search_id); ?><br />
<label for="name">Name</label><?php echo form_input('name', $name); ?><br />
<label for="email">Email</label><?php echo form_input('email', $email); ?><br />
<label for="phone">Phone</label><?php echo form_input('phone', $phone); ?><br /><br />
<input type="submit"  class="button green" style="float:right;" value="Get Users"/>
</div>
</td>
</tr>
</table>
</form>

<form action="<?php echo site_url('user/bulk_communication'); ?>" method="post" id="communications">
<input type="hidden" name="query_string" value="<?php echo $query_string ?>" />
<?php if($this->user_auth->get_permission('user_bulk_email')) { ?>
<div id="email-area" class="area">
<textarea id="email-content" name="email-content" rows="15" cols="80" style="width: 75%" class="tinymce"></textarea><br />

<label for="email-subject">Subject</label>
<input type="text" id="email-subject" name="email-subject" value="" />

<input type="submit" name="action" value="Send Emails" class="button primary" />
</div><?php } ?>


<?php if($this->user_auth->get_permission('user_bulk_edit')) { ?>
<div id="bulk-edit-area" class="area form-area">
<table><tr><td>
<label for="user-type-bulk">User Type</label>
<select name="user-type-bulk">
	<option value="applicant">Applicant</option>
	<option value="volunteer" selected="selected">Volunteer</option>
	<option value="well_wisher">Well Wisher</option>
	<option value="alumni">Alumni</option>
	<option value="other">Other</option>
	<option value="" selected="selected">Leave Unchanged</option>
</select><br />

<label for="group-bulk">Group</label> 
<select id="group-bulk" name="group-bulk[]" multiple="multiple"> 
	<?php
	foreach($all_user_group as $id => $name) {
		if(($id == 1 or $id == 3) and !$this->user_auth->get_permission('permissions_index')) continue; // :HARD-CODE:. To make sure that city people can't create user with very big premissions.
	?>
	<option value="<?php echo $id; ?>"><?php echo $name; ?></option> 
	<?php } ?>
</select><br />
<label>&nbsp;</label><input type="submit" name="action" class="button green" value="Update" /><br />
</td><td valign="top" style="padding-left:30px;">
<?php if($this->user_auth->get_permission('debug')) { ?><br /><input type="submit" name="action"  class="button primary" value="Delete Selected Users" /><?php } ?>
</td>
</tr></table>
</div><?php } ?>

<div class="controls">
<ul id="tabs">
<li class="selected"><a class="with-icon settings" href="#" onclick="showFilters()">Filters</a></li>
<?php if($this->user_auth->get_permission('user_bulk_email')) { ?><li><a class="with-icon email" href="#" onclick="showEmail();">EMail...</a></li><?php } ?>
<?php if($this->user_auth->get_permission('user_bulk_edit')) { ?><li><a class="with-icon edit" href="#" onclick="showBulkEdit();">Bulk Edit Users...</a></li><?php } ?>
</ul>

</div>
<br /><br />

<table cellpadding="0"  cellspacing="0" class="clear data-table">
<thead>
<tr>
	<th class="col-select"><input type="checkbox" name="select-all" id="select-all" value="0" /></th>
	<th>#</th>
	<th>Actions</th>
	<th>Name</th>
	<th>Credits</th>
    <th>Contact Details</th>
    <?php if($this->input->post('city_id') === '0') { ?><th>City</th><?php } ?>
    <?php if($this->input->post('user_type') == 'applicant' or $this->input->post('user_type') == 'well_wisher') { ?>
    <th>Joined On</th>
    <th>Address</th>
    <?php } elseif($this->input->post('user_type') == 'let_go' or $this->input->post('user_type') == 'alumni') { ?>
    <th>Left On</th>
    <?php } if($this->input->post('user_type') == 'let_go') { ?>
    <th>Reason</th>
    <?php } else { ?>
    <th>User Groups</th>
    <?php if($user_type == 'volunteer') { ?>
    <th>Shelter</th>
    <th>Batch</th>
    <?php } ?>
    <?php } ?>
</tr>
</thead>
<tbody>

<?php 
$count = ($current_page - 1) * $items_per_page;
$days = array('Sun','Mon','Tue','Wed','Thur','Fri','Sat');
foreach($all_users as $id => $user) {
	$count++;
	$shadeClass = 'even';
	if($count % 2) $shadeClass = 'odd';
?>

<tr class="<?php echo $shadeClass; ?>" id="group"><!-- <?php print $count ?> -->
	<td class="col-select"><input type="checkbox" name="users[]" class="user-select" value="<?php echo $user->id ?>" />
	<input type="hidden" name="email[<?php echo $user->id ?>]" value="<?php echo $user->email ?>" />
	<input type="hidden" name="phone[<?php echo $user->id ?>]" value="<?php echo $user->phone ?>" /></td>
	<td><?php echo $count ?></td>
	<td class="col-actions"> 
	<?php if($this->user_auth->get_permission('user_edit')) { ?><a href="<?php echo site_url('user/popupEditusers/'.$user->id); ?>" class="thickbox icon edit popup" name="Edit User : <?php echo $user->name ?>">Edit</a><?php } ?>
    <?php if($this->user_auth->get_permission('user_delete')) { ?><a class="delete confirm icon" href="<?php echo site_url('user/delete/'.$user->id) ?>" title="Delete <?php echo $user->name ?>">Delete</a><?php } ?>
    </td>
    <td class="col-name"><a href="<?php echo site_url('user/view/'.$user->id) ?>"><?php echo $user->name; ?></a></td>
    <td class="col-credit"><a href="<?php echo site_url('user/credithistory/'.$user->id) ?>"><?php echo $user->credit; ?></a></td>
    <td class="col-email"><?php 
    	echo $user->email; 
    	if($user->mad_email) echo "<br />{$user->mad_email}";  ?><br />
    	<?php echo $user->phone; ?></td>
	<?php if($this->input->post('city_id') === '0') { ?><td class="col-city"><?php echo $user->city_name; ?></td><?php } ?>
	<?php if($this->input->post('user_type') == 'applicant' or $this->input->post('user_type') == 'well_wisher') { ?>
	<td class="col-joined_on"><?php echo date('d\<\s\u\p\>S\<\/\s\u\p\> M, Y', strtotime($user->joined_on)); ?></td>
    <td class="col-address"><?php echo $user->address; ?></td>
    <?php } elseif($this->input->post('user_type') == 'let_go' or $this->input->post('user_type') == 'alumni') { ?>
    <td class="col-left_on"><?php if($user->left_on != '0000-00-00') echo date('d\<\s\u\p\>S\<\/\s\u\p\> M, Y', strtotime($user->left_on)); ?></td>
    <?php } if($this->input->post('user_type') == 'let_go') { ?>
    <td class="col-reason_for_leaving"><?php echo $user->reason_for_leaving; ?></td>
    <?php } else { ?>
    <td class="col-groups"><?php echo implode(',', $user->groups); ?></td>
    <?php if($user_type == 'volunteer') { ?>
    <td class="col-center"><?php if($user->center_name) echo $user->center_name; ?></td>
    <td class="col-batch"><?php if($user->batch) echo $days[$user->batch->day] . ' ' . date('h:i A', strtotime(date('Y-m-d ').$user->batch->class_time)); ?></td>
    <?php } ?>
    <?php } ?>
</tr>

<?php } ?>
</tbody>
</table>

</form>

<?php 
if(!$total_items) {
	echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
}

if($total_pages > 1) {
	$query_parts = explode("/", $query_string);
	$final_part = count($query_parts) - 1;
	echo '<div class="pager"><span>Page: </span> ';
	if($current_page > 1) {
		$query_parts[$final_part] = $current_page - 1;
		echo "<a class='icon previous' href='".site_url('user/view_users/'.implode("/", $query_parts))."'>&lt;</a> ";
	}
	for($page = 1; $page <= $total_pages; $page++) {
		$query_parts[$final_part] = $page;
		if($page == $current_page) echo "<span class='page'>$page</span> ";
		else echo "<a class='page' href='".site_url('user/view_users/'.implode("/", $query_parts))."'>$page</a> ";
	}
	if($current_page < $total_pages) {
		$query_parts[$final_part] = $current_page + 1;
		echo "<a class='icon next' href='".site_url('user/view_users/'.implode("/", $query_parts))."'>&gt;</a> ";
	}
	echo "</div>";
}
?>

</div>
</div>

<script type="text/javascript" src="<?php echo base_url() ?>js/libraries/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">var base_url="<?php echo base_url() ?>";</script>
<script type="text/javascript" src="<?php echo base_url()?>js/sections/users/view_users.js"></script>
<?php
$this->load->view('layout/footer');
