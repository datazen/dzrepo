<?php
/*
  $Id: prettyPhoto_global_footer.php,v 1.0 2011/07/15 19:56:29 wa4u Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
    global $PHP_SELF;
    if(basename($PHP_SELF) == FILENAME_PRODUCT_INFO){
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready(function(){
        $("a[rel^='prettyPhoto']").prettyPhoto({social_tools: false, deeplinking: false});   
     });
</script>
<?php
    }
?>