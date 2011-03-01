<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url()?>css/thickbox.css">

<script type="text/javascript">
function get_examlist(page_no,search_query)
{
	
	$('#loading').show();
	$.ajax({
		type: "POST",
		url: "<?= site_url('exam/get_examdetails')?>",
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
		url  : "<?= site_url('exam/ajax_deleteexam') ?>",
		data : 'entry_id='+entryId,
		
		success : function(data)
		{		
			get_examlist(page_no);
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
    get_examlist('0','');
</script>

</div>

</div>

