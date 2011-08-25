<?php
foreach($all_users as $id => $user) {
	echo "{$user->name},{$user->email},{$user->phone},{$user->city_name}," . date('dS M Y', strtotime($user->joined_on)) . ",\"" . implode(',', $user->groups) . "\"\n";
}
