<?php $this->load->view('layout/header', array('title'=>'Cities', 'message'=>$message)); ?>
<h1>Cities</h1>

<ul>
<?php foreach($all_cities as $result) { ?>
<li><a href="<?php echo base_url() ?>index.php/city/view/<?php echo $result->id ?>"><?php echo $result->name ?></a></li>
<?php } ?>
</table>

<?php $this->load->view('layout/footer'); ?>