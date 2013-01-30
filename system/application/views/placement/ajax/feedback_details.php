<li>
    Kids Feedback:
</li>
<?php  
   ?>
    <?php //foreach($feedback->result_array() as $row) {
         
//if($row['id']) { ?>
<!--    <li>
    <label for="attendence_<?php //echo $row['id'] ?>"><?php //echo $row['name'] ?></label>
    <input type="checkbox" id="attendence_<?php //echo $row['id'] ?>" name="attendance[]"  value="<?php //echo $row['id'] ?>"/>
    </li>-->
    <?php // } } ?>
<?php 
foreach($feedback_contents->result_array() as $row) {
$feedback_score=$row['feedback_score'];
$feedback_career=$row['feedback_career'];
$feedback_repeat=$row['feedback_repeat'];
$feedback_volunteer_count=$row['feedback_volunteer_count'];
$feedback_volunteer_repeat_strongly_agree=$row['feedback_volunteer_repeat_strongly_agree'];
$feedback_volunteer_repeat_agree=$row['feedback_volunteer_repeat_agree'];
$feedback_volunteer_repeat_neutral=$row['feedback_volunteer_repeat_neutral'];
$feedback_volunteer_repeat_disagree=$row['feedback_volunteer_repeat_disagree'];
$feedback_volunteer_repeat_strongly_disagree=$row['feedback_volunteer_repeat_strongly_disagree'];
$feedback_volunteer_engaging_strongly_agree=$row['feedback_volunteer_engaging_strongly_agree'];
$feedback_volunteer_engaging_agree=$row['feedback_volunteer_engaging_agree'];
$feedback_volunteer_engaging_neutral=$row['feedback_volunteer_engaging_neutral'];
$feedback_volunteer_engaging_disagree=$row['feedback_volunteer_engaging_disagree'];
$feedback_volunteer_engaging_strongly_disagree=$row['feedback_volunteer_engaging_strongly_disagree'];
$feedback_volunteer_suggestion=$row['feedback_volunteer_suggestion'];

$feedback_partner_engaging_strongly_agree=$row['feedback_partner_engaging_strongly_agree'];
$feedback_partner_engaging_agree=$row['feedback_partner_engaging_agree'];
$feedback_partner_engaging_neutral=$row['feedback_partner_engaging_neutral'];
$feedback_partner_engaging_disagree=$row['feedback_partner_engaging_disagree'];
$feedback_partner_engaging_strongly_disagree=$row['feedback_partner_engaging_strongly_disagree'];

$feedback_partner_rating_excelent=$row['feedback_partner_rating_excelent'];
$feedback_partner_rating_very_good=$row['feedback_partner_rating_very_good'];
$feedback_partner_rating_average=$row['feedback_partner_rating_average'];
$feedback_partner_rating_poor=$row['feedback_partner_rating_poor'];
$feedback_partner_rating_very_poor=$row['feedback_partner_rating_very_poor'];
}
 ?>
<li>
<label for="feedback_score">Activity Specific Score: </label>
<input id="feedback_score" name="feedback_score" type="text" value="<?php echo $feedback_score; ?>"/>
    </li>

    <li>
<label for="feedback_score">Pursue career no: </label>
<input id="feedback_career" name="feedback_career" type="text" value="<?php echo $feedback_career; ?>"/>
    </li>
    
    <li>
<label for="feedback_score">Repeat activity no: </label>
<input id="feedback_repeat" name="feedback_repeat" type="text" value="<?php echo $feedback_repeat; ?>"/>
    </li>
    <li>
    MAD Team:
</li>
    <li>
<label for="txtName">No: of Volunteers : </label>
<input id="feedback_volunteer_count" name="feedback_volunteer_count" type="text" value="<?php echo $feedback_volunteer_count; ?>"/>
    </li>
    
    <li>
<label for="txtName">No : of volunteers repeat strongly agree : </label>
<input id="feedback_volunteer_repeat_strongly_agree" name="feedback_volunteer_repeat_strongly_agree" type="text"  value="<?php echo $feedback_volunteer_repeat_strongly_agree;?>"/>
    </li>
   <li>
<label for="txtName">No : of volunteers repeat agree : </label>
<input id="feedback_volunteer_repeat_agree" name="feedback_volunteer_repeat_agree" type="text"  value="<?php echo $feedback_volunteer_repeat_agree;?>"/>
    </li>
    <li>
<label for="txtName">No : of volunteers repeat neutral : </label>
<input id="feedback_volunteer_repeat_strongly_neutral" name="feedback_volunteer_repeat_strongly_neutral" type="text" value="<?php echo $feedback_volunteer_repeat_neutral; ?>"/>
    </li>
    <li>
<label for="txtName">No : of volunteers repeat disagree : </label>
<input id="feedback_volunteer_repeat_disagree" name="feedback_volunteer_repeat_disagree" type="text" value="<?php echo $feedback_volunteer_repeat_disagree;?>"/>
    </li>
    <li>
<label for="txtName">No : of volunteers repeat strongly disagree : </label>
<input id="feedback_volunteer_repeat_strongly_disagree" name="feedback_volunteer_repeat_strongly_disagree" type="text"  value="<?php echo $feedback_volunteer_repeat_strongly_disagree; ?>"/>
    </li>
    
    <li>
<label for="txtName">No : of volunteers engaging strongly agree : </label>
<input id="feedback_volunteer_engaging_strongly_agree" name="feedback_volunteer_engaging_strongly_agree" type="text" value="<?php echo $feedback_volunteer_engaging_strongly_agree; ?>"/>
    </li>
   <li>
<label for="txtName">No : of volunteers engaging agree : </label>
<input id="feedback_volunteer_engaging_agree" name="feedback_volunteer_engaging_agree" type="text" value="<?php echo $feedback_volunteer_engaging_agree; ?>"/>
    </li>
    <li>
<label for="txtName">No : of volunteers engaging neutral : </label>
<input id="feedback_volunteer_engaging_strongly_neutral" name="feedback_volunteer_engaging_strongly_neutral" type="text"  value="<?php echo $feedback_volunteer_engaging_neutral; ?>"/>
    </li>
    <li>
<label for="txtName">No : of volunteers engaging disagree : </label>
<input id="feedback_volunteer_engaging_disagree" name="feedback_volunteer_engaging_disagree" type="text" value="<?php echo $feedback_volunteer_engaging_disagree; ?>"/>
    </li>
    <li>
<label for="txtName">No : of volunteers engaging strongly disagree : </label>
<input id="feedback_volunteer_engaging_strongly_disagree" name="feedback_volunteer_engaging_strongly_disagree" type="text" value="<?php echo $feedback_volunteer_engaging_strongly_disagree; ?>"/>
    </li>
    
    <li>
<label for="txtName">Volunteer suggestion : </label>
<input id="feedback_volunteer_suggestion" name="feedback_volunteer_suggestion" type="text" value="<?php echo $feedback_volunteer_suggestion;?>"/>
    </li>
    
    <li>
    Partner Feedback:
</li>
   
    <li>
<label for="txtName">No : of partners engaging strongly agree : </label>
<input id="feedback_partner_engaging_strongly_agree" name="feedback_partner_engaging_strongly_agree" type="text" value="<?php echo $feedback_partner_engaging_strongly_agree; ?>"/>
    </li>
   <li>
<label for="txtName">No : of partners engaging agree : </label>
<input id="feedback_partner_engaging_agree" name="feedback_partner_engaging_agree" type="text"  value="<?php echo $feedback_partner_engaging_agree; ?>"/>
    </li>
    <li>
<label for="txtName">No : of partners engaging neutral : </label>
<input id="feedback_partner_engaging_neutral" name="feedback_partner_engaging_neutral" type="text" value="<?php echo $feedback_partner_engaging_neutral; ?>"/>
    </li>
    <li>
<label for="txtName">No : of partners engaging disagree : </label>
<input id="feedback_partner_engaging_disagree" name="feedback_partner_engaging_disagree" type="text" value="<?php echo $feedback_partner_engaging_disagree; ?>"/>
    </li>
    <li>
<label for="txtName">No : of partners engaging strongly disagree : </label>
<input id="feedback_partner_engaging_strongly_disagree" name="feedback_partner_engaging_strongly_disagree" type="text" value="<?php echo $feedback_partner_engaging_strongly_disagree; ?>" />
    </li>
    
    
    <li>
<label for="txtName">No : of partners rated the overall activity as excellent : </label>
<input id="feedback_partner_rating_excelent" name="feedback_partner_rating_excelent" type="text" value="<?php echo $feedback_partner_rating_excelent; ?>"/>
    </li>
     <li>
<label for="txtName">No : of partners rated the overall activity as very good : </label>
<input id="feedback_partner_rating_very_good" name="feedback_partner_rating_very_good" type="text" value="<?php echo $feedback_partner_rating_very_good; ?>"/>
     </li>
      <li>
<label for="txtName">No : of partners rated the overall activity as average : </label>
<input id="feedback_partner_rating_average" name="feedback_partner_rating_average" type="text" value="<?php echo $feedback_partner_rating_average; ?>"/>
     </li>
      <li>
<label for="txtName">No : of partners rated the overall activity as poor : </label>
<input id="feedback_partner_rating_poor" name="feedback_partner_rating_poor" type="text" value="<?php echo $feedback_partner_rating_poor; ?>"/>
     </li>
      <li>
<label for="txtName">No : of partners rated the overall activity as very poor : </label>
<input id="feedback_partner_rating_very_poor" name="feedback_partner_rating_very_poor" type="text" value="<?php echo $feedback_partner_rating_very_poor; ?>"/>
     </li>
    