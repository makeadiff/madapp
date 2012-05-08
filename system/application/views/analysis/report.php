<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>

</head>

<body><table width="200" border="1">
  <tr>
    <td width="44"><?php echo $name; ?></td>
     </tr>
  <tr >
    <td>&nbsp;</td>
    <td width="22">LEVEL</td>
    <td width="33">kids</td>
    <?php foreach($all_exams as $row){?><td width="33"><?php echo $row->name;?></td><?php  }?>
    <td width="34">Class</td>
    <td width="34">Agg</td>
    
    
    
    
     </tr>
  <tr>
    <td>&nbsp;</td>
   
    <td><?php echo $levelname;?></td>
 
 
 
 
 <td><?php echo $kidsname;?></td>
<td>

<?php if(sizeof($marks) > 0){?>
 <?php foreach($marks as $row){?>
 <table>
<tr>
 <td width="33"><?php echo $row->name;?></td>
 <td width="33"><?php echo $row->mark;?></td>
 </tr>
  </table>
 <?php  }}else{?> <table><tr>
 <td width="33">-no-</td>
 <td width="33">-no-</td>
 </tr>
  </table>
 <?php } ?>

</td>








<?php if($attendance > 0){?>
<td>
<?php echo $attendance;?>
</td>
<?php } else { ?>
<td>

</td>
<?php } ?>
<td></td>
</tr>
<tr>
<td>total</td>
<td>d</td>


</tr>
</table>
</body>
</html>
    