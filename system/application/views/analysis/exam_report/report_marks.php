<td>

<?php if(sizeof($marks) > 0){?>
 <?php foreach($marks as $row){?>
 <table>
<tr>
 <?php echo $row->name.':'.$row->mark;?>

 </tr>
  </table>
 <?php  }}else{?> <table><tr>
 <!--<td width="33">-no-</td>
 <td width="33">-no-</td>-->
 </tr>
  </table>
 <?php } ?>



</td>


