<?php
$details=$details->result_array();
foreach($details as $row) {
        $corporate_partner=$row['corporate_partner'];
        $corporate_volunteer_count=$row['corporate_volunteer_count'];
        $corporate_poc=$row['corporate_poc'];
        $cr_intern_user_id=$row['cr_intern_user_id'];
}
?>
<li>
<label for="txtName">Corporate Name : </label>
<input id="corpname" name="corpname" type="text" value="<?php echo $corporate_partner; ?>"/>
    </li>
    <li>
<label for="txtName">No: of Volunteers : </label>
<input id="novol" name="novol" type="text" value="<?php echo $corporate_volunteer_count; ?>"/>
    </li>
    <li>
<label for="txtName">Corporate POC : </label>
<input id="corpoc" name="corpoc" type="text" value="<?php echo $corporate_poc; ?>"/>
    </li>
    <li>
<label for="txtName">CR Intern : </label>
<input id="crintrn" name="crintrn" type="text" value="<?php echo $cr_intern_user_id; ?>"/>
    </li>