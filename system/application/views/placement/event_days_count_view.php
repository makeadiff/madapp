 <?php 
 $days = '0000-00-00';
foreach($event_days->result_array() as $row)
{
    $days = $row['started_on'];
   
    
}
$day = strtotime($days);
if(!empty($day))
{
$days = (strtotime(date("Y-m-d")) - strtotime($days)) / (60 * 60 * 24);
}
else
{
$days = 0;
}

echo ceil($days);



 
 ?>