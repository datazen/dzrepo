<?php
/*
  $Id: idealm_error.tpl.php - CRELOADED v6.4.1 version - v2.1

  Released under the GNU General Public License

  Parts may be copyrighted by osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com
*/

  require('includes/application_top.php');

  $GLOBALS['cart_contents'] = unserialize(serialize($_SESSION['cart_contents']));
  $_SESSION['cart'] = $GLOBALS['cart_contents'];

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_IDEALM);

  $content = IDEALM_ERROR_INFO;

  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);

  require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
