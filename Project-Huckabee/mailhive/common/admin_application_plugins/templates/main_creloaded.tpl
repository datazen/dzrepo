<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset={$SESSION_CHARSET}">
<title>{$TITLE}</title>
<link rel="icon" type="image/png" href="favicon.ico" />
<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jquery/1.6.2/jquery.min.js"></script>


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
  <!-- ================== END PAGE LEVEL STYLE ================== -->
  <script language="javascript" src="includes/general.js"></script>
  <script type="text/javascript" src="includes/menu.js"></script>
  

    <script language="javascript" src="includes/general.js"></script>
    <script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/jquery.min-1.5.1.js"></script>
    <script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/jquery.tools.min-1.2.5.js"></script>
    <script language="javascript" src="{$MH_CATALOG_URL}/mailhive/common/js/ceebox/js/jquery.ceebox-min.js"></script>
    <script language="javascript">
        jQuery.noConflict();
    </script>

{include file="main_common_js.tpl"}
{include file="main_common_ceebox.tpl"}

    <link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}/mailhive/common/js/ceebox/css/ceebox_mh.css"/>
    <link rel="stylesheet" type="text/css" href="{$MH_CATALOG_URL}/mailhive/common/admin_application_plugins/common.css">
</head>
<body>
<div id="popupcalendar" class="text"></div>
    <!-- begin #page-container -->
    <div id="page-container" class="fade page-sidebar-fixed page-header-fixed gradient-enabled">
    <!-- header //-->
    {$ADMIN_HEADER}
    <!-- header_eof //-->
      
    <!-- left_navigation //-->
    {$ADMIN_COLUMN_LEFT}
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
      <h1 class="page-header">MailBeez</h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                        <!-- body_text //-->
                        <td width="100%" valign="top">
                        {include file="main_common_content.tpl"}
                        </td>
                </table> </div>
    </div>
    <!-- end panel -->
    </div>
<!-- body_eof //-->

<!-- footer //-->
{$ADMIN_FOOTER}
<!-- footer_eof //-->

{include file="main_common_update_reminder.tpl"}
</body>
</html>
{$ADMIN_APPLICATION_BOTTOM}
