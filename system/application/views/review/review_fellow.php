<?php 
$this->load->view('layout/header', array('title' => "Review Parameters for " . $user->name));
?>
<script type="text/javascript">
function comment(id) {
	jQuery.ajax({
				"url": "<?php echo site_url('review/ajax_get_comment'); ?>/" + id,
				"success": function(data) {
					jQuery("#comment-area").show();
					jQuery("#comment").val(data);
					jQuery("#parameter_id").val(id);
				}
			});
	
}

function cancelComment() {
	jQuery("#comment-area").hide();
}

function saveComment() {
	var id = jQuery("#parameter_id").val();
	jQuery.ajax({
			"url": "<?php echo site_url('review/ajax_save_comment'); ?>/" + id,
			"data": {"comment": jQuery("#comment").val()},
			"type": "POST",
			"success": function(data) {
				jQuery("#comment-area").hide();
			}
		});
}

function inputData(id, name, value, ele) {
	var title = name.replace(/_/g," ");
	var input_value = prompt(title, value);
	if(input_value == undefined || input_value == null) return;
	input_value = Number(input_value);

	ele.innerHTML = input_value;
	
	jQuery.ajax({
			"url": "<?php echo site_url('review/ajax_save_value'); ?>/"+id+"/"+input_value,
			"success": function(data) {
				//alert(data);
			}
		});
}
</script>
<style type="text/css">
#comment-area {
	position:absolute;
	top:200px;
	left:40%;
	width:300px;
	background:#ccc;
	border:1px solid black;
	display:none;
	padding:3px;
}
.data-table a {
	color:#fff !important;
}
</style>
<div id="comment-area">
<form action="" method="post">
<textarea name="comment" id="comment" rows="5" cols="40"></textarea><br />
<input type="button" name="action" value="Cancel" onclick="cancelComment()" />
<input type="button" name="action" value="Save" onclick="saveComment()" style="float:right;" />
<input type="hidden" name="parameter_id" id="parameter_id" />
</form>
</div>

<div id="head" class="clear"><h1>Review Parameters for <?php echo $user->name; ?></h1></div>

<table class="data-table">
<tr><th>Parameter</th><th>Value</th><!-- <th>Data</th> --><th>Comments</th></tr>
<?php 
$flags = array('nothing', 'green','yellow','orange','red','black');
foreach ($reviews as $item) { ?>
<tr class="<?php echo $flags[$item->level]; ?>">
	<td class="parameter-name"><?php echo format($item->name); ?></td>
	<td class="parameter-value"><?php 
			if($item->input_type == 'manual' and $auth->get_permission('review_edit')) {
				echo "<a href='#' onclick='inputData({$item->id},\"{$item->name}\", \"{$item->value}\", this);' class='with-icon edit'>{$item->value}</a>";
			}
			else echo $item->value;
			
			if($item->input_type == 'percentage') echo '%';
	?></td>

<!-- 	<td class="parameter-data"><?php echo $item->data ?></td> -->
	<td class="parameter-comment"><?php if($auth->get_permission('review_comment')) { ?>
		<a href='#' onclick='comment(<?php echo $item->id ?>);' title='Add Comment' class='icon edit'>Comment</a>
		<?php echo $item->comment;
		} ?></td></tr>
<?php } ?>
</table>

<?php $this->load->view('layout/footer');