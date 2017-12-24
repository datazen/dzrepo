<?php
/*
  $Id: data_help.php,v 1.0.0.0 2008/05/13 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

*/
  require('includes/application_top.php');

require(DIR_WS_LANGUAGES . $language . '/help/data_help.php')

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
    <link href="assets/plugins/DataTables/media/css/dataTables.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Select/css/select.bootstrap.min.css" rel="stylesheet" />
    <link href="assets/plugins/DataTables/extensions/Responsive/css/responsive.bootstrap.min.css" rel="stylesheet" />
  <!-- ================== END PAGE LEVEL STYLE ================== -->
  <script language="javascript" src="includes/general.js"></script>
  <script type="text/javascript" src="includes/menu.js"></script>
</head>
<body>
    <!-- begin #page-container -->
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
        <li>Create &nbsp; <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ACCOUNT;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ACCOUNT,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-user"></i><span class="label">+</span></a> <a title="<?php echo BOX_MANUAL_ORDER_CREATE_ORDER;?>" href="<?php echo tep_href_link(FILENAME_CREATE_ORDER,'','SSL');?>" class="btn btn-xs btn-header"><i class="fa fa-shopping-cart"></i><span class="label">+</span></a></li>
        <li>Search &nbsp; <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="ProductsPopover">Products</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="CustomerPopover">Customers</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="OrdersPopover">Orders</a> <a href="javascript:;" class="btn btn-header btn-xs header-popover" id="PagesPopover">Pages</a></li>
      </ol>
      <!-- end breadcrumb -->
      <!-- begin page-header -->
      <h1 class="page-header"><?php echo HEADING_TITLE; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
      <tr>  
        <?php
        if (isset($_GET['help_id'])) {
          $help_id = $_GET['help_id'];
        } else {
          $help_id = '';
          define('HEADING_TITLE', HEADING_TITLE0);
        }
        if ($help_id == '1') {
          define('HEADING_TITLE', HEADING_TITLE1);
        }
        if ($help_id == '2') {
          define('HEADING_TITLE', HEADING_TITLE2);
        }
        if ($help_id == '3') {
          define('HEADING_TITLE', HEADING_TITLE3);
        }
        if ($help_id == '4') {
          define('HEADING_TITLE', HEADING_TITLE4);
        }
        if ($help_id == '5') {
          define('HEADING_TITLE', HEADING_TITLE5);
        }
        if ($help_id == '6') {
          define('HEADING_TITLE', HEADING_TITLE6);
        }
        if ($help_id == '9') {
          define('HEADING_TITLE', HEADING_TITLE9);
        }
        if ($help_id == '10') {
          define('HEADING_TITLE', HEADING_TITLE10);
        } 
        if ($help_id == '11') {
          define('HEADING_TITLE', HEADING_TITLE11);
        }
        if ($help_id == '12') {
          define('HEADING_TITLE', HEADING_TITLE12);
        }
        ?>
        <td width="100%" valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="menuBoxHeading">

          <tr>
            <td><?php echo tep_draw_separator('pixel_trans.gif', '24', '15') . '&nbsp;'  ; ?> </td>
          </tr>
          <tr>
            <td valign="top" clsss="main">
              <?php
              if ($help_id == '1') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_intro.html') ;
              }
              if ($help_id == '2') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_import.html') ;
              }
              if ($help_id == '3') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_export.html') ;
              }
              if ($help_id == '4') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_basicimport.html') ;
              }
              if ($help_id == '5') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_basicexport.html') ;
              }
              if ($help_id == '6') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_spreadsheet.html') ;
              }
              if ($help_id == '9') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_feed_intro.html') ;
              }
              if ($help_id == '10') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_googlebacisgettingstarted.html') ;
              }
              if ($help_id == '11') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_googleconfigure.html') ;
              }
              if ($help_id == '12') {
                include(DIR_WS_LANGUAGES . $language . '/help/ep/data_googlerun.html') ;
              }
              ?>
            </td>
          </tr>
          <?php 
          if(tep_not_null($help_id)){
            ?>
            <tr>
              <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?> </td>
            </tr>
            <tr>
              <td  valign="top" class="main" align="right"><a href="<?php echo tep_href_link(FILENAME_DATA,'selected_box=data');?>"><?php echo tep_image_button('button_return.gif','Return');?></a></td>
            </tr>
            <?php 
          } 
          ?>
        </table></td>
      </tr>
    </table></div></div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>