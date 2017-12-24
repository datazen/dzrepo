<?php
/*
  $Id: popup_links_help.php,v 1.1.1.1 2004/03/04 23:38:02 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  $navigation->remove_current_page();

  require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_LINKS_SUBMIT);
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
<title><?php echo STORE_NAME; ?></title>
<link rel="stylesheet" type="text/css" href="<?php echo TEMPLATE_STYLE;?>">
</head>
<body class="popupBody">
<?php
  $info_box_contents = array();
  $info_box_contents[] = array('text' => HEADING_LINKS_HELP );
  new popupBoxHeading($info_box_contents, false, false);

  $info_box_contents = array();
  $info_box_contents[] = array('text' => TEXT_LINKS_HELP);
  $info_box_contents[] = array('text' => '<a href="javascript:window.close()"><span class="popupClose">' . TEXT_CLOSE_WINDOW . '</span></a>');
  new popupBox($info_box_contents);
  
  $info_box_contents = array();
  $info_box_contents[] = array('align' => 'left',
                                  'text'  => tep_draw_separator('pixel_trans.gif', '100%', '1')
                                );
  new popupBoxFooter($info_box_contents, false, false);
?>
</body>
</html>
<?php require('includes/application_bottom.php'); ?>