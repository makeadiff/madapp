<h3><?php echo $name; ?></h3>

<table border="1" class="madsheet data-table info-box-table">
  <tr>
    <th width="10%">Level</th>
    <th width="10%">Kids</th>
    <?php foreach($all_exams as $row){?><th><?php echo $row->name;?></th><?php  }?>
    <th width="10%">Classes Attended</th>
    <th width="10%">Aggr</th>
  </tr>