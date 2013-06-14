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
				
				
				
				for($d = 30; $d>=0; $d--){ 
				
					$day = new DateTime("now -$d days");
					
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
    <div id="chart_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>