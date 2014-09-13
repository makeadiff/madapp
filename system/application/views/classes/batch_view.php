<?php
$this->load->view('layout/flatui/header', array('title'=>'Batch View'));
?>



    <script type="text/javascript">
        $(document).ready(function(){
            $('.substitute_select').change(function(){
                if($(this).val() == -1){
                    var flag = $(this).attr('id').replace(/\D/g,"");
                    showCities(flag);
                }
            });

          /*  $('#class_tab a').click(function (e) {
                e.preventDefault()
                $(this).tab('show')
            })*/


        });

        function showCities(flag) {
            $.ajax({
                type: "POST",
                url: "<?php echo site_url('classes/other_city_teachers')?>"+'/'+flag,
                success: function(msg){
                    $('#sidebar').html(msg);
                }
            });
        }
    </script>

    <?php
    $prev_week = change_week($from_date, -1);
    $next_week = change_week($from_date, 1);

    if($to_date) {
        $prev_week .= '/'. change_week($to_date, -1);
        $next_week .= '/'. change_week($to_date, 1);
    }

    ?>

    <!-- Nav tabs -->

        <ul class="nav nav-tabs" id="class_tab">
            <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Home</a>
                <ul class="dropdown-menu">
                    <li><a href="#" id="classTab1" tabindex="-1" role="tab" data-toggle="tab">Hai</a> </li>
                    <li><a href="#" id="classTab2" tabindex="-1" role="tab" data-toggle="tab">Hoi</a> </li>

                </ul>

            </li>
        </ul>



    <!-- Tab panes -->
    <div class="tab-content">
        <div class="tab-pane active" id="home">...</div>
        <div class="tab-pane" id="profile">Howdy</div>
        <div class="tab-pane" id="messages">...</div>
        <div class="tab-pane" id="settings">...</div>
    </div>

    <div class="row">
        <div class="col-md-4 col-md-offset-4 col-sm-12">
            <h2 class="title">Batch View</h2><br>
            <h4 class="title"><?php echo $center_name; ?></h4>
            <h4 class="title"><?php echo $batch_name ?></h4>
            <h4 class="title"><a href="<?php echo site_url('classes/batch_view/'.$batch_id.'/'.$prev_week) ?>">&lt;- </a><?php echo date('d<\s\u\p>S</\s\u\p> M, Y', strtotime($from_date)); if($to_date) echo ' to ' . date('dS M, Y', strtotime($to_date)); ?><a href="<?php echo site_url('classes/batch_view/'.$batch_id.'/'.$next_week) ?>"> -&gt;</a></h4>

            <form action="<?php echo site_url('classes/batch_view_save'); ?>" method="post">
                <div class="table-responsive">
                <table class="table table-condensed">
                    <tr><th>Level</th><th>Unit Taught</th><th>Students</th><th>Teacher</th><th>Substitute</th><th>Attendance</th><th>Zero Hour</th><th>Cancellation</th></tr>

                    <?php
                    $row_count = 0;
                    $teacher_row_count = 0;
                    $statuses = array(
                        'attended'	=> 'Attended',
                        'absent'	=> 'Absent',
                    );
                    foreach($classes as $class) {
                        $teacher_count = count($class['teachers']);
                        $rowspan = '';
                        if($teacher_count > 1) $rowspan = "rowspan='$teacher_count'";

                        for($teacher_index=0; $teacher_index < $teacher_count; $teacher_index++) {
                            ?>
                            <tr class="<?php echo ($row_count % 2) ? 'odd' : 'even'; if($class['teachers'][0]['status'] == 'cancelled') echo ' cancelled';  ?>">
                                <?php
                                if($teacher_index == 0) {
                                    ?>
                                    <td <?php echo $rowspan ?>><a href="<?php echo site_url('classes/edit_class/'.$class['id'].'/batch') ?>"><?php echo $class['level_name'] ?></a></td>
                                    <td <?php echo $rowspan ?>><?php echo form_dropdown('lesson_id['.$class['id'].']', $all_lessons[$class['level_id']], $class['lesson_id'], 'style="width:100px;"'); ?></td>
                                    <td <?php echo $rowspan ?>><a href="<?php echo site_url('classes/mark_attendence/'.$class['id']); ?>"><?php echo $class['student_attendence'] ?></a></td>

                                <?php } ?>
                                <td><a href="<?php echo site_url('user/view/'.$class['teachers'][$teacher_index]['id']) ?>"><?php echo $class['teachers'][$teacher_index]['name'] ?></a></td>
                                <td><div id="substitute_<?php echo $teacher_row_count ?>">
                                        <?php
                                        if($class['teachers'][$teacher_index]['substitute_id'] and !isset($all_user_names[$class['teachers'][$teacher_index]['substitute_id']])) { // Inter city substitution...
                                            echo "<a href='javascript:showCities(".$teacher_row_count.");'>";
                                            echo $this->user_model->get_user($class['teachers'][$teacher_index]['substitute_id'])->name;
                                            echo "</a>";

                                        } else {
                                            echo form_dropdown('substitute_id['.$class['id'].']['.$class['teachers'][$teacher_index]['id'].']', $all_user_names,
                                                $class['teachers'][$teacher_index]['substitute_id'], 'id="other_city_'.$teacher_row_count.'" style="width:100px;" class="substitute_select"');
                                        }
                                        ?>
                                    </div></td>
                                <td><?php echo form_dropdown('status['.$class['id'].']['.$class['teachers'][$teacher_index]['id'].']', $statuses, $class['teachers'][$teacher_index]['status'], 'style="width:100px;"'); ?></td>
                                <td><input type="checkbox" name="zero_hour_attendance[<?php echo $class['id'].']['.$class['teachers'][$teacher_index]['id']; ?>]" value="1" <?php if($class['teachers'][$teacher_index]['zero_hour_attendance'] != '0') echo 'checked="checked"'; ?>/></td>

                                <?php if($teacher_index == 0) { ?><td <?php echo $rowspan ?>>
                                    <?php if($class['teachers'][0]['status'] == 'cancelled') { ?><a class="uncancel" href="<?php echo site_url('classes/uncancel_class/'.$class['id'].'/'.$batch_id.'/'.$from_date) ?>">Undo Class Cancellation<a/>
                                    <?php } else { ?><a href="<?php echo site_url('classes/cancel_class/'.$class['id'].'/'.$batch_id.'/'.$from_date) ?>">Cancel Class<a/><?php } ?>
                                    </td><?php } ?>
                            </tr>
                            <?php
                            $teacher_row_count++;
                        }
                        $row_count++;
                    } // Level end ?>
                </table>
                </div>

                <input type="hidden" name="batch_id" value="<?php echo $batch_id ?>" />
                <input type="hidden" name="from_date" value="<?php echo $from_date ?>" />
                <input type="hidden" name="to_date" value="<?php echo $to_date ?>" />
                <input type="submit" value="Save" class="button green" name="action" />

            </form>
        </div>


    </div>




<?php $this->load->view('layout/flatui/footer');

// Add or Subtract seven days.
function change_week($date, $add_sub) {
    return date('Y-m-d', strtotime($date) + ($add_sub * (60 * 60 * 24 * 7)) + 7200); // The '+ 7200' because daylight saving had created an issue on 11th November 2011. We may have to remove it some time.
}
