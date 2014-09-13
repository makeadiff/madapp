				<option selected="selected" >- choose action -</option> 
				<?php 
				$kids=$kids->result_array();
                foreach($kids as $row)
                {
                ?>
                
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
                <?php } ?>
