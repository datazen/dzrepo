<?php
/*
  $Id: popup_links_help.php,v 1.1.1.1 2004/03/04 23:42:00 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LINKS_SUBMIT);

  // RCI code start
  echo $cre_RCI->get('global', 'top');
  echo $cre_RCI->get('popuplinkshelp', 'top');
  // RCI code eof
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
   "http://www.w3.org/TR/html4/loose.dtd">
<html <?php echo HTML_PARAMS; ?>>
<head>
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo TITLE ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>">
</head>
<body marginwidth="10" marginheight="10" topmargin="10" bottommargin="10" leftmargin="10" rightmargin="10">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_LINKS_HELP);

  new infoBoxHeading($info_box_contents, true, true);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => TEXT_LINKS_HELP);

  new infoBox($info_box_contents);
?>

<p class="smallText" align="right"><?php echo '<a href="javascript:window.close()">' . TEXT_CLOSE_WINDOW . '</a>'; ?></p>

</body>
</html>
<?php 
// RCI code start
echo $cre_RCI->get('popuplinkshelp', 'bottom');
echo $cre_RCI->get('global', 'bottom');
// RCI code eof
require('includes/application_bottom.php'); ?>