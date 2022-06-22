<?php if (strrpos($_SERVER["HTTP_HOST"], 'dev.') === 0) { ?>
    <!--script>window.jQuery || document.write('<script src="<?=$FUNC_SERVER; ?>/libs/jquery-1.6.2.js" type="text/javascript"><\/script>')</script>
    <script defer src="<?=$FUNC_SERVER; ?>/plugins.js"></script-->
    <!--script src="<?=$FUNC_SERVER; ?>/svelte/core.js" type="text/javascript"></script>
    <script src="<?=$FUNC_SERVER; ?>/svelte/event.js" type="text/javascript"></script>
    <script src="<?=$FUNC_SERVER; ?>/svelte/hijax.js" type="text/javascript"></script>
    <script src="<?=$FUNC_SERVER; ?>/svelte/hijax.form.js" type="text/javascript"></script>
    <script>SVELTE.hijax.form.init(/:/);</script-->
<?php } else { ?>
    <!--script src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>
    <script>window.jQuery || document.write('<script src="<?=$FUNC_SERVER; ?>/libs/jquery-1.6.2.min.js" type="text/javascript"><\/script>')</script-->
    <script defer src="<?=$FUNC_SERVER; ?>/combined.20111116.js" type="text/javascript"></script>
    <!-- Change UA-XXXXX-X to be your site's ID -->
    <!--script>
      window._gaq = [['_setAccount','[this-site-id UAXXXXXXXX1]'],['_trackPageview'],['_trackPageLoadTime']];
      Modernizr.load({
        load: ('https:' == location.protocol ? '//ssl' : '//www') + '.google-analytics.com/ga.js'
      });
    </script-->
    <!--[if lt IE 8 ]>
      <script src="//ajax.googleapis.com/ajax/libs/chrome-frame/1.0.3/CFInstall.min.js" type="text/javascript"></script>
      <script>window.attachEvent('onload',function(){CFInstall.check({mode:'overlay'})})</script>
    <![endif]-->
<?php } ?>
