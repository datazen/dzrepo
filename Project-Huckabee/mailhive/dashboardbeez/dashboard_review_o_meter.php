<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/dashboardbeez.php');

class dashboard_review_o_meter extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_review_o_meter()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_review_o_meter';
        $this->module = 'dashboard_review_o_meter';
        $this->version = '1.2'; // float value
        $this->required_mb_version = 2.1;
        $this->title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_REVIEW_O_METER_DESC;
        //$this->removable = false; // can't be removed
        $this->enabled = ((MAILBEEZ_DASHBOARD_REVIEW_O_METER_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_STATUS';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/dashboardbeez/';
        $this->documentation_key = $this->module;

        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/dashboardbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = '';

        $this->sort_order = MAILBEEZ_DASHBOARD_REVIEW_O_METER_SORT_ORDER;
    }

    function dbdate($day)
    {
        $rawtime = strtotime(-1 * (int)$day . " days");
        $ndate = date("Ymd", $rawtime);
        return $ndate;
    }

    function getOutput()
    {

        // get the number of customers with order

        define('MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_1', 6 * MAILBEEZ_DASHBOARD_REVIEW_O_METER_FACTOR);
        define('MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_2', 14 * MAILBEEZ_DASHBOARD_REVIEW_O_METER_FACTOR);
        define('MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_3', 20 * MAILBEEZ_DASHBOARD_REVIEW_O_METER_FACTOR);


        $review_last_days = MAILBEEZ_DASHBOARD_REVIEW_O_METER_PASSED_DAYS_SKIP;
        $review_delay_days = 30; // delay between order and review
        $review_delay_days = (defined('MAILBEEZ_REVIEW_PASSED_DAYS')) ? MAILBEEZ_REVIEW_PASSED_DAYS
                : $review_delay_days;
        $review_delay_days = (defined('MAILBEEZ_REVIEW_ADVANCED_STEP1_DELAY')) ? MAILBEEZ_REVIEW_ADVANCED_STEP1_DELAY
                : $review_delay_days;
        $review_rate_max = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_3;

        $status = MAILBEEZ_DASHBOARD_REVIEW_O_METER_ORDER_STATUS_ID;

        $date_skip = $this->dbdate($review_last_days);
        $date_passed = $this->dbdate(-1);

        $date_skip_orders = $this->dbdate($review_last_days + $review_delay_days);
        $date_passed_orders = $this->dbdate(-1 + $review_delay_days);

        $customers_cnt_query_raw = "select count(distinct customers_id) as customers_cnt
                  from " . TABLE_ORDERS . " o, " . TABLE_ORDERS_STATUS_HISTORY . " s
                  where o.orders_id = s.orders_id
                    and o.orders_status = s.orders_status_id
                    and s.orders_status_id = '" . (int)$status . "'
                    and s.date_added <= '" . $date_passed_orders . "'
                    and s.date_added > '" . $date_skip_orders . "'";

        $customers_cnt_query = mh_db_query($customers_cnt_query_raw);
        $customers_cnt = mh_db_fetch_array($customers_cnt_query);

        // get the number of customers given a review
        $review_cnt_query_sql = "select count(distinct customers_id)  as review_cnt
                                from " . TABLE_REVIEWS . "
                             where date_added <= '" . $date_passed . "'
                                and date_added > '" . $date_skip . "' ";
        $review_cnt_query = mh_db_query($review_cnt_query_sql);
        $review_cnt = mh_db_fetch_array($review_cnt_query);

        $reviews_count = $review_cnt['review_cnt'];
        $customers_count = $customers_cnt['customers_cnt'];

        if ($customers_count > 0) {
            $review_rate = 100 * $reviews_count / $customers_count;
        } else {
            $review_rate = 0;
        }

        $review_show_fix_hint = true;

        if ($review_rate == 0) {
            $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_1;
            $rate_color = '#f8933c';
            $rate_color_text = '#b85c0c';
        }
        if ($review_rate > 0) {
            $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_2;
            $rate_color = '#f8933c';
            $rate_color_text = '#b85c0c';
        }
        if ($review_rate > MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_1) {
            $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_3;
            $rate_color = '#fff7a5';
            $rate_color_text = '#aba032';
        }
        if ($review_rate > MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_2) {
            $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_4;
            $rate_color = '#abd37f';
            $rate_color_text = '#597c32';
            $review_show_fix_hint = false;
        }
        if ($review_rate >= MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_3) {
            $review_rate_title = MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_MSG_5;
            $rate_color = '#abd37f';
            $rate_color_text = '#597c32';
            $review_show_fix_hint = false;
        }
        ob_start();


        // http://code.google.com/p/jsgauge/wiki/API
        ?>
    <div id="WidgetTitle"><?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_TITLE?></div>
    <div id="WidgetSubTitle"><?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_DESC; ?></div>

    <?php if (MAILBEEZ_VERSION < 2.3): ?>
    <!--[if IE]>
    <script type="text/javascript"
            src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/dashboardbeez/dashboard_winback_o_meter/js/excanvas.min.js"></script><![endif]-->
    <script type="text/javascript"
            src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/dashboardbeez/dashboard_review_o_meter/js/gauge.js"></script>
    <script type="text/javascript"
            src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/dashboardbeez/dashboard_review_o_meter/js/jquery.gauge.js"></script>
    <?php endif; ?>

    <div style="float: left; z-index: 5; position: relative; height: 70px; margin-top: 7px;">
        <canvas id="review" width="130" height="130" style="margin-left: 20px;"></canvas>
    </div>

    <script type="text/javascript">
        jQuery(document).ready(function() {
            jQuery("#review")
                    .gauge({
                        colorOfText: '#303030',
                        colorOfWarningText: '#00ad07',
                        colorOfFill: [ '#888', '#333', '#ddd', '#fcfcfc' ],
                        colorOfPointerFill: '#909090',
                        colorOfPointerStroke: '#909090',
                        colorOfCenterCircleFill : '#909090',
                        colorOfCenterCircleStroke: '#909090',
                        majorTicks: 1,
                        minorTicks: 1,
                        min: 0,
                        max: <?php echo $review_rate_max; ?>,
                        label: 'RATE',
                        unitsLabel: '%',
                        bands: [
                            {color: "#f8933c", from: 0, to: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_1; ?>},
                            {color: "#fff7a5", from: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_1; ?>, to: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_2; ?>},
                            {color: "#abd37f", from: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_2; ?>, to: <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_LIMIT_3; ?>}
                        ]
                    })
        });
        setTimeout('jQuery("#review").gauge("setValue", <?php echo $review_rate; ?>)', 300);
    </script>
    <div style="margin-left: 0px; border: 0px solid red; position: relative; z-index: 2;min-height: 130px;">

        <div style="font-family: Trebuchet MS; font-size: 11px; font-weight: bold; color: #363636; margin-left: 170px; margin-top: 27px;">
            <?php echo sprintf(MAILBEEZ_DASHBOARD_REVIEW_O_METER_RATE_TEXT, $review_rate)?>
        </div>

        <div style=" margin-top: 10px;position:relative; background-color: <?php echo $rate_color; ?>; padding: 7px; text-align: center; color: <?php echo $rate_color_text; ?>; font-weight: bold; font-size: 14px;"><?php echo $review_rate_title; ?></div>

        <?php if ($review_show_fix_hint) { ?>
        <div style="margin-left: 170px; margin-top: 12px; font-family: Trebuchet MS; font-size: 11px; font-weight: bold; color: #363636;">
            <?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_FIX_TEXT;?>
            <br/>
            <a href="http://www.mailbeez.com/documentation/tutorials/increase-seo-and-revenue-with-customer-reviews/<?php echo (defined('MH_LINKID_1')
                    ? MH_LINKID_1 : ''); ?>"
               target="_blank"><?php echo MAILBEEZ_DASHBOARD_REVIEW_O_METER_FIX_TEXT_LINK;?></a>

        </div>
        <?php }?>


    </div>
    <div style="position: relative; bottom: 3px; font-family: Trebuchet MS; font-size: 11px; font-weight: normal; color: #898989">
        <?php echo sprintf(MAILBEEZ_DASHBOARD_REVIEW_O_METER_STATS_TEXT, $review_last_days, $reviews_count, $customers_count, $review_delay_days); ?>
    </div>

    <?
        $output = ob_get_contents();
        ob_end_clean();
        /*
        *

           <link rel="stylesheet" type="text/css" href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/dashboardbeez/dashboard_review_o_meter/css/css.css">
           <link rel="stylesheet" href="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/dashboardbeez/dashboard_review_o_meter/css/gauge_screen.css" type="text/css"/>
           <!--[if IE]><script type="text/javascript" language="javascript" src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/dashboardbeez/dashboard_review_o_meter/js/excanvas.min.js"></script><![endif]-->
           <script language="javascript" type="text/javascript" src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/dashboardbeez/dashboard_review_o_meter/js/jQueryRotateCompressed.2.1.js"></script>
           <script language="javascript" type="text/javascript" src="<?php echo MH_CATALOG_SERVER . DIR_WS_CATALOG ?>/mailhive/dashboardbeez/dashboard_review_o_meter/js/jgauge-0.3.0.a3.js"></script>

           <div id="jGaugeDemo" class="jgauge"></div>

           <script type="text/javascript">
               var myGauge = new jGauge(); // Create a new jGauge.
               myGauge.id = 'jGaugeDemo'; // Link the new jGauge to the placeholder DIV.

               // This function is called by jQuery once the page has finished loading.
               $(document).ready(function() {
                   myGauge.init(); // Put the jGauge on the page by initialising it.
               });


               myGauge.setValue(7.35);
           </script>

        *
        *
        */


        return $output;
    }

    function check()
    {
        return defined('MAILBEEZ_DASHBOARD_REVIEW_O_METER_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Show MailBeez Review-O-Meter',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to show the MailBeez Review-O-Meter on the dashboard? (recommended)',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Set Order Status',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_ORDER_STATUS_ID',
                                    'configuration_value' => '3',
                                    'configuration_description' => 'Set the status of orders to identify as dispatched',
                                    'set_function' => 'mh_cfg_pull_down_order_statuses(',
                                    'use_function' => 'mh_get_order_status_name'
                               ));

        mh_insert_config_value(array('configuration_title' => 'Count this number of past days:',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_PASSED_DAYS_SKIP',
                                    'configuration_value' => '30',
                                    'configuration_description' => 'number of past days to use for calculation',
                                    'set_function' => ''
                               ));

        mh_insert_config_value(array('configuration_title' => 'Industry Factor',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_FACTOR',
                                    'configuration_value' => '1',
                                    'configuration_description' => 'Adjust the scale to your industry',
                                    'set_function' => ''
                               ));


        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_SORT_ORDER',
                                    'configuration_value' => '10',
                                    'configuration_description' => 'Sort order of display. Lowest is displayed first.',
                                    'set_function' => ''
                               ));
    }


    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_REVIEW_O_METER_STATUS', 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_ORDER_STATUS_ID', 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_PASSED_DAYS_SKIP', 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_FACTOR', 'MAILBEEZ_DASHBOARD_REVIEW_O_METER_SORT_ORDER');
    }

}

?>
