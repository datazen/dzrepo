<?php
/*
  $Id: 2_ordertotals_index_blockright.php,v 1.0.0.0 2007/11/15 01:41:11 wa4u Exp $

  CRE Loaded, Open Source E-Commerce Solutions
  http://www.creloaded.com

  Copyright (c) 2008 CRE Loaded
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/
if(defined('ADMIN_BLOCKS_OT_STATUS') && ADMIN_BLOCKS_OT_STATUS == 'true'){
  require_once(DIR_WS_CLASSES . 'currencies.php');
   
  $currencies = new currencies();
  global $languages_id;

  //check Highest Order Number
  $highest_order_num = tep_db_fetch_array(tep_db_query("SELECT MAX(orders_id) AS highest_order_num FROM " . TABLE_ORDERS . ""));
  
  // today's
  $today = date('Y-m-d');
  
  $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE  o.date_purchased LIKE '" . $today . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "'");
  $ot_data = tep_db_fetch_array($ot_query);
  $todays_raw_total = $ot_data['total'];
  $todays_raw_count = $ot_data['count'];
  
  $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE  o.date_purchased LIKE '" . $today . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "' AND os.orders_status_id in (" . ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP . ")");
  $ot_data = tep_db_fetch_array($ot_query);
  $todays_approved_total = $ot_data['total'];
  $todays_approved_count = $ot_data['count'];

  if ($todays_raw_count > 0 && $todays_approved_count > 0) {
    $todays_approved_percent = ($todays_approved_count / $todays_raw_count) * 100;
  } else {
    $todays_approved_percent = 0;
  }
  
  
  //yesterday's total
  $yesterday = strtotime( '-1 days' ); // or, strtotime( 'yesterday' );
  $yesterday = date( 'Y-m-d', $yesterday );
  
  $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE  o.date_purchased LIKE '" . $yesterday . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "'");
  $ot_data = tep_db_fetch_array($ot_query);
  $yesterdays_raw_total = $ot_data['total'];
  $yesterdays_raw_count = $ot_data['count'];
  
  $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE  o.date_purchased LIKE '" . $yesterday . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "' AND os.orders_status_id in (" . ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP . ")");
  $ot_data = tep_db_fetch_array($ot_query);
  $yesterdays_approved_total = $ot_data['total'];
  $yesterdays_approved_count = $ot_data['count'];
  
  
  //month's total
  $this_month = date('Y-m-');
  
  $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE o.date_purchased LIKE '" . $this_month . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "'");
  $ot_data = tep_db_fetch_array($ot_query);
  $this_month_raw_total = $ot_data['total'];
  $this_month_raw_count = $ot_data['count'];
  
  $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE  o.date_purchased LIKE '" . $this_month . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "' AND os.orders_status_id in (" . ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP . ")");
  $ot_data = tep_db_fetch_array($ot_query);
  $this_month_approved_total = $ot_data['total'];
  $this_month_approved_count = $ot_data['count'];
  
  if ($this_month_raw_count > 0 && $this_month_approved_count > 0) {
    $this_month_approved_percent = ($this_month_approved_count / $this_month_raw_count) * 100;
  } else {
    $this_month_approved_percent = 0;
  }
  
  
  //last month's total
  if (strtolower(ADMIN_BLOCKS_OT_SHOW_LAST_MONTH) == 'true') {
    $last_month = strtotime( '-1 months' );
    $last_month = date( 'Y-m', $last_month );
    
    $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE o.date_purchased LIKE '" . $last_month . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "'");
    $ot_data = tep_db_fetch_array($ot_query);
    $last_month_raw_total = $ot_data['total'];
    $last_month_raw_count = $ot_data['count'];
    
    $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE  o.date_purchased LIKE '" . $last_month . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "' AND os.orders_status_id in (" . ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP . ")");
    $ot_data = tep_db_fetch_array($ot_query);
    $last_month_approved_total = $ot_data['total'];
    $last_month_approved_count = $ot_data['count'];
  }
  
  
  //year's total
  if (strtolower(ADMIN_BLOCKS_OT_SHOW_YTD) == 'true') {
    $this_year = date('Y');
    
    $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE o.date_purchased LIKE '" . $this_year . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "'");
    $ot_data = tep_db_fetch_array($ot_query);
    $ytd_raw_total = $ot_data['total'];
    $ytd_raw_count = $ot_data['count'];
    
    $ot_query = tep_db_query("SELECT SUM(ot.value) AS total, COUNT(*) AS count FROM " . TABLE_ORDERS_TOTAL . " ot, " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS . " os WHERE  o.date_purchased LIKE '" . $this_year . "%' AND ot.orders_id = o.orders_id AND ot.class = 'ot_total' AND os.orders_status_id = o.orders_status AND os.language_id = '" . $_SESSION['languages_id'] . "' AND os.orders_status_id in (" . ADMIN_BLOCKS_OT_APPROVED_ORDER_STATUS_MAP . ")");
    $ot_data = tep_db_fetch_array($ot_query);
    $ytd_approved_total = $ot_data['total'];
    $ytd_approved_count = $ot_data['count'];
  }
?>
<!-- begin ordertotal -->
<div class="col-md-6">
  <!-- begin panel -->
  <div class="panel panel-inverse" data-sortable-id="table-basic-4">
    <div class="panel-heading">
      <div class="panel-heading-btn">
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-default" data-click="panel-expand"><i class="fa fa-expand"></i></a>
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-success" data-click="panel-reload"><i class="fa fa-repeat"></i></a>
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-warning" data-click="panel-collapse"><i class="fa fa-minus"></i></a>
        <a href="javascript:;" class="btn btn-xs btn-icon btn-circle btn-danger" data-click="panel-remove"><i class="fa fa-times"></i></a>
      </div>
      <h4 class="panel-title"><?php echo BLOCK_TITLE_OT; ?></h4>
    </div>
    <div class="panel-body">
      <ul class="list-unstyled">
        <li><?php echo BLOCK_CONTENT_OT_HIGHEST_ORDER_NUM . $highest_order_num['highest_order_num'];?></li>
        <li><?php echo BLOCK_CONTENT_OT_TODAY_SO_FAR . $currencies->format($todays_raw_total) . ' (' . $currencies->format($todays_approved_total) . ')';?></li>
        <li><?php echo BLOCK_CONTENT_OT_YESTERDAYS_ORDERS . $currencies->format($yesterdays_raw_total) . ' (' . $currencies->format($yesterdays_approved_total) . ')';?></li>
        <li><?php echo BLOCK_CONTENT_OT_MONTH_DATE_TOTAL . $currencies->format($this_month_raw_total) . ' (' . $currencies->format($this_month_approved_total) . ')';?></li>
        <?php
        if (strtolower(ADMIN_BLOCKS_OT_SHOW_LAST_MONTH) == 'true') {
          ?>
          <li><?php echo BLOCK_CONTENT_OT_LAST_MONTH_TOTAL . $currencies->format($last_month_raw_total) . ' (' . $currencies->format($last_month_approved_total) . ')';?></li>
          <?php                                                                                                                                           
        }
        if (strtolower(ADMIN_BLOCKS_OT_SHOW_YTD) == 'true') {
          ?>
          <li><?php echo BLOCK_CONTENT_OT_YEAR_DATE_TOTAL . $currencies->format($ytd_raw_total) . ' (' . $currencies->format($ytd_approved_total) . ')';?></li>
          <?php
        }
        ?>
        <li><?php echo BLOCK_CONTENT_OT_PERCENT_APPROVED . '<br>' . BLOCK_CONTENT_OT_TODAY . tep_round($todays_approved_percent, 2) . '%<br>' . BLOCK_CONTENT_OT_MONTH . tep_round($this_month_approved_percent, 2) . '%';?></li>
        <b><a href="#" onclick="window.open('<?php echo tep_href_link('index_block_preference.php', '', 'SSL');?>', 'popupWindow', 'toolbar=no,location=no,directories=no,status=no,menubar=no,scrollbars=no,resizable=yes,copyhistory=no,width=400,height=300,top=200,left=300');"><?php echo BLOCK_CONTENT_OT_PREFERENCE; ?></a></b>
      </ul>
    </div>
  </div>
</div>
<!-- end ordertotal -->
<?php
}
?>