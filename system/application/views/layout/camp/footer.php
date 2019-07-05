
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
if(((strpos($url, 'localhost') === false) and (strpos($url, '192.168') === false))) { // Don't show in local mode.
?>
<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-5816278-6', 'auto');
  ga('send', 'pageview');
</script>
<?php } ?>
</body>
</html>
