<table width="200" border="1" class="madsheet data-table info-box-table">
 <h3><?php echo $name; ?></h3> 
  <tr>
    <!--<th>&nbsp;</th>-->
    <th width="22">Level</th>
    <th width="33">kids</th>
    <?php foreach($all_exams as $row){?><th width="33"><?php echo $row->name;?></th><?php  }?>
    <th width="34">Classes Attended</th>
    <th width="34">Aggr</th>
    