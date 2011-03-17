<script type="text/javascript">
function get_permissionlist(page_no,search_query)
{
	
	$('#loading').show();
		$.ajax({
		type: "POST",
		url: "<?= site_url('permission/get_permissionlist')?>",
		data: "pageno="+page_no+"&q="+search_query,
		success: function(msg){
		$('#loading').hide();
		$('#updateDiv').html(msg);
		}
		});
}

function deleteEntry(entryId,page_no)
{
	var bool = confirm("confirm delete!")
	if(bool)
	{
		$.ajax({
		type : "POST",
		url  : "<?= site_url('permission/ajax_deletepermission') ?>",
		data : 'entry_id='+entryId,
		
		success : function(data)
		{		
			get_permissionlist(page_no);
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
    get_permissionlist('0','');
</script>

</div>

</div>

