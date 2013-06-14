<html>
	<head>
		<style>
			#container
			{
				position:absolute;
				left:50;
				top:180;
			}
							
			#text
			{
				
				position:relative;    
				color:white;
				font-size:25px;
				//text-decoration: none;
				font-family:Arial Black;
				
			}
			
			#bg
			{
			
			background-image:url("http://makeadiff.in/madadpp/images/subfinder_usage_bg.jpg");
			background-repeat:no-repeat;
			background-color:#ec1a47;
			
			}
			
		</style>
	</head>
	
	<body id="bg">
	
	
	
	<div id="container">
	
		
		<?php

			foreach($city_ordered as $city){
						
				echo '<a id="text" href="./analyze/' . $city->cityname . '">' . $city->cityname . '</a><br>';
					
			}
		?>
		
		
	</div>
		
		
		
	</body>
</html>