<div id="content" class="clear">
<!-- Main Begins -->
	<div id="main" class="clear">
   	  <div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>

            <!-- start page actions-->
        	
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
