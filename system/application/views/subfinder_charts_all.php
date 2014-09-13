<html>
<body>

<?php
	foreach($city_query->result() as $city_row){
		echo '<iframe src="./analyze/' . $city_row->name . '/true" width="700" height="400" seamless></iframe>';
	}
?>
</body>
</html>