<?php

/** URL: http://www.mwasif.com/
 ** Tutorial: http://www.mwasif.com/2007/4/save-image-in-mysql-with-php/
 **/
 
// change your configuration according to your environment


$mysql_user = "root";
$database_host = "localhost";
$password = "";
$database = "Project_Madapp";

/*
$mysql_user = "arunrajr_maxpro";
$database_host = "localhost";
$password = "maxpropass";
$database = "arunrajr_exam";
*/
$link=mysql_connect($database_host, $mysql_user, $password) 
or 
die ("Unable to connect to DB server. Error: ".mysql_error());
mysql_select_db($database,$link);

?>