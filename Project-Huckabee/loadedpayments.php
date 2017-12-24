<?php
 /**
  @name       loadedpayments.php   
  @version    1.0.0 | 05-21-2012 | datazen
  @author     Loaded Commerce Core Team
  @copyright  (c) 2012 loadedcommerce.com
  @license    GPL2
*/
require('includes/application_top.php');
include(DIR_WS_LANGUAGES . $language . '/loadedpayments.php');

function rePost() {
  foreach ($_POST as $key => $value) {
    echo '<input type="hidden" name="' . $key . '" value="' . $value . '">' . "\n";
  }
}

if (file_exists(DIR_WS_CLASSES . 'rci.php')) { // is CRE                                                   
  $breadcrumb->add(HEADING_TITLE, tep_href_link('loadedpayments.php', '', 'NONSSL'));
  $content = 'loadedpayments';
  require(DIR_WS_TEMPLATES . TEMPLATE_NAME . '/' . TEMPLATENAME_MAIN_PAGE);
} else if (file_exists(DIR_WS_INCLUDES . 'column_left.php')) { // is osc 2.2
  ?>
  <!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
  <html <?php echo HTML_PARAMS; ?>>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
    <title><?php echo TITLE; ?></title>
    <base href="<?php echo (($request_type == 'SSL') ? HTTPS_SERVER : HTTP_SERVER) . DIR_WS_CATALOG; ?>">
    <link rel="stylesheet" type="text/css" href="stylesheet.css">
  </head>
  <body marginwidth="0" marginheight="0" topmargin="0" bottommargin="0" leftmargin="0" rightmargin="0">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
    <!-- body //-->
    <table border="0" width="100%" cellspacing="3" cellpadding="3">
      <tr>
        <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
          <!-- left_navigation //-->
          <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
          <!-- left_navigation_eof //-->
        </table></td>
        <!-- body_text //-->
        <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
                <td class="pageHeading" align="right"><?php echo tep_image(DIR_WS_IMAGES . 'table_background_specials.gif', HEADING_TITLE, HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <tr>
            <td><table border="0" width="100%" cellspacing="1" cellpadding="2" class="infoBox">
              <tr class="infoBoxContents">
                <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
                  <tr>
                    <td class="main">
                      <div id="container" style="position:relative;">
                        <form name="pmtForm" id="pmtForm" action="<?php echo $_SESSION['payform_url']; ?>" target="pmtFrame" method="post"><?php rePost(); ?></form>        
                        <div id="loadingContainer"  style="position: absolute; left:220px; top:100px;"><p><img border="0" src="images/lp-loading.png"></p></div>
                        <iframe frameborder="0" onload="setTimeout(function() {hideLoader();},1250);" src="" id="pmtFrame" name="pmtFrame" height="300px" width="606px" scrolling="no" marginheight="0" marginwidth="0">Your browser does not support iframes.</iframe> 
                      </div>
                    </td>
                  </tr>
                </table></td>
              </tr>
            </table></td>
          </tr>
          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
          </tr>
          <!-- body_text_eof //-->
        </td></table>
        <td width="<?php echo BOX_WIDTH; ?>" valign="top"><table border="0" width="<?php echo BOX_WIDTH; ?>" cellspacing="0" cellpadding="2">
          <!-- right_navigation //-->
          <?php require(DIR_WS_INCLUDES . 'column_right.php'); ?>
          <!-- right_navigation_eof //-->
        </table></td>
      </tr>
    </table>
    <!-- body_eof //-->
    <!-- footer //-->
    <?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
    <!-- footer_eof //-->
    <br>
    <script>
      function hideLoader() {
        var loadDiv = document.getElementById("loadingContainer"); 
        loadDiv.style.display = "none"; 
      }
      
      window.onload = function(){
        document.forms["pmtForm"].submit();
      };        
    </script>
  </body>
  </html>
  <?php
} else { // is osc 2.3  
  $breadcrumb->add('Loaded Payments', tep_href_link('loadedpayments.php')); 
  require(DIR_WS_INCLUDES . 'template_top.php');
  ?>
  <h1><?php echo HEADING_TITLE; ?></h1>
  <div class="contentContainer">
    <div class="contentText" style="position:relative;">
      <form name="pmtForm" id="pmtForm" action="<?php echo $_SESSION['payform_url']; ?>" target="pmtFrame" method="post"><?php rePost(); ?></form>        
      <div id="loadingContainer"  style="position: absolute; left:220px; top:100px;"><p><img border="0" src="images/lp-loading.png"></p></div>
      <iframe frameborder="0" onload="setTimeout(function() {hideLoader();},1250);" src="" id="pmtFrame" name="pmtFrame" height="300px" width="606px" scrolling="no" marginheight="0" marginwidth="0">Your browser does not support iframes.</iframe> 
    </div>
    <script>
      function hideLoader() {
        var loadDiv = document.getElementById("loadingContainer"); 
        loadDiv.style.display = "none"; 
      }
      
      window.onload = function(){
        document.forms["pmtForm"].submit();
      };        
    </script>    
  </div>
  <?php
  require(DIR_WS_INCLUDES . 'template_bottom.php'); 
}
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>