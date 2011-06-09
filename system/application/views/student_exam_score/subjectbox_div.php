            <?php for($i=1;$i<=$sub_no;$i++) { ?>
            	<div class="fields">
                        <label for="txtName">Subject <?=$i?> : </label>
                        <input type="text" id="choice-text-<?=$i?>" name="choice-text-<?=$i?>" style="margin-left:-5px;" 
                        class="inputClass" <?php if($i==$sub_no) { ?> onkeyup="javascript:get_centers();" <?php } ?>/>
                        <p class="error clear"></p>
                </div>
            <?php } ?>
