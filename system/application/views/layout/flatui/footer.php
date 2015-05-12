
<script type="text/javascript">$('body').fadeIn(1000);</script>

<script type="text/javascript" src="<?php echo base_url() ?>js/application.js"></script>
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