<?php
/*
  $Id: prettyPhoto_global_header.php,v 1.0 2011/07/15 19:56:29 wa4u Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/ 
  global $PHP_SELF; 
  if (basename($PHP_SELF) == FILENAME_PRODUCT_INFO) {
?>
<link rel="stylesheet" href="<?php echo DIR_WS_TEMPLATES;?>default/css/prettyPhoto.css" type="text/css" media="screen" title="prettyPhoto main stylesheet" charset="utf-8" /> 
<script src="<?php echo DIR_WS_JAVASCRIPT;?>jquery/jquery.prettyPhoto.js" type="text/javascript" charset="utf-8"></script> 
<?php
  }
?>
