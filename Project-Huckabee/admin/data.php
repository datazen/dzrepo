<?php
/*
  $Id: data.php,v 2.0.0.0 2008/05/13 13:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

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
      <?php
      // RCI start
      echo $cre_RCI->get('data', 'listingtop');
      // RCI eof
      ?>     
      <tr>
        <td valign="top"><table width="70%" border="0" cellspacing="2" cellpadding="2">
  <tr>
    <td colspan="2" class="main"><strong><?php echo WELCOME_TO_DATA_EXPORT_IMPORT_SYSTEM;?></strong></td>
  </tr>
  <tr>
    <td colspan="2"><?php echo tep_draw_separator('pixel_black.gif', '100%', '1'); ?></td>
  </tr>
  <tr>
    <td width="50%" valign="top" class="main"><p style="font-weight:bold;"><?php echo TEXT_HELP_EASY_POPULATE;?></p>
      <ul>                                                                                                                       
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=1') . '">' . TEXT_EP_INTRO . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=2') . '">' . TEXT_EP_ADV_IMPORT . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=3') . '">' . TEXT_EP_ADV_EXPORT . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=4') . '">' . TEXT_EP_BASIC_IMPORT . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=5') . '">' . TEXT_EP_BASIC_EXPORT . '</a>'; ?></li>
        <li><?php echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=6') . '">' . TEXT_EP_EDITING_FILE . '</a>'; ?></li>
    </ul></td>
    <!-- td width="50%" valign="top" class="main"><p style="font-weight:bold;"><?php echo TEXT_HELP_DATA_FEEDER_SYSTEM;?></p>
      <ul>
        <li><?php //echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=9') . '">' . TEXT_DATA_INTRO . '</a>'; ?></li>
        <li><?php //echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=10') . '">' . TEXT_DATA_FIRST_GOOGLE_FEED . '</a>'; ?></li>
        <li><?php //echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=11') . '">' . TEXT_DATA_CONFIGURE_FEED . '</a>'; ?></li>
        <li><?php //echo '<a href="' . tep_href_link(FILENAME_DATA_HELP, 'help_id=12') . '">' . TEXT_DATA_RUN_FEED . '</a>'; ?></li>
    </ul></td -->
  </tr>
</table>
        </td>
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