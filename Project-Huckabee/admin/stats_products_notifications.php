<?php
/*
  Id: stats_products_notifications.php,v 1.1 2003/05/16 00:10:05 ft01189 Exp 

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
  
  Contribution by Radu Manole, radu_manole@hotmail.com
  
  
  Added to CRE Loaded 6.2
  
  Last Modified Date : $Date$
  Last Modified By : $Author$
   
*/
  require('includes/application_top.php');
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
      <h1 class="page-header"><?php echo HEADING_TITLE; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse">   

<?php
// show customers for a product
$action = (isset($_GET['action']) ? $_GET['action']: '');
$pID = (isset($_GET['pID']) ? $_GET['pID']: '');
$page = (isset($_GET['page']) ? $_GET['page']: '');
$rows = (isset($rows) ? $rows : '');

if ($action == 'show_customers' && (int)$pID) {
  $products_id = (int)$pID;
?>

    <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td class="dataTableContent"><?php echo TEXT_DESCRIPTION_TO; ?><b>"<?php echo tep_get_products_name($products_id); ?>"</b>.</td>
        </tr>
        <tr>
          <td>&nbsp;</td>
        </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NAME; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_EMAIL; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_DATE; ?></td>
              </tr>
<?php
  $cpage = (isset($_GET['cpage']) ? $_GET['cpage'] : '');
  if ($cpage > 1) $rows = $cpage * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;

    $customers_query_raw = "select c.customers_firstname, c.customers_lastname, c.customers_email_address, pn.date_added
                            from " . TABLE_CUSTOMERS . " c, " . TABLE_PRODUCTS_NOTIFICATIONS . " pn 
                            where c.customers_id = pn.customers_id and pn.products_id = '" . $products_id . "' 
                            order by c.customers_firstname, c.customers_lastname";

    $customers_split = new splitPageResults($_GET['cpage'], MAX_DISPLAY_SEARCH_RESULTS, $customers_query_raw, $customers_query_numrows);
    $customers_query = tep_db_query($customers_query_raw);
  
    while ($customers = tep_db_fetch_array($customers_query)) {
      $rows++;

      if (strlen($rows) < 2) {
        $rows = '0' . $rows;
      }
?>
              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'">
                <td width="30" nowrap class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?php echo $customers['customers_firstname'] . ' ' . $customers['customers_lastname']; ?></td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_MAIL, 'selected_box=tools&customer=' . $customers['customers_email_address'], 'NONSSL') . '">' . $customers['customers_email_address'] . '</a>'; ?>&nbsp;</td>
                <td class="dataTableContent"><?php echo $customers['date_added']; ?>&nbsp;</td>
              </tr>
<?php
    }
?>
            </table></td>
          </tr> 
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr>
                <td class="smallText" valign="top"><?php echo $customers_split->display_count($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['cpage'], 'Displaying <b>%s</b> to <b>%s</b> (of <b>%s</b> customers)' , '', 'cpage'); ?></td>
                <td class="smallText" align="right"><?php echo $customers_split->display_links($customers_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['cpage'], tep_get_all_get_params(array('cpage')), 'cpage'); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
      <table width="100%" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="5"></td>
        </tr>
        <tr> 
          <td align="right"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_NOTIFICATIONS, tep_get_all_get_params(array('action', 'pID', 'cpage'))) . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a>'; ?></td>
        </tr>
      </table>
<?php
// default
} else {

?>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="pageHeading"><?php echo HEADING_TITLE; ?></td>
            <td class="pageHeading" align="right"><?php echo tep_draw_separator('pixel_trans.gif', HEADING_IMAGE_WIDTH, HEADING_IMAGE_HEIGHT); ?></td>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td class="dataTableContent"><?php echo TEXT_DESCRIPTION; ?></td>
      </tr>
      <tr>
        <td>&nbsp;</td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
              <tr class="dataTableHeadingRow">
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_PRODUCTS; ?></td>
                <td class="dataTableHeadingContent" align="center"><?php echo TABLE_HEADING_COUNT; ?>&nbsp;</td>
              </tr>
<?php
  if ($page > 1) $rows = $page * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;

    $products_notifications_query_raw = "select count(pn.products_id) as count_notifications, pn.products_id, pd.products_name 
                                   from " . TABLE_PRODUCTS_NOTIFICATIONS . " pn, " . TABLE_PRODUCTS_DESCRIPTION . " pd, " . TABLE_CUSTOMERS . " c
                                         where pn.products_id = pd.products_id and pd.language_id = '" . $languages_id . "' 
                                         and pn.customers_id = c.customers_id 
                                         group by pn.products_id order by count_notifications desc";
    // fix numrows
    $products_count_query = tep_db_query($products_notifications_query_raw);

    $products_notifications_split = new splitPageResults($page, MAX_DISPLAY_SEARCH_RESULTS, $products_notifications_query_raw,     $products_notifications_query_numrows);     
    $products_notifications_query = tep_db_query($products_notifications_query_raw);
    $products_notifications_query_numrows = tep_db_num_rows($products_count_query);
  
    while ($products = tep_db_fetch_array($products_notifications_query)) {
      $rows++;

      if (strlen($rows) < 2) {
        $rows = '0' . $rows;
      }
?>
              <tr class="dataTableRow" onmouseover="this.className='dataTableRowOver';this.style.cursor='hand'" onmouseout="this.className='dataTableRow'" onclick="document.location.href='<?php echo tep_href_link(FILENAME_STATS_PRODUCTS_NOTIFICATIONS, 'action=show_customers&pID=' . $products['products_id'] . '&page=' . $page, 'NONSSL'); ?>'">
                <td width="30" nowrap class="dataTableContent"><?php echo $rows; ?>.</td>
                <td class="dataTableContent"><?php echo '<a href="' . tep_href_link(FILENAME_STATS_PRODUCTS_NOTIFICATIONS, 'action=show_customers&pID=' . $products['products_id'] . '&page=' . $page, 'NONSSL') . '">' . $products['products_name'] . '</a>'; ?></td>
                <td class="dataTableContent" align="center"><?php echo $products['count_notifications']; ?>&nbsp;</td>
              </tr>
<?php
  }
?>
            </table></td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table-foot">
              <tr>
                <td class="smallText" valign="top"><?php echo $products_notifications_split->display_count($products_notifications_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $page, TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $products_notifications_split->display_links($products_notifications_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $page); ?></td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table>
<?php
} // end else
?>
    </td>
<!-- body_text_eof //-->
  </tr>
</table>   </div></div>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
