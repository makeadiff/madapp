<?php
$this->load->view('layout/header',array('title'=>$title));
?>
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/sections/users/view_users.css">

<div id="content" class="clear">

<div id="main" class="clear">
<div id="head" class="clear">
<h1><?php echo $title; ?></h1>

<div id="actions">
<?php if($this->user_auth->get_permission('user_add')) { ?>
<a href="<?php echo site_url('user/popupAdduser')?>" class="thickbox button green primary popup" name="Add User">Add User</a>
<?php } ?>
</div><br class="clear" />
</div>
<?php if($this->user_auth->get_permission('center_edit')) { ?>
<div id="train-nav">
<ul>
<?php if($this->session->userdata("active_center")) { ?>
<li id="train-prev"><a href="<?php echo site_url('center/manage/'.$this->session->userdata("active_center"))?>">&lt; Edit Center Details</a></li>
<li id="train-top"><a href="<?php echo site_url('center/manage/'.$this->session->userdata("active_center"))?>">^ Manage Center</a></li>
<li id="train-next"><a href="<?php echo site_url('kids/manageaddkids')?>">Manage Kids &gt;</a></li>
<?php } else { ?>
<li id="train-prev">&nbsp;</li>
<li id="train-top"><a href="<?php echo site_url('center/manageaddcenters')?>">^ Manage Center</a></li>
<li id="train-next"><a href="<?php echo site_url('kids/manageaddkids')?>">Manage Kids &gt;</a></li>
<?php } ?>
</ul>
</div><br />
<?php } ?>

<form action="" method="post" id="filters">
<table style="margin-bottom:25px;">
<tr>
<td style="vertical-align:top;"><div class="field clear">
	<label for="city_id">Select City </label>
	<select name="city_id" id="city_id">
	<option value="0">Any City</option>
	<?php
	foreach($all_cities as $row) { ?>
	<option value="<?php echo $row->id; ?>" <?php 
		if(!empty($city_id) and $city_id == $row->id) echo 'selected="selected"';
	?>><?php echo $row->name; ?></option>
	<?php } ?>
	</select>
	<p class="error clear"></p> 
	</div>

	<?php if($this->user_auth->get_permission('see_applicants')) { ?><br />
	<div class="field clear">
	<label for="user_type">User Type</label>
	<select name="user_type" id="user_type">
	<option value="0">All Type</option>
	<?php
	$types = array('applicant', 'volunteer', 'well_wisher', 'alumni', 'other');
	foreach($types as $row) { ?>
	<option value="<?php echo $row; ?>" <?php 
		if(!empty($user_type) and $user_type == $row) echo 'selected="selected"';
	?>><?php echo ucfirst($row); ?></option>
	<?php } ?>
	</select>
	<p class="error clear"></p> 
	</div>
	<?php } ?>
</td>
		
<td style="vertical-align:top;"><div  class="field clear" style="margin-left:20px; margin-bottom:10px;">
	<label for="user_group">Group </label>
	
	<select name="user_group[]" id="user_group" style="width:150px; height:100px;" multiple>
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
	<label for="name">Name</label>
	<input name="name" id="name" type="text" value="<?php echo $name ?>">
	<p class="error clear"></p>
	</div>
</td>
<td style="vertical-align:bottom;"><div  class="field clear" style="margin-left:20px;">
<input type="submit" value="Get Users"/>
</div>
</td>                                     
</tr>
</table>
</form>

<form action="" method="post" id="communications">
<input type="hidden" name="query_string" value="<?php echo $query_string ?>" />
<div id="email-area">
<label for="email-subject">Subject</label>
<input type="text" id="email-subject" name="email-subject" value="" /><br />

<label for="email-content">Content</label><br />
<textarea id="email-content" name="email-content" rows="15" cols="80" style="width: 95%" class="tinymce"></textarea>
<input type="submit" name="action" value="Send Emails" />
</div>

<div id="sms-area">
<label for="sms-content">Content</label><br />
<textarea name="sms-content" rows="5" cols="70" style="width: 95%"></textarea><br />
<input type="submit" name="action" value="Send SMSs" />
</div>
<br /><br />

<a class="with-icon settings" href="#" onclick="showFilters()">Filters</a> &nbsp; &nbsp;
<a class="with-icon add" href="<?php echo site_url('user/import'); ?>">Import Users...</a> &nbsp; &nbsp;
<a class="with-icon save" href="<?php echo site_url('user/export/'.$query_string); ?>">Export</a> &nbsp; &nbsp;
<a class="with-icon email" href="#" onclick="showEmail();">EMail...</a> &nbsp; &nbsp;
<a class="with-icon sms" href="#" onclick="showSms();">SMS...</a> &nbsp; &nbsp;
<br /><br />

<table cellpadding="0"  cellspacing="0" class="clear data-table">
<thead>
<tr>
	<th class="col-select"><input type="checkbox" name="select-all" id="select-all" value="0" /></th>
	<th class="sortable">Name</th>
    <th>Email</th>
    <th>Phone</th>
    <?php if($this->input->post('city_id') === '0') { ?><th class="colPosition">City</th><?php } ?>
    <th class="sortable">Joined On</th>
    <th>User Groups</th>
    <th>Actions</th>
</tr>
</thead>
<tbody>

<?php 
$count = 0;
foreach($all_users as $id => $user) {
	$count++;
	$shadeClass = 'even';
	if($count % 2) $shadeClass = 'odd';
?> 
<tr class="<?php echo $shadeClass; ?>" id="group">
	<td class="col-select"><input type="checkbox" name="users[]" value="<?php echo $user->id ?>" />
<input type="hidden" name="email[<?php echo $user->id ?>]" value="<?php echo $user->email ?>" />
<input type="hidden" name="phone[<?php echo $user->id ?>]" value="<?php echo $user->phone ?>" /></td>
    <td class="col-name"><a href="<?php echo site_url('user/view/'.$user->id) ?>"><?php echo $user->name; ?></a></td>
    <td class="col-email"><?php echo $user->email; ?></td>
    <td class="col-phone" style="text-align:left"><?php echo $user->phone; ?></td>
    <?php if($this->input->post('city_id') === '0') { ?><td class="col-city"><?php echo $user->city_name; ?></td><?php } ?>
	<td class="col-join"><?php echo date('dS M, Y', strtotime($user->joined_on)); ?></td>
    <td class="col-groups"><?php echo implode(',', $user->groups); ?></td>
    
    <td class="col-actions"> 
	<?php if($this->user_auth->get_permission('user_edit')) { ?><a href="<?php echo site_url('user/popupEditusers/'.$user->id); ?>" class="thickbox icon edit popup" name="Edit User : <?php echo $user->name ?>">Edit</a><?php } ?>
    <?php if($this->user_auth->get_permission('user_delete')) { ?><a class="delete confirm icon" href="<?php echo site_url('user/delete/'.$user->id) ?>" title="Delete <?php echo $user->name ?>">Delete</a><?php } ?>
    </td>
</tr>

<?php }?>
</tbody>
</table>
</form>

<?php if(!$count) echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>"; ?>

</div>
</div>

<script type="text/javascript" src="<?php echo base_url() ?>js/libraries/tiny_mce/jquery.tinymce.js"></script>
<script type="text/javascript">var base_url="<?php echo base_url() ?>";</script>
<script type="text/javascript" src="<?php echo base_url()?>js/sections/view_users.js"></script>
<?php
$this->load->view('layout/footer');
