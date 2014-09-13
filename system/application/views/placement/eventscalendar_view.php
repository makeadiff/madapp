<?php
//session_start();
//include_once('includes/logincheck.php');
//include_once('includes/dbconfig.php');

$monthNames = Array("January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December");

if (!isset($_REQUEST["month"]))
    $_REQUEST["month"] = date("n");
if (!isset($_REQUEST["year"]))
    $_REQUEST["year"] = date("Y");


$cMonth = $_REQUEST["month"];
$cYear = $_REQUEST["year"];

$prev_year = $cYear;
$next_year = $cYear;

$prev_month = $cMonth - 1;
$next_month = $cMonth + 1;

if ($prev_month == 0) {
    $prev_month = 12;
    $prev_year = $cYear - 1;
}
if ($next_month == 13) {
    $next_month = 1;
    $next_year = $cYear + 1;
}
?> 


<script type="text/javascript">
    Cufon.replace('h1') ('h1 a') ('h2') ('h3') ('h4') ('h5') ('h6') ('blockquote') ('.txtbold');
</script>

<script type="text/javascript">
   
	 
	 
    //	 function showdiv(a,b,c){
    //			a=a+"/"+b+"/"+c;
    //			$('#hid').html(a);
    //			$('#hid1').val(a);
    //		  	$('#timediv').show();
    //		}
		
    function calldiv(a)
    {
        alert(a);
        //	  	a=a+"-"+b+"-"+c;
        //	  	$.ajax({
        //			 url: 'timepage.php',
        //			 data:'dt='+a,
        //			 success: function(data) { 
        //				   $('#timediv').html(data);
        //				    
        //			 }
        //		   });
    }
</script>
<style>
    .month {color:#333333; font-size:14px; text-align:center; height:30px; width:100%;}
    .sunday {color:#FF0000; font-weight:bold; text-align:center; width:90px;}
    .available {color:#29aae7; font-weight:bold; text-align:center; width:90px;}
    .not_available {color:#FF0000; font-weight:bold; text-align:center; width:90px; border-bottom:1px dotted #E6E6E6;}
    .block_link {text-align:center; width:90px;}
    .block_link a {color:#29aae7; display:block; padding:2px 6px; text-decoration:none; border-bottom:1px dotted #E6E6E6;}
    .block_link a:hover {background-color:#29aae7; color:#FFFFFF;}

    .available-time { max-width:150px; height:40px; float:left; margin-bottom:10px; border-right:1px solid #CCCCCC;}
    .available-time radio {max-width:15px; float:left;}
    .available-time-li { max-width:125px; height:20px; float:right; list-style-type:none; margin-right:5px;}
    .available-time-li span a { max-width:125px;height:20px;color:#29aae7; font-weight:bold; font-size:14px;display:block; text-decoration:none;}
    .available-time-li div { max-width:125px;height:20px;font-size:11px; color:#999999; float:right;}

    .not-available-time { max-width:150px; height:40px; float:left; margin-bottom:10px; border-right:1px solid #CCCCCC;}
    .not-available-time radio {max-width:15px; float:left;}
    .not-available-time-li { max-width:125px; height:20px; float:right; list-style-type:none; margin-right:5px;}
    .not-available-time-li span a { max-width:125px;height:20px; color:#FF0000; font-weight:bold; font-size:14px;display:block; text-decoration:none;}
    .not-available-time-li div { max-width:125px; margin-left:15px; height:20px; float:right; font-size:11px; color:#999999;}

    .button1 {padding:2px 5px; background-color:#333333; color:#FFFFFF; border:none; cursor:pointer; float:left; margin-right:10px; text-decoration:none;}
    .button1 a {text-decoration:none;}
    .button1:hover {background-color:#666666;}
    .open {border-bottom: 1px dotted #441681; color:#ff2052; text-decoration: none;}
    .closed {border-bottom: 1px dotted #441681; color:#33CCCC; text-decoration: none;}
</style>
<div id="head" class="clear">
    <h1><?php echo $title; ?></h1>
    <!-- start page actions-->
    <a href="<?php echo site_url('placement/placement_view') ?>">< Placement Dashboard</a>

    <!-- end page actions-->
</div>
<div id="wrapper">
    <div id="container">

        <?php //include_once "includes/header.php" ?><!-- end #top -->
        <?php
        //require_once("includes/menubar.php");
        ?>
        <!-- end #topnavigation -->


        <div id="content">
            <div id="content-left">
                <div id="maintext">


                    <div style="border:1px solid #CCCCCC; width:635px; margin-left:20px;margin-top:20px;">    
                        <table width="600">
                            <tr align="center">
                                <td bgcolor="#999999" style="color:#FFFFFF">
                                    <table width="100%" border="0" cellspacing="0" cellpadding="0">
                                        <tr>
                                            <td width="50%" align="left">&nbsp;&nbsp;<a href="<?php echo $_SERVER["PHP_SELF"] . "?month=" . $prev_month . "&year=" . $prev_year; ?>" style="color:#FFFFFF">Previous Month</a></td>
                                            <td width="50%" align="right"><a href="<?php echo $_SERVER["PHP_SELF"] . "?month=" . $next_month . "&year=" . $next_year; ?>" style="color:#FFFFFF">Next Month</a>&nbsp;&nbsp;</td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            <tr>
                                <td align="center">
                                    <table width="630" border="0" cellpadding="2" cellspacing="2">

                                        <tr>
                                            <td colspan="7" class="month"><h4><?php echo $monthNames[$cMonth - 1] . ' ' . $cYear; ?></h4></td>
                                        </tr>
                                        <tr>
                                            <td class="sunday"><strong>Sunday</strong></td>
                                            <td class="available"><strong>Monday</strong></td>
                                            <td class="available"><strong>Tuesday</strong></td>
                                            <td class="available"><strong>Wednesday</strong></td>
                                            <td class="available"><strong>Thursday</strong></td>
                                            <td class="available"><strong>Friday</strong></td>
                                            <td class="available"><strong>Saturday</strong></td>
                                        </tr> 

                                        <?php
                                        $timestamp = mktime(0, 0, 0, $cMonth, 1, $cYear);
                                        $maxday = date("t", $timestamp);
                                        //echo $maxday;
                                        $thismonth = getdate($timestamp);
                                        //print_r( $thismonth);
                                        $startday = $thismonth['wday'];
                                        //echo $startday;

                                        for ($i = 0; $i < ($maxday + $startday); $i++) {
                                            if (($i % 7) == 0)
                                                echo "<tr>\n";

                                            if ($i < $startday)
                                                echo "<td></td>\n";

                                            else {
                                                $cdt = date('Y-m-d');

                                                $tdate = $i - $startday + 1;
                                                $crdt = date('Y-m-d', mktime(0, 0, 0, $cMonth, $tdate, $cYear));



                                                $s = 0;
                                                $event = 0;
                                                foreach ($calenderdetails->result_array() as $row) {

                                                    $bar = explode(" ", $row['started_on']);
                                                    $started_date = $bar[0];
                                                    if ($started_date == $crdt) {
                                                        //$s = 1;
                                                        $event = $started_date;
                                                    
//           
                                                    $current_dt=   strtotime(date('Y-m-d'));
                                                    $db_dat=strtotime($row['started_on']);
                                                    if($db_dat > $current_dt)                                         
                                                    {
                                                        $s = 1;
                                                    }
 else {
     $s=2;
 }
                    }                               
                                                }

                                                if ($s == 1) {
                                                    $escaped_text = HtmlSpecialChars(json_encode($event));
                                                    echo "<td class='not_available'><a class='open' href='".site_url('placement/popuplistevents/'.$event)."'  >" . ($i - $startday + 1) . "</a></td>\n";
                                                    //javascript: calldiv(" . $escaped_text . ");
                                                }
                                               else if ($s == 2) {
                                                    $escaped_text = HtmlSpecialChars(json_encode($event));
                                                    echo "<td class='not_available'><a class='closed' href='".site_url('placement/popuplistevents/'.$event)."'  >" . ($i - $startday + 1) . "</a></td>\n";
                                                    //javascript: calldiv(" . $escaped_text . ");
                                                }
                                                
                                                
                                                else if ($row['started_on'] != $crdt) {
                                                    echo "<td class='block_link'>" . ($i - $startday + 1) . "</td>\n";
                                                }
                                            }
                                            if (($i % 7) == 6)
                                                echo "</tr>\n";
                                        }
                                        ?>
                                    </table>
                                </td>
                            </tr>
                        </table> 
                    </div>
                    <div class="clear"></div>

                    <div id="timediv" style=" width:640px; float:left; margin-left:200px;">

                    </div> 





                </div>
                <!-- end #maintext -->
            </div><!-- end #content-left -->
            <!-- end #content-right -->
            <div class="clear"></div>
        </div><!-- end #content -->


        <div id="footer">		
        </div><!-- end #footer -->
    </div><!-- end #container -->	
</div>

