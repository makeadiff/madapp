<?php $this->load->view('layout/css',array('thickbox'=>true)); ?>

<form id="formEditor" class="mainForm clear" action="<?=site_url('center/addCenter')?>" method="post" style="width:500px;" onsubmit="return validate();"  >
<fieldset class="clear" style="margin-top:70px;width:500px;margin-left:-30px;">
	<?php 
	$this_city_id = $this->session->userdata('city_id');
	if($this->user_auth->get_permission('change_city')) { ?>
			<div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select city:</label> 
            <select id="city" name="city" > 
            <option value="0" >- choose action -</option> 
				<?php 
                $details = $details->result_array();
                foreach($details as $row) {
                ?>
                <option value="<?php echo $row['id']; ?>" <?php if($this_city_id == $row['id']) print ' selected="selected"'; ?>><?php echo $row['name']; ?></option> 
                <?php } ?>
            </select>
            </div>
    <?php } else { ?>
    	<input type="hidden" name="city" value="<?php echo $this_city_id; ?>" />
    <?php } ?>
    
            <div class="field clear" style="width:500px;">
            <label for="selBulkActions">Select Head:</label> 
            <select id="user_id" name="user_id"> 
            <option selected="selected" value="0" >- Choose -</option> 
				<?php 
                $user_name = $user_name->result_array();
                foreach($user_name as $row)
                {
                ?>
                <option value="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option> 
                <?php } ?>
            </select>
            </div>
           
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">Center : </label>
                        <input id="center" name="center"  type="text" /> 
                      
            </div>
            
            <div class="field clear" style="width:550px;"> 
     				<input style="margin-left:250px;" id="btnSubmit" class="button primary" type="submit" value="Submit" />
            </div>
            </fieldset>
            </form>
            
            
            <script>
     function validate()
     {
        if(document.getElementById("city").value == '0')
          {		
              alert("Select a City.");
              return false;
          }
       if(document.getElementById("center").value == '')
          {
              alert("Center Missing.");
              return false;
          }
	}
		</script>