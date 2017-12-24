<?php
/*

  Copyright (c) 2007 CRWworks.com
  http://www.creloaded.com

  Released under the GNU General Public License
 * The main GUI for the ImageManager.
 * @author $Author: Wei Zhuo $
 * @version $Id: manager.php 26 2004-03-31 02:35:21Z Wei Zhuo $
 * @package ImageManager
 */
require('includes/application_top.php');

    require_once('includes/javascript/image_manager/config.inc.php');
    require_once('includes/javascript/image_manager/Classes/ImageManager.php');


require(DIR_WS_LANGUAGES . $language . '/imagemanager_manger.php');

    $manager = new ImageManager($IMConfig);
    $dirs = $manager->getDirs();

;?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html <?php echo HTML_PARAMS; ?>>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>
<script type="text/javascript">
  if (typeof jQuery == 'undefined') {
    //alert('You are running a local copy of jQuery!');
    document.write(unescape("%3Cscript src='includes/javascript/jquery-1.6.2.min.js' type='text/javascript'%3E%3C/script%3E"));
  }
</script>
 <link rel="stylesheet" type="text/css" href="includes/javascript/image_manager/assets/manager.css" />
 <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
<!--[if IE]>
<link rel="stylesheet" type="text/css" href="includes/stylesheet-ie.css">
<![endif]-->
<script type="text/javascript" src="includes/javascript/image_manager/assets/popup.js"></script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/dialog.js"></script>
<script type="text/javascript">
/*<![CDATA[*/

    if(window.opener)
        I18N = window.opener.ImageManager.I18N;

    var thumbdir = "<?php echo $IMConfig['thumbnail_dir']; ;?>";
    var base_url = "<?php echo $manager->getBaseURL(); ;?>";
/*]]>*/
</script>
<script type="text/javascript" src="includes/javascript/image_manager/assets/manager.js"></script>
<link rel="stylesheet" type="text/css" href="includes/headernavmenu.css">
<script type="text/javascript" src="includes/menu.js"></script>
</head>
<body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0" bgcolor="#FFFFFF">
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

    <div id="messages" style="display: none;"><span id="message"></span><img SRC="includes/javascript/image_managerimg/dots.gif" width="22" height="12" alt="..." /></div>
<!-- Image Display -->
    <iframe src="includes/javascript/image_manager/manager.php" name="imgManager" id="imgManager" class="imageManager" width="100%" scrolling="no" title="Image Selection" frameborder="0"></iframe>

<!-- Image Display -->
    <div style="text-align: right;">
          <hr />
    </div>


</div>
</fieldset>
      </tr>
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
