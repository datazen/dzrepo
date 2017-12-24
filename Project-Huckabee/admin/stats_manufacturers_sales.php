<?php
/*
    Contribution Name: Manufacturer Sales Report
    Contribution Version: 2.3

Creation of this file 
Author Name: Robert Heath
Author E-Mail Address: robert@rhgraphicdesign.com
Athor Website: http://www.rhgraphicdesign.com
Donations: www.paypal.com
Donations Email: robert@rhgraphicdesign

Modifications on PHP file 
    Date: 28/05/07
    Name: Cyril Jacquenot
    What's modified?
      * fixed localizations:
        * use of currency class, to use global currency settings (€, £, $)
        * use of PHP server date/time format (fr_FR, en-EN, ...)         
      * fixed: code merging
        * before: copy/paste had been used
        * now: 
          * same code appears once
          * sql requests have completely been rewritten
          * sql requests have been speeded up
          * html code renewed (html tags are now OK) 
          * better display of pages                                       
      * added: PHP file including functions only : stats_manufacturers_sales_functions.php
      * added: some styles in "printer.css" and "stylesheet.css"
      * added: possibility to see sold products :
        * for all manufacturers                  
        * by one manufacturer                  
        * by all the customers of one manufacturer                  
        * by every customers of one manufacturer              
        * by one customer of one manufacturer
        * for each request, there is the possibility to print a simple page                          
        * when listing products, there is now a link to the product detail  
    What's need to be fixed?
      * french date format gestion with SpiffyCal
                                           
  Donations: www.paypal.com with email: cyril.jacquenot@laposte.net

Released under the GNU General Public License
*/

  require('includes/application_top.php');

  require(DIR_WS_CLASSES . 'currencies.php');
  $currencies = new currencies();
  
  require(DIR_WS_FUNCTIONS . 'stats_manufacturers_sales_functions.php');

  // global variables
  $manufacturer_id = "";
  $customer_id = "";
  $manufacturer_name = "";
  $customer_name = "";

  if (isset($_GET['start_date'])) {
    $start_date = $_GET['start_date'];
  } else if (isset($_POST['start_date'])) {
    $start_date = $_POST['start_date'];
  } else {
    $start_date = (date('Y-m-01'));
  }

  if (isset($_GET['end_date'])) {
    $end_date = $_GET['end_date'];
  } else if (isset($_POST['end_date'])) {
    $end_date = $_POST['end_date'];
  } else {
    $end_date = (date('Y-m-d'));
  }
 
  // set printer-friendly toggle
  (tep_db_prepare_input($_GET['print']=='yes')) ? $print=true : $print=false;
  (tep_db_prepare_input($_GET['customer_only']=='yes')) ? $customer_only=true : $customer_only=false;
  // set inversion toggle
  
  if (!empty($_GET['mID']))    {$manufacturer_id = $_GET['mID'];}
  if (!empty($_GET['cID']))    {$customer_id = $_GET['cID'];}
  if (!empty($_GET['mName']))  {$manufacturer_name = $_GET['mName'];}
  if (!empty($_GET['cName']))  {$customer_name = $_GET['cName'];}

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
<body onload="<?php if($print) {   echo 'window.print()';}  ?>">
   <link rel="stylesheet" type="text/css" href="includes/javascript/spiffyCal/spiffyCal_v2_1.css">
    <script language="JavaScript" src="includes/javascript/spiffyCal/spiffyCal_v2_1.js"></script>
    <script language="javascript">
      <!--
      var startDate = new ctlSpiffyCalendarBox("startDate", "date_range", "start_date","btnDate1","<?php echo $start_date; ?>",scBTNMODE_CUSTOMBLUE);
      var endDate = new ctlSpiffyCalendarBox("endDate", "date_range", "end_date","btnDate2","<?php echo $end_date; ?>",scBTNMODE_CUSTOMBLUE);
      //-->
    </script>
    <div id="spiffycalendar" class="text"></div>
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
$td_page_heading = "<td class='pageHeading'>";
if ($print) {$td_page_heading .= "<big>".STORE_NAME . "</big><br>";}
if (!$manufacturer_id) {
  $td_page_heading .= HEADING_TITLE; 
}
else {
  $td_page_heading .= HEADING_TITLE_REPORT_MANUFACTURER . $manufacturer_name; 
}
$td_page_heading .= "</td>";

if(!$print) {
// =======================================================================================================================================================================    
// TABLES : NOT IN PRINTING MODE
// =======================================================================================================================================================================    
?>
<!-- header //-->

<!-- header_eof //-->

<table border="0" width="100%" cellspacing="2" cellpadding="2">
  <tr>


    <td width="100%" valign="top">
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <td>
            <table border="0" width="100%" cellspacing="0" cellpadding="0">
              <tr>
                <?php echo $td_page_heading;?> 
                <td class="smallText" align="right">
                  <a href="<?php echo tep_href_link(FILENAME_STATS_MANUFACTURERS,'print=yes&start_date=' . $start_date . '&end_date=' . $end_date . (tep_not_null($manufacturer_id) ? '&mID=' . $manufacturer_id : ''),'NONSSL');?>" target="print"><?php echo tep_image_button('button_print_page.gif', IMAGE_BUTTON_PRINT);?></a>
                  <?php 
                  if (isset($manufacturer_id) && tep_not_null($manufacturer_id)) {
                    echo '&nbsp; <a href="' . tep_href_link(FILENAME_STATS_MANUFACTURERS,'start_date=' . $start_date . '&end_date=' . $end_date,'NONSSL') . '">' . tep_image_button('button_back.gif', IMAGE_BACK) . '</a> &nbsp; ';
                  }
                  ?>
                </td>
              </tr>
            </table>
          </td>
        </tr>
        <tr>
          <td>
            <!-- date range table -->
            <table border="0" width="100%" cellspacing="0" cellpadding="2">
              <tr class="main">
                <td align="left">
                  <?php echo tep_draw_form('date_range',FILENAME_STATS_MANUFACTURERS, tep_get_all_get_params(array("start_date", "end_date")), 'post'); ?> 

                    <?php echo ENTRY_STARTDATE; ?> &nbsp;
                    <script language="javascript">startDate.writeControl();startDate.dateFormat='yyyy-MM-dd';</script>
                    <?php echo ENTRY_TODATE; ?> &nbsp;    
                    <script language="javascript">endDate.writeControl();endDate.dateFormat='yyyy-MM-dd';</script>

                    <input type="submit" value="<?php echo ENTRY_SUBMIT; ?>">

                  </form>
                </td>
              </tr>
              <tr class="main">
                <td class="main" align="left"><?php echo HEADING_TITLE_PERIOD . ": " . $start_date .  " - " . $end_date; ?> </td>
              </tr>
            </table>
            <!-- end date range table -->

<?php 
    if (!$manufacturer_id) { 
        
    } // end if empty_mID 
    ?>
          </td>

        </tr>
        <tr>
          <td>
<?php 
} else {
// =======================================================================================================================================================================    
// TABLES : PRINTING MODE
// =======================================================================================================================================================================    
?>
<table border="0" width="100%" cellspacing="0" cellpadding="2">
    <tr>
      <td>
      <table border="0" width="100%" cellspacing="0" cellpadding="0">
        <tr>
          <?php echo $td_page_heading; ?>
        </tr>
        <tr>
          <td>
          <table>
            <tr>
                <td class="smallText" align="left"><?php echo HEADING_TITLE_PERIOD . ": ";  ?></td>
                <td width="8"></td>
                <td class="smallText" align="left">
                <?php 
                if (!$manufacturer_id) { 
                    echo $start_date." - ".$end_date;
                } else {
                    echo $start_date." - ".$end_date;
                }?>
                </td>
            </tr>
          </table>
        </td>
      </tr>
        <tr>
          <td>
<?php
}
?>  

  <table border="0" width="100%" cellspacing="0" cellpadding="0">
    <tr>
    <td valign="top">
      <?php if (!$manufacturer_id) { 
      // =======================================================================================================================================================================    
      // mID == "" => list all the ordered products by manufacturers
      // =======================================================================================================================================================================    
      ?>
      <table border="0" width="100%" cellspacing="0" cellpadding="2" class="data-table">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" nowrap><?php echo TABLE_HEADING_MANUFACTURERS_NAME; ?></td>
          <td class="dataTableHeadingContent" align="right" nowrap><?php echo TABLE_TOTAL_PRODUCTS; ?></td>
          <td class="dataTableHeadingContent" align="right" nowrap><?php echo TABLE_TOTAL_SALES; ?></td>
        </tr>
        <?php
        $manufacturers_query = tep_db_query(getMySQLraw("m"));
        while ($manufacturers = tep_db_fetch_array($manufacturers_query)) {
          $products_quantity = $manufacturers['sum_pq'];
          $final_price = $manufacturers['sum_fp'];
   
          if(!$print) {
            $url_param = 'mID=' . $manufacturers['manufacturers_id'] . '&mName=' . urlencode($manufacturers['manufacturers_name']) . "&start_date=$start_date&end_date=$end_date";
          ?>
            <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_STATS_MANUFACTURERS, $url_param, 'NONSSL'); ?>'">
          <?php
          } else { // printing mode
          ?>
            <tr class="dataTableRow">
          <?php 
          }
          ?>
          <td class="dataTableContent" width="100%"><?php echo $manufacturers['manufacturers_name']; ?></td>
          <td class="dataTableContent" align="right"><?php echo $products_quantity; ?></td>
          <td class="dataTableContent" align="right"><?php echo $currencies->format($final_price); ?></td>
        </tr>
        <?php
            $total_quantity = $total_quantity + $products_quantity;
            $total_sales = ($total_sales + $final_price);
            }
        ?>
        <tr>
          <td class="dataTableTotalRow" colspan="1" align="right"><?php echo ENTRY_TOTAL; ?></td>
          <td class="dataTableTotalRow" align="right"><?php echo $total_quantity; ?></td>
          <td class="dataTableTotalRow" align="right"><?php echo $currencies->format($total_sales); ?></td>
        </tr>
      </table>
    <?php
   } else {
    // =======================================================================================================================================================================    
    // mID != "" => list all the ordered products for this manufacturer_id
    // =======================================================================================================================================================================

      if (!$customer_only) {
?>
      <table border="0" width="100%" cellspacing="0" cellpadding="2">
        <tr class="dataTableHeadingRow">
          <td class="dataTableHeadingContent" nowrap width="100%"><?php echo TABLE_CUSTOMER_NAME; ?></td>
          <td class="dataTableHeadingContent" align="center" nowrap><?php echo TABLE_ORDER_PURCHASED; ?></td>
          <td class="dataTableHeadingContent" align="right" nowrap><?php echo TABLE_TOTAL_PRODUCTS; ?></td>
          <td class="dataTableHeadingContent" align="right" nowrap><?php echo TABLE_PRODUCT_REVENUE; ?></td>
        </tr>
        <?php
        $total_sales = 0;
        $total_quantity = 0;
        $man_customers_query = tep_db_query(getMySQLraw("c"));
        while ($man_cust_products = tep_db_fetch_array($man_customers_query)) {
          $products_quantity = $man_cust_products['sum_pq'];
          $final_price = $man_cust_products['sum_fp'];
          if (!$print) {
            $url_param = "cName=".$man_cust_products['customers_name']."&cID=".$man_cust_products['customers_id']."&mName=".urlencode($manufacturer_name)."&mID=$manufacturer_id&start_date=$start_date&end_date=$end_date";
          ?>
          
                <tr class="dataTableRow" onMouseOver="rowOverEffect(this)" onMouseOut="rowOutEffect(this)" onClick="document.location.href='<?php echo tep_href_link(FILENAME_STATS_MANUFACTURERS, $url_param, 'NONSSL'); ?>'">
                
          <?php
          } else {?>
                <tr class="dataTableRow">
          <?php
          }
          ?>
          <td class="dataTableContent" width="100%"><?php echo $man_cust_products['customers_name']; ?></td>
          <td class="dataTableContent" width="100%" align="center"><?php echo strftime(DATE_FORMAT_SHORT, strtotime($man_cust_products['dp'])); ?></td>
          <td class="dataTableContent" align="right"><?php echo $products_quantity; ?></td>
          <td class="dataTableContent" align="right"><?php echo $currencies->format($final_price); ?></td>
        </tr>
        <?php
            $total_quantity = $total_quantity + $products_quantity;
            $total_sales = ($total_sales + $final_price);
            }?>
        <tr class="dataTableRow">
          <td class="dataTableTotalRow" align="left"><?php echo ALL_CUSTOMERS; ?></td>
          <td class="dataTableTotalRow" align="right"><?php echo ENTRY_TOTAL; ?></td>
          <td class="dataTableTotalRow" align="right"><?php echo $total_quantity; ?></td>
          <td class="dataTableTotalRow" align="right"><?php echo $currencies->format($total_sales); ?></td>
        <tr><td>&nbsp;</td></tr>
        </tr><?php
        if (!$print) {
          $url_param = "mName=".urlencode($manufacturer_name)."&mID=$manufacturer_id&start_date=$start_date&end_date=$end_date";?>
          <tr>
            <td colspan="4"><?php
              echo "<a href='".tep_href_link(FILENAME_STATS_MANUFACTURERS, 'cID=all&'.$url_param, 'NONSSL')."'>".SHOW_ALL_ORDERED_PRODUCTS."</a>";?>
            </td>
          </tr>
          <tr>
            <td colspan="4"><?php
              echo "<a href='".tep_href_link(FILENAME_STATS_MANUFACTURERS, 'cID=all_by_once&'.$url_param, 'NONSSL')."'>".SHOW_ALL_ORDERED_PRODUCTS_FOR_ALL_CUSTOMERS."</a>";?>
            </td>
          </tr><?php
        }?>
      </table><?php
      }

      require(DIR_WS_CLASSES . 'order.php');

      if ($customer_id) {
        if ($customer_id == "all_by_once") {
            $man_customers_list_query = tep_db_query(getMySQLraw("c"));
            while ($man_cust_list_products = tep_db_fetch_array($man_customers_list_query)) {
                $customer_id = $man_cust_list_products['customers_id'];
                echo getCustomerProductsBlock();
          }
          $customer_id = "all_by_once";
        }
        else {
          echo getCustomerProductsBlock();
        }
      }
}
?>
      </td>
      </tr>
    </table>
    </td>
    <!-- body_text_eof //-->
    </tr>
    </table>
  </td>
  </tr>
</table>
<!-- body_eof //-->
<!-- footer //-->
<?php 
if(!$print) {
  require(DIR_WS_INCLUDES . 'footer.php');
} else {
  echo "<hr><span class='strongText'>".PRINTED_ON . "</span><span class='smallText'>" .strftime(DATE_TIME_FORMAT) . "</span>";
} 
?>
<!-- footer_eof //-->
</div></div></div>
</body>
</html>
<?php require(DIR_WS_INCLUDES . 'application_bottom.php'); ?>
