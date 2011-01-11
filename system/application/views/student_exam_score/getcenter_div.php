				<label for="selBulkActions">Select center:</label> 
                <select id="centers" name="centers"  onchange="javascript:get_kidslist(this.value);"   > 
                <option >- choose action -</option>
                <?php 
                $center = $center->result_array();
                foreach($center as $row)
                {
                ?>
                <option  value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
                <?php } ?>
				</select>