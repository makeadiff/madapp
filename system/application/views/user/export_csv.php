<?php
$days = array('Sun','Mon','Tue','Wed','Thur','Fri','Sat');
foreach($all_users as $id => $user) {
	echo "{$user->name},{$user->email},{$user->phone},{$user->city_name}," . $user->joined_on . ",\"";
	echo implode(',', $user->groups) . "\",";
	
	if($user->batch) echo "\"" . $user->batch->name . "\",\"" . $days[$user->batch->day] . ' ' . date('h:i A', strtotime(date('Y-m-d ').$user->batch->class_time)) . "\",\"";
	else echo '"","",';
	
	echo "{$user->sex},{$user->birthday},{$user->job_status},{$user->source},{$user->preferred_day},{$user->source},\"" 
			. str_replace("\n",",", $user->address) . "\",\"" . str_replace("\n",",", $user->why_mad) . "\"";
	echo "\n";
}
