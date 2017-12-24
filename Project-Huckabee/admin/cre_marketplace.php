<?php
/*
  $Id: cre_marketplace.php,v 1.0 2007/09/20 23:38:56 datazen Exp $

  CRE Loaded, Commercial Open Source E-Commerce
  http://www.creloaded.com

  Copyright (c) 2007 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

require('includes/application_top.php');
require(DIR_WS_LANGUAGES . $language . '/' . FILENAME_CRE_MARKETPLACE);
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
    <div class="panel panel-inverse">
    <?php 
function cre_iframe($n,$zone) {
if($n!='' && $zone !='') {
$iframe_content .= <<<EOF
<script language='JavaScript' type='text/javascript' src='https://adserver.authsecure.com/adx.js'></script>
<script language='JavaScript' type='text/javascript'>
<!--
   if (!document.phpAds_used) document.phpAds_used = ',';
   phpAds_random = new String (Math.random()); phpAds_random = phpAds_random.substring(2,11);
   
   document.write ("<" + "script language='JavaScript' type='text/javascript' src='");
   document.write ("https://adserver.authsecure.com/adjs.php?n=" + phpAds_random);
   document.write ("&amp;what=zone:$zone");
   document.write ("&amp;exclude=" + document.phpAds_used);
   if (document.referrer)
      document.write ("&amp;referer=" + escape(document.referrer));
   document.write ("'><" + "/script>");
//-->
</script><noscript><a href='https://adserver.authsecure.com/adclick.php?n=$n' target='_blank'><img src='https://adserver.authsecure.com/adview.php?what=zone:$zone&amp;n=$n' border='0' alt=''></a></noscript>
EOF;
} else { 
$iframe_content = '';
}
return $iframe_content;
}
?>
<!-- header_eof //-->
<!-- body //-->
<table width="100%" border="0" cellspacing="3" cellpadding="3">
              <tr>
                <td class="main"><!--Banner one Start--><?php echo cre_iframe('a8c53aea','52');?><!--Banner one End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner two Start--><?php echo cre_iframe('ab67cba4','53');?><!--Banner two End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner three Start--><?php echo cre_iframe('a601751d','54');?><!--Banner three End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner four Start--><?php echo cre_iframe('a601751d','55');?><!--Banner four End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner five Start--><?php echo cre_iframe('a4186fe8','56');?><!--Banner five End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner six Start--><?php echo cre_iframe('a777c26a','57');?><!--Banner six End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner sevem Start--><?php echo cre_iframe('ad185eea','58');?><!--Banner seven End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner eight Start--><?php echo cre_iframe('a3d53437','59');?><!--Banner eight End--></td>
              </tr>              
              <tr>
                <td class="main"><!--Banner nine Start--><?php echo cre_iframe('aa0357d5','60');?><!--Banner nine End--></td>
              </tr>
              <tr>
                <td class="main"><!--Banner ten Start--><?php echo cre_iframe('a18b055c','61');?><!--Banner ten End--></td>
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