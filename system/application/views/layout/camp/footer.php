      
      </div>
<div class="sidebar" id="sidebar">
<!-- MODULE ENDS -->
</div>
     </div>
    </div>
    <!-- BODY ENDS -->
    <div class="footer">
     <div class="line"></div>
    </div>
</div>
<!--<a id="fdbk_tab" class="fdbk_tab_bottom" style="background-color:#222" href="https://getsatisfaction.com/mad/topics/new">FEEDBACK</a>-->

<?php 
$url = site_url(); 
if(strpos($url, 'localhost') === false) { // Don't show in local mode.
?>
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-5816278-6']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();
</script>
<?php } ?>
</body>
</html>
