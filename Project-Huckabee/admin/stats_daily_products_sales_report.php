<?php
/*
  Written by Marc Sauton, September 2004
  Daily Product Report Contribution for the OsCommerce Community
  Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  $date = (isset($_REQUEST['date']) ? $_REQUEST['date'] : '');
  $csv_accum = (isset($csv_accum) ? $csv_accum: '');
  // start csv - bounce csv string back as file
  if (isset($_POST['csv'])) {
    if ($_POST['saveas']) {  // rebound posted csv as save file
   $savename= $_POST['saveas'] . ".csv";
  }
  else $savename='unknown.csv';
  $csv_string = '';
  if ($_POST['csv']) $csv_string=$_POST['csv'];
  if (strlen($csv_string)>0){
    header("Expires: Mon, 26 Nov 1962 00:00:00 GMT");
    header("Last-Modified: " . gmdate('D,d M Y H:i:s') . ' GMT');
    header("Cache-Control: no-cache, must-revalidate");
    header("Pragma: no-cache");
    header("Content-Type: Application/octet-stream");
    header("Content-Disposition: attachment; filename=$savename");
    echo $csv_string;
  }
  else echo "CSV string empty";
  exit;
  };
  //end csv

  if($date == "") {
     $date = date('Y-m-d'); #2003-09-07%
  } else {
      if(  preg_match("/([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})/", $date)) {
          $date = $date;
      } else {
          $date = date('Y-m-d'); #2003-09-07%
      }
  }
  if( $date == "") { $date = date('Y-m-d'); }
  $cal1maxdate = date("Y") . "," . date("m") . "," . date("d");
?>
<!DOCTYPE html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<title>Loaded Commercial Open Source eCommerce</title>
<link rel="icon" type="image/png" href="favicon.ico" />
  <meta content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" name="viewport" />
  <link rel="stylesheet" type="text/css" href="includes/stylesheet.css">
  <!-- ================== BEGIN BASE CSS STYLE ================== -->
  <link href="http://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
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
<div id="spiffycalendar" class="text"></div>
<script language="javascript"><!--
  var cal1 = new ctlSpiffyCalendarBox("cal1", "dailyreportform", "reportdate","btnDate3","",scBTNMODE_CALBTN);
  cal1.readonly=true;
  cal1.displayLeft=true;
  // cal1.JStoRunOnSelect="document.dailyreportform.submit();";
<?php
  if (isset($_GET[tep_session_name()])) {
    $oscid = '&' . tep_session_name() . '=' . $_GET[tep_session_name()];
  } else {
    $oscid = '';
  }
?>
  cal1.JStoRunOnSelect="document.dailyreportform.action='<?php echo tep_href_link(basename($PHP_SELF));?>?date='+document.dailyreportform.reportdate.value + '<?php echo $oscid; ?>'; document.dailyreportform.submit();";
  cal1.useDateRange=true;
  cal1.setMinDate(2004,1,1);
  cal1.setMaxDate( <?php echo $cal1maxdate; ?> );
//--></script>
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
      <h1 class="page-header"><?php echo HEADING_TITLE . $date; ?></h1>
      <!-- end page-header -->
      
    <!-- begin panel -->
    <div class="panel panel-inverse">



<table border="0" width="100%" cellspacing="0" cellpadding="0">
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="0">
          <tr>
            <td class="main" align="right">
            <!-- BOF drop down OS -->
            <?php echo tep_draw_form('status', FILENAME_STATS_DAILY_SALES_REPORT, '', 'get');
            // get list of orders_status names for dropdown selection
            if (isset($_GET[tep_session_name()])) {
              echo tep_draw_hidden_field(tep_session_name(), $_GET[tep_session_name()]);
            }
            $orders_status_array = array();
            $orders_statuses[] = array('id' => '', 'text' => TEXT_ALL_ORDERS);
            $orders_status_query = tep_db_query("select orders_status_id, orders_status_name from " . TABLE_ORDERS_STATUS . " where language_id = '" . $languages_id . "'");
            while ($orders_status = tep_db_fetch_array($orders_status_query)) {
                $orders_statuses[] = array('id' => $orders_status['orders_status_id'], 'text' => $orders_status['orders_status_name']);
                $orders_status_array[$orders_status['orders_status_id']] = $orders_status['orders_status_name'];
            }
            echo TEXT_ORDERS_STATUS . ': ' . tep_draw_pull_down_menu('status', $orders_statuses, '', 'onChange="this.form.submit();"'); 
            if(isset($_GET['date']))  echo tep_draw_hidden_field('date', $date); 
            ?>  
             </form>
            <!-- EOF drop down OS --></td>
            <td class="main" align="right">
                <?php echo tep_draw_form('dailyreportform', FILENAME_STATS_DAILY_SALES_REPORT, 'date=' . $date, 'post');?>
                <!-- input type="hidden" name="action" value="dailyreportaction" -->
                <?php // <br>cal1 value:<script language="javascript">document.write( document.forms.dailyreportform.action);</script><br> ?>
                <?php echo DISPLAY_ANOTHER_REPORT_DATE;?>
                <script language="javascript">cal1.writeControl(); cal1.dateFormat="yyyy-MM-dd"; document.dailyreportform.reportdate.value="<?php echo $date; ?>"</script></td>
                </form>
          </tr>
        </table></td>
      </tr>
      <tr>
        <td><table border="0" width="100%" cellspacing="0" cellpadding="2">
          <tr>
            <td valign="top"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table">
              <tr class="dataTableHeadingRow">
<?php
$csv_accum .= "";
?>
                <td class="dataTableHeadingContent"><?php echo TABLE_HEADING_NUMBER; ?></td>
                <td class="dataTableHeadingContent"><?php mirror_out(TABLE_HEADING_ORDER_QUANTITY); ?></td>
                <td class="dataTableHeadingContent"><?php mirror_out(TABLE_HEADING_PRODUCT_NAME); ?></td>
                <td class="dataTableHeadingContent"><?php mirror_out(TABLE_HEADING_PRODUCT_MODEL); ?></td>
                <td class="dataTableHeadingContent"><?php mirror_out(TABLE_HEADING_UNITPRICE); ?></td>
                <td class="dataTableHeadingContent"><?php mirror_out(TABLE_HEADING_PRODUCT_QUANTITY); ?></td>
                <td class="dataTableHeadingContent" align="right"><?php mirror_out(TABLE_HEADING_TOTAL_PURCHASED); ?>&nbsp;</td>
              </tr>

<?php
// new line for CSV
$csv_accum .= "\n";
//

  if (isset($_GET['page']) && ($_GET['page'] > 1)) $rows = $_GET['page'] * MAX_DISPLAY_SEARCH_RESULTS - MAX_DISPLAY_SEARCH_RESULTS;
  $products_query_sql = "select ot.value, sum(ot.value) as dailyvalue, count(distinct o.orders_id) as howmany_orders, o.orders_id, sum(op.products_quantity) as howmany_tickets, op.products_id, op.products_name, op.products_model, op.final_price as ticket_price, op.final_price * sum(op.products_quantity) as howmuch from orders_total ot, orders o, orders_products op where ";
  $status = '';
  if (isset($_GET['status']) && $_GET['status'] != '') $status = tep_db_prepare_input($_GET['status']);
  if ($status <> '') $products_query_sql .= "o.orders_status ='" . $status . "' and ";
  $products_query_sql .= "o.date_purchased like \"$date%\" and o.orders_id = op.orders_id and ot.orders_id = op.orders_id and ot.class='ot_total' group by op.products_name";
  $products_query_raw = $products_query_sql;
  $customers_split = new splitPageResults($_GET['page'], MAX_DISPLAY_SEARCH_RESULTS, $products_query_raw, $products_query_numrows);
  $products_query_numrows = tep_db_query($products_query_sql);
  $products_query_numrows = tep_db_num_rows($products_query_numrows);

  $rows = 0;
  $total_purchased = 0;
  $list_total_purchased = 0;
  $products_query = tep_db_query($products_query_raw);
  while ($products = tep_db_fetch_array($products_query)) {
    $rows++;

    if (strlen($rows) < 2) {
      $rows = '0' . $rows;
    }
    ?>
      <tr class="dataTableRow" onmouseover="rowOverEffect(this)" onmouseout="rowOutEffect(this)" onclick="document.location.href='<?php echo tep_href_link(FILENAME_CATEGORIES, 'action=new_product_preview&read=only&pID=' . $products['products_id'] . '?page=' . $_GET['page'], 'NONSSL'); ?>'">
        <td class="dataTableContent"><?php echo $rows; ?>.</td>
        <td class="dataTableContent"><?php mirror_out(number_format($products['howmany_orders'],0)) ?></td>
        <td class="dataTableContent"><?php mirror_out($products['products_name']) ?></td>
        <td class="dataTableContent"><?php mirror_out($products['products_model']) ?></td>
        <td class="dataTableContent"><?php mirror_out(number_format($products['ticket_price'],2)) ?></td>
        <td class="dataTableContent"><?php mirror_out(number_format($products['howmany_tickets'],0)) ?></td>
        <td class="dataTableContent" align="right"><?php mirror_out(number_format($products['howmuch'],2)); ?></td>
      </tr>
      <?php
      $list_total_purchased = $list_total_purchased + $products['howmuch'];  
      // new line for CSV
      $csv_accum .= "\n";
      //
  }
  $total_purchased = 0;
  $total_query = tep_db_query("select op.final_price * op.products_quantity as total from orders o, orders_products op where o.date_purchased like \"$date%\" and o.orders_id = op.orders_id");
  while ($total_data = tep_db_fetch_array($total_query)) {
    $total_purchased += $total_data['total'];
  }
  ?>
    </table></td>
       </tr>
       <tr>
         <td class=main align=center>
           <?php  echo '<b>' . TABLE_DAILY_VALUE . $currencies->format($list_total_purchased) . '</b>&nbsp;&nbsp;&nbsp;'; 
           echo '<b>' . TABLE_ACCUMULATED_VALUE . $currencies->format($total_purchased) . '</b>'; ?>
         </td>
       </tr>
          <tr>
            <td class="smallText" colspan="4"><form action="<?php echo tep_href_link(FILENAME_STATS_DAILY_SALES_REPORT, '', 'SSL'); ?>" method=post>
              <input type='hidden' name='csv' value='<?php echo $csv_accum; ?>'>
              <input type="hidden" name="saveas" value="daily_product_sales_report_<?php echo date('YmdHi'); ?>">
              <?php echo tep_image_submit('submit.png', TEXT_BUTTON_REPORT_SAVE) ?>
              </form>
            </td>
          </tr>
          <tr>
            <td colspan="3"><table border="0" width="100%" cellspacing="0" cellpadding="0" class="data-table-foot">
              <tr>
                <td class="smallText" valign="top"><?php echo $customers_split->display_count($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, $_GET['page'], TEXT_DISPLAY_NUMBER_OF_PRODUCTS); ?></td>
                <td class="smallText" align="right"><?php echo $customers_split->display_links($products_query_numrows, MAX_DISPLAY_SEARCH_RESULTS, MAX_DISPLAY_PAGE_LINKS, $_GET['page'], tep_get_all_get_params(array('page'))); ?>&nbsp;</td>
              </tr>
            </table></td>
          </tr>
        </table></td>
      </tr>
    </table></div></div>
</div>
<!-- body_eof //-->

<!-- footer //-->
<?php require(DIR_WS_INCLUDES . 'footer.php'); ?>
<!-- footer_eof //-->
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); 

function mirror_out ($field) {
global $csv_accum;
echo $field;
$field = strip_tags($field);
$field = preg_replace ("/,/","",$field);
if ($csv_accum=='') $csv_accum=$field; 
else 
{if (strrpos($csv_accum,chr(10)) == (strlen($csv_accum)-1)) $csv_accum .= $field;
else $csv_accum .= "," . $field; };
return;
}

?>