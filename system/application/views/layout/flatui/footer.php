
<script type="text/javascript">$('body').fadeIn(1000);</script>

<script type="text/javascript">
var base_url = "<?php echo base_url(); ?>";
</script>
<script type="text/javascript" src="<?php echo base_url() ?>js/application.js"></script>
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
