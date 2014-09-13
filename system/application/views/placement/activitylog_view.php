
<script type="text/javascript">
    function getcount(id) {
        var gid=id;
        //alert(gid)
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('placement/childimpact_kids') ?>/"+gid,
            success: function(msg){
                $('#count'+gid).html(msg);
                //$('#kids_list').html("");
            }
        });
    }
    
    
    function getdayscount(id) {
        var gid=id;
        //alert(gid)
        $.ajax({
            type: "POST",
            url: "<?php echo site_url('placement/event_days_count') ?>/"+gid,
            success: function(msg){
                //alert("hello");
                $('#dayscount'+gid).html(msg);
                //$('#kids_list').html("");
            }
        });
    }

</script>

<div id="head" class="clear">
    <h1><?php echo $title; ?></h1>
    <!-- start page actions-->
    <a href="<?php echo site_url('placement/report') ?>">< Report Dashboard</a>
    <div id="actions"> 
    <!--			<a href="<?php //echo site_url('placement/childimpact')  ?>" class="thickbox button primary green popup" name="Child Impact">Child Impact</a>-->
    </div>
    <!-- end page actions-->
</div>
<div id="wrapper">
    <div id="container">
        <table id="tableItems" class="clear data-table" cellpadding="0" cellspacing="0">
            <thead>
                <tr>
                    <th class="colCheck1">City Name</th>
                    <th class="colActions1">Jan</th>
                    <th class="colActions2">Feb</th>
                    <th class="colActions3">Mar</th>
                    <th class="colActions4">Apr</th>
                    <th class="colActions5">May</th>
                    <th class="colActions6">Jun</th>
                    <th class="colActions7">Jul</th>
                    <th class="colActions8">Aug</th>
                    <th class="colActions9">Sep</th>
                    <th class="colActions10">Oct</th>
                    <th class="colActions11">Nov</th>
                    <th class="colActions12">Dec</th>
                </tr>
            </thead>
           