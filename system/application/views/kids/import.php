<?php 
$this->load->view('layout/header', array('title' => "Import Kids..."));
?>
<div id="head" class="clear"><h1>Import Kids...</h1></div>


<form action="<?php echo site_url('kids/import_field_select') ?>" method="post" enctype="multipart/form-data">
<label for="csv_file">Import Kids...</label><input type="file" name="csv_file" />
<input type="submit" value="Import" />
</form>


<?php $this->load->view('layout/footer');