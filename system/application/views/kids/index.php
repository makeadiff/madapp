<div id="content" class="clear">
<!-- Main Begins -->
<div id="main" class="clear"> 

<form action="<?php echo site_url('kids/index'); ?>" method="post">
<select name="center_id">
<option value="0">All Children</option>
<?php foreach($center_list as $row){ ?>
<option value="<?php echo $row->id; ?>" <?php if($center_id == $row->id) { echo 'selected'; } ?>><?php echo $row->name; ?></option>
<?php } ?>
</select>
<input type="submit" name="action" value="Go" />
</form>

<div id="head" class="clear">
<h1><?php echo $title; ?></h1>

<div id="actions">
<?php if($this->user_auth->get_permission('kids_add') and $page == 'index') { ?>
<a href="<?php echo site_url('kids/popupaddKids')?>" class="thickbox button green primary popup" name="Add Children">Add Children</a>
<?php } ?>
</div><br class="clear" />

<?php if($this->user_auth->get_permission('center_edit') and $page == 'index') { ?>
<div id="train-nav">
<ul>
<li id="train-prev"><a href="<?php echo site_url('user/view_users')?>">&lt; Manage Volunteers</a></li>
<?php if($this->session->userdata("active_center")) { ?>
<li id="train-top"><a href="<?php echo site_url('center/manage/'.$this->session->userdata("active_center"))?>">^ Manage Center</a></li>
<li id="train-next"><a href="<?php echo site_url('level/index/center/'.$this->session->userdata("active_center"))?>">Manage Levels &gt;</a></li>
<?php } else { ?>
<li id="train-top"><a href="<?php echo site_url('center/manageaddcenters')?>">^ Manage Center</a></li>
<?php } ?>
</ul>
</div>
<?php } ?>
</div><br />

<div id="kids_list">
<?php if($this->user_auth->get_permission('kids_add') and $page == 'index') { ?>
<a class="add with-icon" href="<?php echo site_url('kids/import'); ?>">Import Children</a>
<?php } ?>

<?php if($this->user_auth->get_permission('kids_show_deleted') and $page == 'index') { ?>
<a class="delete with-icon" href="<?php echo site_url('kids/show_deleted'); ?>">Show Deleted Children</a>
<?php } ?>

<?php if($page == 'show_deleted') { ?>
<a class="done with-icon" href="<?php echo site_url('kids/manageaddkids'); ?>">Show Actual Children</a>
<?php } ?>



<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colName left sortable">Name</th>
	<th class="colStatus sortable">Sex</th>
    <th class="colStatus sortable">Birth Day</th>    
    <?php if($center_id) { ?>
        <th class="colStatus">Level</th>
    <?php } else { ?>
        <th class="colStatus">Center</th>
    <?php } ?>
	<th class="colActions">Actions</th>
</tr>
</thead>
<tbody>

<?php
$statusIco = '';
$statusText = '';
$count = 0;
foreach($details as $row) {	
	$count++;
	$shadeClass = 'even';
	if($count % 2) $shadeClass = 'odd';
?> 
<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colName left"><?php echo $row->name; ?></td>
    <td><?php $the_sexes = array('m'=>'Male','f'=>'Female', 'u' => 'Unknown'); if($row->sex) echo $the_sexes[$row->sex]; ?></td>
    <td><?php if($row->birthday != '0000-00-00' and $row->birthday != '1970-01-01') echo date('dS M, Y', strtotime($row->birthday)); ?></td>
    <?php if($center_id) { ?>
        <td><?php if(isset($student_level_mapping[$row->id]) and isset($all_levels[$student_level_mapping[$row->id]])) 
                        echo $all_levels[$student_level_mapping[$row->id]] ?></td>
    <?php } else { ?>
        <td><?php echo $row->center_name;?></td>
    <?php } ?>

    <td class="colActions right"> 
    <?php if($this->user_auth->get_permission('kids_edit') and $page == 'index') { ?><a href="<?php echo site_url('kids/popupEdit_kids/'.$row->id)?>" class="thickbox icon edit popup" name="Edit student: <?php echo  $row->name ?>">Edit</a><?php } ?>
    <?php if($this->user_auth->get_permission('kids_delete') and $page == 'index') { ?><a class="actionDelete icon delete popup" href="<?php echo site_url('kids/popupDelete_kid/'.$row->id); ?>">Delete</a><?php } ?>
    </td>
</tr>

<?php } ?>
</tbody>
</table>
</div>
<?php if(!$count) {
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
} ?>

</div>

</div>
