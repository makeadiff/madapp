<script type="text/javascript">
function get_projectlist(page_no)
{
	
	$('#loading').show();
		$.ajax({
		type: "POST",
		url: "<?= site_url('project/getprojectlist')?>",
		data: "pageno="+page_no,
		success: function(msg){
		$('#loading').hide();
		$('#updateDiv').html(msg);
		}
	});
}

function deleteEntry(entryId,page_no)
{
	var bool = confirm("Are you sure you wish to delete this Project?")
	if(bool)
	{
		$.ajax({
		type : "POST",
		url  : "<?php echo site_url('project/ajax_deleteproject') ?>",
		data : 'entry_id='+entryId,
		
		success : function(data)
		{		
			get_projectlist(page_no);
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
    
<script type="text/javascript">
get_projectlist('0');
</script>

</div>

</div>

