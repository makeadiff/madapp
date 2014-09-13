<?php
include_once "db.php";
header('Content-type: image/jpeg');
$query = "SELECT image from image_questions where id=". intval($_GET["id"]);
$rs = mysql_fetch_array(mysql_query($query));
echo base64_decode($rs["image"]);
?>