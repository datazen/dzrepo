<?php
/*
  $Id: jquery_global_header.php,v 1.0 2011/07/15 19:56:29 maestro Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
?>
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>
<script type="text/javascript">
  if (typeof jQuery == 'undefined') {
    //alert('You are running a local copy of jQuery!');
    document.write(unescape("%3Cscript src='includes/javascript/jquery-1.6.2.min.js' type='text/javascript'%3E%3C/script%3E"));
  }
</script>
