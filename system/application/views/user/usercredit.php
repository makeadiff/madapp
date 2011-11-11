<div id="updateDiv" >
<div id="content" class="clear">

<div id="main" class="clear">
<div id="head" class="clear">
<h1><?php echo $title; ?></h1>
</div>


<table class="clear data-table">
<thead>
<tr><th>#</th><th>Class Time</th><th>Class Status</th><th>Credit Change</th><th>Credit</th></tr>
</thead>
<tbody>

<?php foreach($credit_log as $credit) { ?>
<tr>
<td><?php echo $credit['i'] ?></a></td>
<td><?php echo date('d M, Y h:i A', strtotime($credit['class_on'])); ?></a></td>
<td><?php echo $credit['Substitutedby'] ?></a></td>
<td><?php echo $credit['lost'] ?></a></td>
<td><?php echo $credit['credit'] ?></td>   
</tr>   
<?php } ?>
</tbody>
</table>

</div>
</div>
</div>
