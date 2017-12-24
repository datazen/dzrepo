<?php
/*
  $Id: create_order.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License

*/

  require('includes/application_top.php');

// #### Get Available Customers

  $query = tep_db_query("select customers_id, customers_firstname, customers_lastname from " . TABLE_CUSTOMERS . " ORDER BY customers_lastname DESC");
  $result = $query;

  if (tep_db_num_rows($result) > 0) {
    // Query Successful
    $SelectCustomerBox = "<select name='Customer'><option value=''>". BUTTON_TEXT_CHOOSE_CUST . "</option>\n";
    while($db_Row = tep_db_fetch_array($result)){
      $SelectCustomerBox .= "<option value='" . $db_Row["customers_id"] . "'";
      if(isset($_GET['Customer']) and $db_Row["customers_id"]==$_GET['Customer']) $SelectCustomerBox .= ' selected="selected" ';
      $SelectCustomerBox .= ">" . $db_Row["customers_lastname"] . " , " . $db_Row["customers_firstname"] . "</option>\n";
    }
    $SelectCustomerBox .= "</select>\n";
  }
//newcode below
  $query = tep_db_query("select code, value from " . TABLE_CURRENCIES . " ORDER BY code");
  $result = $query;
  
  if (tep_db_num_rows($result) > 0) {
    // Query Successful
    $SelectCurrencyBox = '<select name="Currency"><option value="" selected="selected">' . TEXT_SELECT_CURRENCY . "</option>\n";
    while($db_Row = tep_db_fetch_array($result)) { 
      $SelectCurrencyBox .= "<option value='" . $db_Row["code"] . " , " . $db_Row["value"] . "'";
      $SelectCurrencyBox .= ">" . $db_Row["code"] . "</option>\n";
    }
    $SelectCurrencyBox .= "</select>\n";
  }
  if(isset($_GET['Customer'])) {
    //$account_query = tep_db_query("select * from " . TABLE_CUSTOMERS . " where customers_id = '" . $_GET['Customer'] . "'");
    $account_query = tep_db_query("select c.*,ab.entry_telephone as customers_telephone from " . TABLE_CUSTOMERS . " c, ".TABLE_ADDRESS_BOOK." ab where c.customers_id = '" . $_GET['Customer'] . "' and c.customers_id = ab.customers_id and ab.address_book_id = c.customers_default_address_id");

    $account = tep_db_fetch_array($account_query);
    $customer = $account['customers_id'];
    $address_query = tep_db_query("select ab.* from " . TABLE_CUSTOMERS . " c, ".TABLE_ADDRESS_BOOK." ab where c.customers_id = '" . $_GET['Customer'] . "' and c.customers_id = ab.customers_id and ab.address_book_id = c.customers_default_address_id");
    $address = tep_db_fetch_array($address_query);
  }

// #### Generate Page
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=<?php echo CHARSET; ?>">
<title><?php echo TITLE; ?></title>
<link rel="icon" type="image/png" href="favicon.ico" />
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
      <h1 class="page-header"><?php echo $HEADING_STEP1; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse"><table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <td class="main" valign="top"><?php
                echo tep_draw_form('select_customer', FILENAME_CREATE_ORDER, tep_get_all_get_params(array('action','select_customer')) . '', 'get', '', 'SSL') ;
                if (isset($_GET[tep_session_name()])) {
                  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
                }
                echo  '<table border="0"><tr>' . "\n";
                echo  tep_draw_separator('pixel_trans.gif', '100%', '10');
                echo  '<td><font class="main"><b>' . TEXT_SELECT_CUST . '</b></font><br>' . $SelectCustomerBox . '</td>' . "\n";
                echo  '<td valign="bottom">' . tep_image_submit('submit.png', BUTTON_TEXT_SELECT_CUST) . '</td>' . "\n";
                echo  '</tr></table></form>' . "\n";
              ?>
                  <?php
                echo tep_draw_form('select_customer', FILENAME_CREATE_ORDER, tep_get_all_get_params(array('action','select_customer')) . '', 'get', '', 'SSL') ;
                //echo  '<form action="' . FILENAME_CREATE_ORDER . '" method="GET">' . "\n";
                if (isset($_GET[tep_session_name()])) {
                  echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
                }
                echo  '<table border="0"><tr>' . "\n";
                echo  '<td><font class="main"><b>' . TEXT_OR_BY . '</b></font><br><input type="text" name="Customer"></td>' . "\n";
                echo  '<td valign="bottom">' . tep_image_submit('submit.png', BUTTON_TEXT_CHOOSE_CUST) . '</td>' . "\n";
                echo  '</tr></table></form>' . "\n";
              ?>
                </td>
              </tr>
              <tr>
                <td width="100%" valign="top"><?php 
              echo tep_draw_form('account_edit', FILENAME_CREATE_ORDER_PROCESS, tep_get_all_get_params(array('action','create_order')) . '', 'post', 'onSubmit="return check_form(account_edit);"', 'SSL');
              echo (isset($account['customers_id']) ? tep_draw_hidden_field('customers_id', $account['customers_id']) : ''); 
              ?>
              <table border="0" width="100%" cellspacing="0" cellpadding="0">
                <tr>
                  <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
                    <tr>
                      <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                    </tr>
                    <tr>
                      <td class="pageHeading"><?php echo HEADING_CREATE; ?></td>
                    </tr>
                  </table></td>
                </tr>
                <tr>
                  <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
                </tr>
                <tr>
                  <td><?php
                    //onSubmit="return check_form();"
                    require(DIR_WS_MODULES . 'create_order_details.php');
                  ?>
                  </td>
                </tr>
              </table>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
              <tr>
                <td>
                <?php
                  if(isset($_REQUEST['Customer']) && $_REQUEST['Customer']!='') {
                ?>
                  <table border="0" width="100%" cellspacing="0" cellpadding="2">
                    <tr>
                      <td class="main"><?php echo '<a href="javascript: history.go(-1)">' . tep_image_button('button_back.gif', IMAGE_BUTTON_BACK) . '</a>'; ?></td>
                      <td class="main" align="right"><?php echo tep_image_submit('button_confirm.gif', IMAGE_BUTTON_CONFIRM); ?></td>
                    </tr>
                  </table>
                  <?php
                  }
                  ?>
                </td>
              </tr>
              <tr>
                <td><?php echo tep_draw_separator('pixel_trans.gif', '100%', '10'); ?></td>
              </tr>
            </table></td>
        </tr>
      </table>
      </form></div></div></div>
</div>
<!-- body_eof //-->
<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
<br>
</body>
</html>
<?php
require(DIR_WS_INCLUDES . 'application_bottom.php');
?>
