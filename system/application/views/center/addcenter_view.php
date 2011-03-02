<?php include_once('session_timeout.php'); ?>
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/thickbox.css">

<script>

	function get_centerlist(page_no,search_query)
	{
		
		$('#loading').show();
            $.ajax({
            type: "POST",
            url: "<?= site_url('center/getcenterlist')?>",
            data: "pageno="+page_no+"&q="+search_query,
            success: function(msg){
            $('#loading').hide();
            $('#updateDiv').html(msg);
            }
            });
	}
	
	function deleteEntry(entryId,page_no)
	{
		var bool = confirm("Are you sure you want to delete this?")
		if(bool)
		{
			$.ajax({
			type : "POST",
			url  : "<?= site_url('center/ajax_deletecenter') ?>",
			data : 'entry_id='+entryId,
			success : function(data) {		
			 	get_centerlist(page_no);
			},
			error: function(data) {
				var error = data.responseText.match(/<p>(.+?)<\/p>/);
				if(error) error = " - Error: " + error[1];
				alert("Couldn't delete center" + error);
			}
			
			});
		}
	}	
	
	
</script>

<div style="height:20px;padding-top: 5px;">
<div id="loading" name="loading" style="display: none;">
    <img src="<?php echo base_url()?>images/ico/loading.gif" height="25" width="25" style="border: none;margin-left: 300px;" /> loading...
</div>
</div>
<div id="updateDiv" >
    
<script>
    get_centerlist('0','');
</script>

</div>

</div>

