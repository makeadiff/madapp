<?php $this->load->view('layout/thickbox_header'); ?>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() ?>css/calender.css" />
<script src="<?php echo base_url() ?>js/cal.js"></script>

<?php
$edt = date('Y');
$sdt = date('Y') - 20;
?>
<script>
    jQuery(document).ready(function () {
        $('input#date-pick').simpleDatepicker({ startdate: <?php echo $sdt; ?>, enddate: <?php echo $edt; ?>, chosendate:new Date('2000-01-01')});
    });
</script>
<script type="text/javascript">
    $(function () {
        $("#corporate").change(getcorporate);
    });

    function getcorporate() {
        var id = $("#id").val();
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('placement/get_corporate_update') ?>/"+this.value+"/"+id,
            success: function(msg){
                $('#corp').html(msg);
                //$('#kids_list').html("");
            }
        });
    }

</script>
<h2>Edit Event</h2>
<script>
    function validate()
    {
        if(document.getElementById("eventname").value == '')
        {		
            alert("Event Name Missing.");
            document.getElementById('eventname').focus();
            return false;
        }
          
        if(document.getElementById("date-pick").value == '')
        {		
            alert("Date Missing.");
            document.getElementById('date-pick').focus();
            return false;
        }
        
    }
</script>
<?php
$details = $details->result_array();
foreach ($details as $row) {
    $root_id = $row['id'];
    $name = $row['name'];
    $user_id = $row['user_id'];
    $city_id = $row['city'];
    $started_on = $row['started_on'];
    $activity_id = $row['placement_activity_id'];
    $corporate_partner = $row['corporate_partner'];
    $corporate_volunteer_count = $row['corporate_volunteer_count'];
    $corporate_poc = $row['corporate_poc'];
    $cr_intern_user_id = $row['cr_intern_user_id'];
}
?>

<div id="message"></div>
<div style="float:left; margin-top:20px;">
    <form id="formEditor" class="mainForm clear" action="<?php echo site_url('placement/updateevent_name/' . $root_id) ?>" method="post" onsubmit="return validate();" style="width:355px;">
        <fieldset class="clear">
            <ul class="form city-form">
                <input type="hidden" name="id" id="id" value="<?php echo $root_id; ?>"/>
                <li>
                    <label for="txtName">Event Name : </label>
                    <input id="eventname" name="eventname" type="text" value="<?php echo $name; ?>"/>
                </li>

                <li>
                    <label for="owner">Activity owner : </label>
                    <select name="owner" id="owner">
                        <option value="">Select Owner</option>
                        <?php foreach ($user->result_array() as $row): ?>
                            <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $user_id) { ?>selected="selected" <?php } ?>><?php echo $row['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>

                <li>
                    <label for="city">City : </label>
                    <select name="city" id="city">
                        <option value="">Select City name</option>
                        <?php foreach ($city->result_array() as $row): ?>
                            <option value="<?php echo $row['id']; ?>" <?php if ($city_id == $row['id']) { ?>selected="selected" <?php } ?>><?php echo $row['name']; ?></option>
                        <?php endforeach; ?>
                    </select>
                </li>

                <li>
                    <label for="txtName">Started On: </label>
                    <input name="date-pick" class="date-pick" id="date-pick" type="text" value="<?php echo $started_on; ?>">
                </li>

              

                <li>

                    <label for="activity_id">Placement Activity: </label>
                    <select name="activity_id" id="activity_id">
                        <option value="">Select Center</option>
                        <?php foreach ($activity->result_array() as $row) { ?>
                            <option value="<?php echo $row['id']; ?>" <?php if ($row['id'] == $activity_id) { ?> selected="selected" <?php } ?>><?php echo $row['name']; ?></option>
                        <?php } ?>
                    </select>
                </li>

                <li>
                    <label for="corporate">Corporate Partner: </label>
                    <select name="corporate" id="corporate">
                        <option value="0">Select Corporate</option>
                        <option value="1" <?php if ($corporate_partner != '') { ?> selected="selected" <?php } ?>>Yes</option>
                        <option value="2">No</option>

                    </select>
                </li>

                <div id="corp">
                    <?php
                    if ($corporate_partner != '') {
                        ?>
                        <li>
                            <label for="txtName">Corporate Name : </label>
                            <input id="corpname" name="corpname" type="text" value="<?php echo $corporate_partner; ?>"/>
                        </li>
                        <?php
                    }
                    ?>

                    <?php
                    if ($corporate_volunteer_count != '') {
                        ?>
                        <li>
                            <label for="txtName">No: of Volunteers  : </label>
                            <input id="novol" name="novol" type="text" value="<?php echo $corporate_volunteer_count; ?>"/>
                        </li>
    <?php
}
?>

                    <?php
                    if ($corporate_poc != '') {
                        ?>
                        <li>
                            <label for="txtName">Corporate POC : </label>
                            <input id="corpoc" name="corpoc" type="text" value="<?php echo $corporate_poc; ?>"/>
                        </li>
    <?php
}
?>

                    <?php
                    if ($cr_intern_user_id != 0) {
                        ?>
                        <li>
                            <label for="txtName">CR Intern : </label>
                            <input id="crintrn" name="crintrn" type="text" value="<?php echo $cr_intern_user_id; ?>"/>
                        </li>
    <?php
}
?>
                </div>
            </ul>
            <ul>
                <li><input id="btnSubmit"  class="button green" type="submit" value="Submit"  />
                    <a href="<?= site_url('placement/manageevents') ?>" class="cancel-button">Cancel</a>
                </li>
            </ul>
        </fieldset>
    </form>		
</div>

