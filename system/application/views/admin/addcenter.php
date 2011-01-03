
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script type="text/javascript" src="<?php echo base_url()?>js/thickbox.js"></script>


<form id="formEditor" class="mainForm clear" action="<?=site_url('admin/addChapter')?>" method="post" style="width:500px;" onsubmit="javascript:parent.tb_remove();">
<fieldset class="clear" style="margin-top:70px;width:500px;margin-left:-30px;">
<div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select Subject:</label> 
            <select id="selBulkActions" name="subject" onchange="retrieve_subject(this.value);"> 
            <option selected="selected" >- choose action -</option> 
<?php 
$retriveSubject = $retriveSubject->result_array();

foreach($retriveSubject as $row)
{
?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['subject_name']; ?></option> 
                 <?php } ?>
            </select>
            </div>
           
            <div class="field clear" id="chapterUpdate" style="width:500px;" >
                
                
                
                
                       
                        
            </div>
            </fieldset>
            </form>