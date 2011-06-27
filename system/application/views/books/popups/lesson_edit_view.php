<?php $this->load->view('layout/thickbox_header'); ?>
<div style="float:left;"><h1>Edit Lessons</h1></div>
<?php $book_name=$book_name->result_array(); ?>
<script>
function validate()
{
if(document.getElementById("book").value == '')
          {		
              alert("Bookname Missing.");
			  document.getElementById('book').focus();
              return false;
          }
if(document.getElementById("lessonname").value == '')
          {		
              alert("Lesson Name Missing.");
			  document.getElementById('lessonname').focus();
              return false;
          }
}
</script>
<div style="float:left; margin-top:20px;">
<form id="formEditor" class="mainForm clear" action="<?=site_url('books/update_lesson')?>" method="post"  onsubmit="return validate();">
<fieldset class="clear">
<ul class="form city-form">

			<?php foreach($book_name as $row){
			$name=$row['name'];
			$root_id=$row['id'];
			$book_id=$row['book_id'];
			}
			 ?>
           <li>
                        <label for="txtName">Select Book : </label>
                        <select id="book" name="book" > 
            			<option value="0" >- choose action -</option> 
						<?php 
                		$details = $details->result_array();
                		foreach($details as $row) {
                		?>
                        <?php if($row['id']==$book_id){?>
                		<option value="<?php echo $row['id']; ?>" selected="selelcted"><?php echo $row['name']; ?></option> 
                        <?php }else{?>
                        <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
               		 	<?php } }?>
            			</select>
                      </li>
           
					<li>
                       <label for="txtName">BookName : </label>
                       <input id="lessonname" style="height:20px;" name="lessonname"  type="text" value="<?=$name?>" /> 
            		</li>
            </ul>
            <ul>
            <li>
            		<input type="hidden" name="root_id" id="root_id" value="<?=$root_id?>" />
     				<input   id="btnSubmit" class="button green" type="submit" value="Submit" />
                    <a href="<?=site_url('books/manage_chapters')?>" class="cancel-button">Cancel</a>

            </li>
            </ul>
            </fieldset>
            </form>
            
         </div>