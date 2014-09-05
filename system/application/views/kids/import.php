<?php 
$this->load->view('layout/header', array('title' => "Import Kids..."));
?>
<div id="head" class="clear"><h1>Import Kids...</h1></div>


<form action="<?php echo site_url('kids/import_field_select') ?>" class="form-area" method="post" enctype="multipart/form-data">
<label for="csv_file">Import Kids...</label><input type="file" name="csv_file" /><br />

<label for="center_id">Center:</label> 
<select id="center_id" name="center_id">
<?php foreach($centers as $row) { ?>
<option value="<?php echo $row->id; ?>"><?php echo $row->name; ?></option>
<?php } ?>
</select><br />

<label>&nbsp;</label><input type="submit" value="Import" />
</form>


<?php $this->load->view('layout/footer');