<?php
/*
  $Id: wpjunior_help.php 2008/06/20 13:48:20Z datazen $

  CRE Loaded, Commerical Open Source eCommerce
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

  require('includes/application_top.php');

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd"><html <?php echo HTML_PARAMS; ?>>
<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo WPJUNIOR_HELP_TITLE;?></title>
<script type="text/javascript" src="includes/prototype.js"></script> 
<link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<script language="javascript" src="admin/includes/general.js"></script>
</head>
<!-- header //-->
<?php require(DIR_WS_INCLUDES . 'header.php'); ?>
<!-- header_eof //-->
<!-- body //-->
<div id="body">
<table border="0" width="100%" cellspacing="0" cellpadding="0" class="body-table">
  <tr>
    <!-- left_navigation //-->
     <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
     <!-- left_navigation_eof //-->
    <!-- body_text //-->
    <td valign="top" class="page-container"><table border="0" width="100%" cellspacing="0" cellpadding="0">
     <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo WPJUNIOR_HELP_MSG_1;?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><?php echo tep_draw_separator('pixel_trans.gif', '1', '10'); ?></td>
      </tr>
       <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
         <td class="main">
           <div align="center"><h2><?php echo WPJUNIOR_HELP_MSG_2;?></h2></div>
         </td>
        </tr>
        <tr>
          <td align="center">
            <?php echo WPJUNIOR_HELP_MSG_3;?>
          </td>
        </tr>
        <tr>
          <td>
            <hr align="center" size="4" width="450">
          </td>
        </tr>
        <tr>
          <td>
            <?php echo WPJUNIOR_HELP_MSG_4;?>
            <hr align="center" size="4" width="450">
          </td>
        </tr>
       </td></table>
      </tr>
     </td>
   </table></td>
   <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>