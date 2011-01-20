<script type="text/javascript" src="<?php echo base_url()?>js/jquery.min.js"></script>
<script>
function update_userlist()
{
city=$('#city').val();
var agents = "";
			$('#group :checked').each(function(i, selected)
				{ 
 				agents+=($(selected).val()=="")?$(selected).val():$(selected).val()+",";
				});
name=$('#name').val();

		$.ajax({
            type: "POST",
            url: "<?= site_url('user/user_search')?>",
            data: "city="+city+"&group="+agents+"&name="+name,
            success: function(msg){
           		//$('#loading').hide();
            	$('#search').html(msg);
				$('#error').html(msg);
            }
            });
}
</script>


<div id="content" class="clear">

<!-- Main Begins -->
	<div id="main" class="clear">
    	<div id="head" class="clear">
        	<h1><?php echo $title; ?></h1>

                       <!-- end page actions-->

           </div>
	<table width="700" border="0" cellspacing="0" cellpadding="0" style="margin-bottom:25px;">
  	<tr>
    
            
    <td><div class="field clear">
            <label for="date" style="margin-left:20px;">Select City</label>
            <select name="city" id="city">
            <?php $city=$city->result_array();
			foreach($city as $row){ echo "fi=".$row['name'];
			?>
            <option id="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
            </select>
            <p class="error clear"></p> 
            </div>
    </td>
            
    <td><div  class="field clear" style="margin-left:20px; margin-bottom:10px;">
        	<label for="date">Group</label>
            
            <select name="group" id="group" style="width:150px; height:100px;" multiple>
            <?php $group=$group->result_array();
					foreach($group as $row)
					{ ?>
            <option id="<?php echo $row['id']; ?>"><?php echo $row['name']; ?></option>
            <?php } ?>
            </select>
            <p class="error clear"></p>
            </div>
    </td>  
     <td><div  class="field clear" style="margin-left:20px;">
        	<label for="date">Name</label>
            <input name="name" id="name"  type="text">
            <p class="error clear"></p>
            </div>
    </td>
    <td><div  class="field clear" style="margin-left:20px;">
    <input type="submit" value="find"  onclick="javascript:update_userlist('0');"/>
    </div>
    </td>                                     
  	</tr>
</table>
<div id="update_sales">
<table id="tableItems" class="clear" cellpadding="0" cellspacing="0">
<thead>
	<tr>
	<th class="colCheck1">Id</th>
	<th class="colName left sortable">Name</th>
    <th class="colStatus sortable">Email</th>
    <th class="colStatus">Mobile No</th>
    <th class="colPosition">Position Held</th>
    <th class="colPosition">City</th>
    <th class="colPosition">Center</th>
    <th class="colPosition">User Type</th>
</tr>
</thead>
<tbody>
<div id="search">
<?php 
$norecord_flag = 1;
$shadeFlag = 0;
$shadeClass = '';
$statusIco = '';
$statusText = '';
$i=0;
$content = $details->result_array();
foreach($content as $row)
{	$i++;
$norecord_flag = 0;
	if($shadeFlag == 0)
	  {
  		$shadeClass = 'even';
		$shadeFlag = 1;
  	  }
	else if($shadeFlag == 1)
	  {
  		$shadeClass = 'odd';		
			$shadeFlag = 0;
  	  }
?> 
<tr class="<?php echo $shadeClass; ?>">
    <td class="colCheck1"><a href="#"><?php echo $row['id']; ?></a></td>
    <td class="colName left"> <a href="#"><?php echo $row['name']; ?></a></td>
    <td class="colCount"><a href=""> <?php echo $row['email']; ?></a></td> 
     <td class="colStatus" style="text-align:left"><?php echo $row['phone'];  ?></td>
     <td class="colPosition" style="text-align:left"><?php echo $row['title'];  ?></td>
    <td class="colPosition"><?php echo $row['city_name'];  ?></td>
    <td class="colPosition"><?php echo $row['center_name'];  ?></td>
    <td class="colPosition"><?php echo $row['user_type'];  ?></td>
</tr>
<?php  } ?> 
</div>
</tbody>
</table>
</div>
</div>
</div>
