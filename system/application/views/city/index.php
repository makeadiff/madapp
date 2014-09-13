<?php $this->load->view('layout/flatui/header', array('title'=>'Cities', 'message'=>$message)); ?>


<div id="head" class="container-fluid"><h2 class="title">Cities</h2>

<?php if($this->user_auth->get_permission('city_create')) { ?>
<div id="actions"> 
<a href="<?= site_url('city/create')?>" class="popup button green primary" style="margin-bottom:10px;" id="example" name="Add City">Add City</a>
</div>
<?php } ?>
</div>
<div class="row">
    <div class="form-group col-md-4 col-md-offset-4 col-sm-12">
        <table id="main" class="data-table tablesorter info-box-table table table-condensed table-bordered table-custom ">
        <thead><tr><th>Name</th><th colspan="2">Action</th></tr></thead>
        <?php foreach($all_cities as $result) { ?>
        <tr><td><?php echo $result->name;
            if($result->problem_count) print "<span class='warning icon'>!</span>";
            ?><div class="center-info info-box"><ul><li><?php
                print implode('</li><li>', $result->information);
            ?></li></ul></div></td>
        <td><a href="<?php echo site_url('city/edit/'.$result->id); ?>" class=" popup  primary edit with-icon">Edit</a></td>
        </tr>
        <?php } ?>
        </table>
    </div>
</div>

<?php $this->load->view('layout/flatui/footer'); ?>