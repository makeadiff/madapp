<script type="text/javascript">
function get_kids_Name(center_id,pageno){
	$.ajax({
		type: "POST",
		url: "<?= site_url('kids/get_kids_details') ?>",
		data: "center_id="+center_id+"&page_no="+pageno,
		success: function(msg){
			$('#kids_list').html(msg);
		}
	});
}


</script>
<div align="center"><?php if($this->session->userdata('success') ){ echo $this->session->userdata('success'); $this->session->unset_userdata('success');}?></div>

<div id="content" class="clear">
<!-- Main Begins -->
<div id="main" class="clear"> 

<select name="center" id="center" onchange="javascript:get_kids_Name(this.value,0);" >
<option value="0">All Kids</option>
<?php foreach($center_list as $row){ ?>
<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
<?php } ?>
</select>

<div id="head" class="clear">
<h1><?php echo $title; ?></h1>

<div id="actions">
<?php if($this->user_auth->get_permission('kids_add')) { ?>
<a href="<?php echo site_url('kids/popupaddKids')?>" class="thickbox button primary popup" name="Add Kids">Add Kids</a>
<?php } ?>
</div><br class="clear" />

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
</div><br />

<div id="kids_list">
<table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
<thead>
<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Name</th>
    <th class="colStatus sortable">Birth Day</th>
    <th class="colStatus">Center</th>
	<th class="colStatus">Image</th>
   <th class="colActions">Actions</th>
</tr>
</thead>
<tbody>

<?php 
$statusIco = '';
$statusText = '';
$content = $details->result_array();
$count = 0;
foreach($content as $row) {	
	$count++;
	$shadeClass = 'even';
	if($count % 2) $shadeClass = 'odd';
?> 
<tr class="<?php echo $shadeClass; ?>" id="group">
    <td class="colCheck1"><?php echo $row['id']; ?></td>
    <td class="colName left"><?php echo $row['name']; ?></td>
    <td class="colCount"><?php echo $row['birthday']; ?></td> 
    <td class="colStatus" style="text-align:left"><?php echo $row['center_name'];?></td>
	<td class="colPosition"><?php if($row['photo']) { ?><img src="<?php echo base_url().'pictures/'.$row['photo']; ?>" width="50" height="50" /><?php } ?></td>
    
    <td class="colActions right"> 
    <?php if($this->user_auth->get_permission('kids_edit')) { ?><a href="<?php echo site_url('kids/popupEdit_kids/'.$row['id'])?>" class="thickbox with-icon edit popup" name="Edit student: <?php echo  $row['name'] ?>">Edit</a><?php } ?>
    <?php if($this->user_auth->get_permission('kids_delete')) { ?>
    
    <a class="actionDelete icon delete confirm" href="<?php echo site_url('kids/ajax_deleteStudent/'.$row['id']); ?>">Delete</a>
	<?php } ?>
    </td>
</tr>

<?php  }?>
</tbody>
</table>
</div>
<?php if(!$count) {
	   echo "<div style='background-color: #FFFF66;height:30px;text-align:center;padding-top:10px;font-weight:bold;' >- no records found -</div>";
} ?>

</div>
<br /><br />
<a class="add with-icon" href="<?php echo site_url('kids/import'); ?>">Import Kids</a>

</div>
