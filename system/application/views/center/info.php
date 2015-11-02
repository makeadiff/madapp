<?php $this->load->view('layout/flatui/header', array('title'=>'City Information')); ?>

<div class="container" id="content">
<h2 class="title"><?php echo $center->name ?> Information</h2>

<table border="1" width="100%">
<tr><th>Level</th><th>Students</th><th>Batche Info</th></tr>
<?php 
// dump($data);

foreach($data as $level) {
	print "<tr><td>" . $level['level_name'] . "</td>";

	print "<td><table>";
	foreach ($level['kids'] as $student_id => $name) {
		print "<tr><td>$name</td></tr>";
	}
	print "</table></td>";

	print "<td valign='top'><table width='100%' border='1'><tr><th>Batches</th><th>Teachers</th></tr>";
	foreach ($level['batch'] as $batch_id => $batch) {
		print "<tr><td width='50%'>" . $batch['name'] . "</td><td><table>";

		foreach ($batch['teachers'] as $user_id) {
			print "<tr><td>" . (isset($all_users[$user_id]) ? $all_users[$user_id] : 'None') . "</td></tr>";
		}

		print "</table></td></tr>";
	}

	print "</table></td>";

	print "</tr>";
} ?>
</table>
<!--
(
    [2570] => Array
        (
            [level_name] => 6 A
            [kids] => Array
                (
                    [11463] => Binny
                    [11459] => Cathy
                    [11466] => Jithin
                    [11469] => Rijuta
                )

            [batch] => Array
                (
                    [1195] => Array
                        (
                            [name] => 0 16:00:00
                            [teachers] => Array
                                (
                                    [0] => 45482
                                    [1] => 42117
                                )

                        )

                )

        )

-->
</div>

<?php $this->load->view('layout/flatui/footer'); ?>

