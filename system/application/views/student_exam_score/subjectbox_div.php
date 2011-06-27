            <?php for($i=1;$i<=$sub_no;$i++) { ?>
            	<li>
                <label for="txtName">Subject <?=$i?> : </label>
                <input type="text" id="choice-text-<?=$i?>" name="choice-text-<?=$i?>" <?php if($i==$sub_no) { ?>  <?php } ?>/>
              	</li>
            <?php } ?>
