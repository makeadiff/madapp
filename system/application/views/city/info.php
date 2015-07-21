<?php $this->load->view('layout/flatui/header', array('title'=>'City Information')); ?>

<div class="container" id="content">
<h2 class="title">City Information</h2>

<h3>Center Count: <?php echo $center_count ?></h3>

<h3>Student Information</h3>

<dl>
<dt>Total Students</dt>
<dd><?php echo $kids_count ?></dd>
</dl>

<h3>Volunteer Information</h3>

<dl>
<dt>Total Volunteers</dt><dd><?php echo $volunteer_count ?></dd>
<dt>Teacher Count</dt><dd><?php echo $teacher_count ?></dd>
<dt>Assigned Teachers</dt><dd><?php echo $mapped_teachers_count ?></dd>
</dl>
</div>

<?php $this->load->view('layout/flatui/footer'); ?>
