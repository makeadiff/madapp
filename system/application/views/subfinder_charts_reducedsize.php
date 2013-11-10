<html>
  <head>
  
    <script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">
      google.load("visualization", "1", {packages:["corechart"]});
      google.setOnLoadCallback(drawChart);
      function drawChart() {
		var data = google.visualization.arrayToDataTable([
			  ['Day', 'Requests', 'Replies'],
			  
			  <?php
				
				$flag = false;
				
				for($d = 30; $d>=0; $d--){ 
				
					$day = new DateTime("now -$d days");
					
					if(${$city_selected.$name_request.$d} != NULL || ${$city_selected.$name_reply.$d} != NULL)
						$flag = true;
					
					echo "['" . $day->format('d-M') . "'," . ${$city_selected.$name_request.$d} . "," . ${$city_selected.$name_reply.$d} . "],";
					
					
					
				}
				?>
			  ]);

		<?php $title = "Sub Finder Usage Data for " . $city_selected; ?>		
		
        var options = {
          title:<?php echo '"' . $title . '"';?>
        };

        var chart = new google.visualization.LineChart(document.getElementById('chart_div'));
        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
	<?php 
	if($flag == false)
		echo '<h3 style="text-align:center">' . $city_selected. ' has not started using SubFinder<h3>';
	else 
		echo '<h3 style="text-align:center">' . $city_selected . '<h3>';
	?>
	<div id="chart_div" style="width: 600px; height: 250px;"></div>
	
  </body>
</html>