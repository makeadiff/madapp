
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
  window.intercomSettings = {
    app_id: "xnngu157",
    <?php 
      if(!empty($_SESSION['name'])) echo " name: '$_SESSION[name]',\n";
      if(!empty($_SESSION['email'])) echo " email: '$_SESSION[email]'\n";
    ?>
  };
  </script>
<script>(function(){var w=window;var ic=w.Intercom;if(typeof ic==="function"){ic('reattach_activator');ic('update',intercomSettings);}else{var d=document;var i=function(){i.c(arguments)};i.q=[];i.c=function(args){i.q.push(args)};w.Intercom=i;function l(){var s=d.createElement('script');s.type='text/javascript';s.async=true;s.src='https://widget.intercom.io/widget/xnngu157';var x=d.getElementsByTagName('script')[0];x.parentNode.insertBefore(s,x);}if(w.attachEvent){w.attachEvent('onload',l);}else{w.addEventListener('load',l,false);}}})()</script>

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
