
<?php ?>
<?php foreach ($feedback->result_array() as $row) {

    if ($row['id']) {
        ?> 
        <?php
        $flag = 0;
        foreach ($student->result_array() as $row1):
            if ($row['id'] == $row1['student_id']) {
                $flag = 1;
            }
        endforeach;
        ?>
        <li>
            <label for="attendence_<?php echo $row['id'] ?>"><?php echo $row['name'] ?></label>
            <input type="checkbox" id="attendence_<?php echo $row['id'] ?>" name="attendance[]"  value="<?php echo $row['id'] ?>" <?php if ($flag == 1) { ?>checked="checked" <?php } ?> />
        </li>
    <?php }
} ?>
