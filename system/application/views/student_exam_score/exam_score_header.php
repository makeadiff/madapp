
<script>
	
	tb_init('a.thickbox, input.thickbox');
	
	function triggerSearch()
	{
		q = $('#searchQuery').val();
		get_groupList('0',q);
	}
	
	$(document).ready(function(){
	
		
		$('#example').each(function(){
			var url = $(this).attr('href') + '?TB_iframe=true&height=500&width=900';
	
			$(this).attr('href', url);
		});
		
	
	}
	);  
	
</script>

<div id="content" class="clear">

<!-- Main Begins -->
	<div id="main" class="clear">
   	  <div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>

            <!-- start page actions-->
        	<div id="actions"> 
<a href="<?= site_url('exam/popupAddMark')?>" class="thickbox button primary" id="example" name="<strong>Add Mark</strong>">Add Exam Mark</a>
</div>
			<!-- end page actions-->

      </div>

		

<table cellpadding="0"  cellspacing="0" class="clear data-table" id="tableItems">
<thead>
<tr>
<th class="colName left sortable" style="width:10px;">Id</th>
<th class="colName left sortable">Name</th>
<?php 
$subject = $subject->result_array();
foreach($subject as $row){
 ?> 
	<th class="colCheck1"><?php echo $row['name']; ?></th>
	
    <?php }?>
</tr>
</thead>
<tbody>
