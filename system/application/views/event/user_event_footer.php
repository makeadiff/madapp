</li>
</ul>

<ul>
<li>
<input class="button green" type="submit" value="+ Add Users Event" />
<a href="<?php echo site_url('event/index')?>" class="sec-action">Cancel</a>
</li>
</ul>
</form>
<script>
function validate()
{
if(document.getElementById("event").value == '-1')
	{		
		alert("Select a Event");
		return false;
	}
if(document.getElementById("users").checked == '')
	{
		alert("Select one User");
		return false;
	}
}
</script>

