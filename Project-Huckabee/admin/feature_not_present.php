<?php
/*
  $Id: validate_New.php,v 1.1.1.1 2004/03/04 23:38:20 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

 THIS IS BETA - Use at your own risk!
  Step-By-Step Manual Order Entry Verion 0.5
  Customer Entry through Admin
*/

  require('includes/application_top.php');

?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="icon" type="image/png" href="favicon.ico" />
<script type="text/javascript" src="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//ajax.googleapis.com/ajax/libs/jquery/<?php echo JQUERY_VERSION; ?>/jquery.min.js"></script>


  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
                                                             <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link href="<?php echo (($request_type == 'SSL') ? 'https:' : 'http:'); ?>//fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <link href="assets/plugins/jquery-ui/themes/base/minified/jquery-ui.min.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet" />
  <link href="assets/plugins/font-awesome/css/font-awesome.min.css" rel="stylesheet" />
  <link href="assets/css/animate.min.css" rel="stylesheet" />
  <link href="assets/css/style.min.css" rel="stylesheet" />
  <link href="assets/css/style-responsive.min.css" rel="stylesheet" />
  <link href="assets/css/theme/blue.css" rel="stylesheet" id="theme" />
  <!-- ================== END BASE CSS STYLE ================== -->
  
  <!-- ================== BEGIN PAGE LEVEL STYLE ================== -->
  <link href="assets/plugins/jquery-jvectormap/jquery-jvectormap-1.2.2.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker.css" rel="stylesheet" />
  <link href="assets/plugins/bootstrap-datepicker/css/datepicker3.css" rel="stylesheet" />
    <link href="assets/plugins/gritter/css/jquery.gritter.css" rel="stylesheet" />  
  <!-- ================== END PAGE LEVEL STYLE ================== -->
  <script language="javascript" src="includes/general.js"></script>
  <script type="text/javascript" src="includes/menu.js"></script>
</head>
<body>
<body>
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed gradient-enabled">
    <!-- header //-->
    <?php require(DIR_WS_INCLUDES . 'header.php'); ?>
    <!-- header_eof //-->
      
    <!-- left_navigation //-->
    <?php require(DIR_WS_INCLUDES . 'column_left.php'); ?>
    <!-- left_navigation_eof //-->
      
    <!-- begin #content -->
    <div id="content" class="content">
      <!-- begin breadcrumb -->
      <ol class="breadcrumb pull-right">
        <li><a href="javascript:;">Home</a></li>
        <li><a href="javascript:;">Tools</a></li>
        <li class="active"><?php echo $cfg_group['configuration_group_title']; ?></li>
      </ol>
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header"><?php echo $cfg_group['configuration_group_title']; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse">
  <table border="0" cellpadding="0" cellspacing="0" style="margin: 1em auto; width: 600px;" align="center">
    <tr>
       <td></td>
       <td style="padding-bottom: 1em;" align="left"><a href="http://store.loadedcommerce.com" target="_blank"><img border="0" src="images/window-logo.png" /></a></td>
       <td></td>
    </tr>
     <tr>
       <td class="box-top-left">&nbsp;</td>
       <td class="box-top">&nbsp;</td>
       <td class="box-top-right" style="width: 48px;" >&nbsp;</td>
     </tr>
     <!--
     <tr>
        <td class="box-left">&nbsp;</td>
        <td style="background: #f0f8fc url(images/window-right.png) repeat-y right;" width="600" colspan="2">
          <img src="images/window-pro.png" align="right" /><img src="images/window-available.png" align="right" style="margin-right: 4px;" />
        </td>
     </tr>
     -->
     <tr>
        <td class="box-left">&nbsp;</td>
        <td class="box-content" valign="top">
          <div class="box-text" style="padding: 1em 0; line-height: 1.8em; font-size: 14px; color: #333;">
          <p>
          This feature is not available with your current Loaded Commerce product.  If you'd like to upgrade, you'll get additional features, support, and flexibility.</p><p><a href="http://www.creloaded.com/products/shoppingcarts/creloadedpro/" target="_blank">Loaded Commerce Professional</a> and <a href="http://www.creloaded.com/creloaded/products/shoppingcarts/creloadedb2b/" target="_blank">Loaded Commerce Professional B2B</a> include a complete Content Management System, automatic URL search engine optimization and additional email and phone support.  Plus, they have increased capabilities with additional features such as one page checkout and abandoned cart recovery.
          </p>
          </div>
        </td> 
        <td class="box-right" style="width: 48px;">&nbsp;</td>
      </tr>
     <tr>
        <td class="box-left">&nbsp;</td>
        <td align="right" class="box-content" valign="bottom">
        <div style="padding-top: 1em;">
        <a href="http://store.loadedcommerce.com" target="_blank"><img src="images/window-more.png" /></a>
        </div>
        </td>
        <td class="box-right" style="width: 48px;">&nbsp;</td>
     </tr>
     <tr>
       <td class="box-bottom-left">&nbsp;</td>
       <td class="box-bottom">&nbsp;</td>
       <td class="box-bottom-right" style="width: 48px;">&nbsp;</td>
     </tr>
  </table>
      
    </td>
    <!-- body_text_eof //-->
  </tr>
</table>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php
    require(DIR_WS_INCLUDES . 'footer.php');
?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>