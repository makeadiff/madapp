<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/g.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/l.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/bk.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/r.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url()?>css/validation.css" />
<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>

<form id="formEditor" class="mainForm clear" action="<?=site_url('books/addbook')?>" method="post" style="width:500px;" onsubmit="return validate();"  >
<fieldset class="clear" style="margin-top:70px;width:500px;margin-left:-30px;">
	
            
            <div class="field clear" style="width:500px;"> 
                        <label for="txtName">BookName : </label>
                        <input id="bookname" name="bookname"  type="text" /> 
                      
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