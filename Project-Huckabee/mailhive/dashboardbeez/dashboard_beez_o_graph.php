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

class dashboard_beez_o_graph extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_beez_o_graph()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_beez_o_graph';
        $this->module = 'dashboard_beez_o_graph';
        $this->version = '1.1'; // float value
        $this->required_mb_version = 2.1;
        $this->title = MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_DESC;
        //$this->removable = false; // can't be removed
        $this->enabled = ((MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS == 'True') ? true : false);
        $this->status_key = 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS';
        $this->documentation_root = 'http://www.mailbeez.com/documentation/dashboardbeez/';
        $this->documentation_key = $this->module;

        $this->admin_action_plugins_path = DIR_FS_CATALOG . 'mailhive/dashboardbeez/'; // default-path to include admin action plugins from
        $this->admin_action_plugins = '';

        $this->sort_order = MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_SORT_ORDER;
    }

    function getOutput()
    {

        $days = array();
        for ($i = 0; $i < MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP; $i++) {
            $days[date('Y-m-d', strtotime('-' . $i . ' days'))] = 0;
        }

        $days_sent_array = $days;


        $mailbeez_sent_query_sql = "select date_format(date_sent, '%Y-%m-%d') as dateday, count(autoemail_id) as sent_count
                                        from " . TABLE_MAILBEEZ_TRACKING . "
                                    where date_sub(curdate(), interval " . MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP . " day) <= date_sent
                                        " . MAILBEEZ_SIMULATION_SQL . "
                                        group by dateday";

        $mailbeez_sent_query = mh_db_query($mailbeez_sent_query_sql);
        while ($mailbeez_sent_cnt = mh_db_fetch_array($mailbeez_sent_query)) {
            $days_sent_array[$mailbeez_sent_cnt['dateday']] = $mailbeez_sent_cnt['sent_count'];
        }

        $days_sent_array = array_reverse($days_sent_array, true);

        $js_array_sent = '';
        foreach ($days_sent_array as $date => $sent) {
            $js_array_sent .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) * 1000) . ', ' . $sent . '],';
        }

        if (!empty($js_array_sent)) {
            $js_array_sent = substr($js_array_sent, 0, -1);
        }

        $chart_label_sent = mh_output_string(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_SENT_LINK);
        $chart_label_sent_link = mh_href_link(FILENAME_MAILBEEZ, 'module=report_track&app=load_app&app_path=report_track/report_track.php');


        $days_block_array = $days;


        $mailbeez_block_query_sql = "select date_format(date_block, '%Y-%m-%d') as dateday, count(autoemail_id) as block_count
                                        from " . TABLE_MAILBEEZ_BLOCK . "
                                    where date_sub(curdate(), interval " . MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP . " day) <= date_block
                                        " . MAILBEEZ_SIMULATION_SQL . "
                                        group by dateday";

        $mailbeez_block_query = mh_db_query($mailbeez_block_query_sql);
        while ($mailbeez_block_cnt = mh_db_fetch_array($mailbeez_block_query)) {
            $days_block_array[$mailbeez_block_cnt['dateday']] = $mailbeez_block_cnt['block_count'];
        }

        $days_block_array = array_reverse($days_block_array, true);

        $js_array_block = '';
        foreach ($days_block_array as $date => $blocked) {
            $js_array_block .= '[' . (mktime(0, 0, 0, substr($date, 5, 2), substr($date, 8, 2), substr($date, 0, 4)) * 1000) . ', ' . $blocked . '],';
        }

        if (!empty($js_array_block)) {
            $js_array_block = substr($js_array_block, 0, -1);
        }

        $chart_label_block = mh_output_string(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_BLOCK_LINK);
        $chart_label_block_link = mh_href_link(FILENAME_MAILBEEZ, 'module=report_block&app=load_app&app_path=report_block/report_block.php');

        ob_start();

        ?>

    <div id="WidgetTitle"><?php echo MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_TITLE?></div>
    <div id="WidgetSubTitle"><?php echo sprintf(MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_DESC, MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP); ?></div>

    <div id="beez_o_meter" style="width: 100%; height: 150px; margin-top: 10px;"></div>
    <script type="text/javascript">
        jQuery(function () {
            var plot_beez_o_meter_sent = [<?php echo $js_array_sent ?>];
            var plot_beez_o_meter_block = [<?php echo $js_array_block ?>];

            jQuery.plot(jQuery('#beez_o_meter'), [
                {
                    label: '<?php echo $chart_label_sent ?>',
                    data: plot_beez_o_meter_sent,
                    lines: { show: true, fill: false },
                    points: { show: true },
                    color: '#abd37f'
                },
                {
                    label: '<?php echo $chart_label_block ?>',
                    data: plot_beez_o_meter_block,
                    lines: { show: true, fill: false },
                    points: { show: true },
                    color: '#f8933c',
                }
            ], {
                xaxis: {
                    ticks: 7,
                    mode: 'time'
                },
                yaxis: {
                    ticks: 3,
                    min: 0
                },
                grid: {
                    backgroundColor: { colors: ['#fcfcfc', '#f0f0f0'] },
                    hoverable: true,
                    borderColor: '#888',
                    fontFamily: 'Trebuchet MS, Arial'
                },
                legend: {
                    labelFormatter: function(label, series) {
                        if (label == '<?php echo $chart_label_sent ?>')
                        {
                            return '<a href="<?php echo $chart_label_sent_link; ?>">' + label + '</a>';
                        }
                        if (label == '<?php echo $chart_label_block ?>')
                        {
                            return '<a href="<?php echo $chart_label_block_link; ?>">' + label + '</a>';
                        }
                    }
                }
            });
        });

        function showTooltip(x, y, contents) {
            jQuery('<div id="tooltip">' + contents + '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y + 5,
                left: x + 5,
                border: '1px solid #fdd',
                padding: '2px',
                backgroundColor: '#fee',
                fontFamily: 'Arial',
                fontSize: '10px',
                opacity: 0.80
            }).appendTo('body').fadeIn(200);
        }

        var monthNames = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];

        var previousPoint = null;
        jQuery('#beez_o_meter').bind('plothover', function (event, pos, item) {
            if (item) {
                if (previousPoint != item.datapoint) {
                    previousPoint = item.datapoint;

                    jQuery('#tooltip').remove();
                    var x = item.datapoint[0],
                            y = item.datapoint[1],
                            xdate = new Date(x);

                    showTooltip(item.pageX, item.pageY, y + ' for ' + monthNames[xdate.getMonth()] + '-' + xdate.getDate());
                }
            } else {
                jQuery('#tooltip').remove();
                previousPoint = null;
            }
        });
    </script>
    <?
        $output = ob_get_contents();
        ob_end_clean();

        return $output;
    }

    function check()
    {
        return defined('MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Show MailBeez Beez-O-Graph',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to show the MailBeez Beez-O-Graph on the dashboard? (recommended)',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Count this number of past days:',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP',
                                    'configuration_value' => '30',
                                    'configuration_description' => 'number of past days to use for calculation',
                                    'set_function' => ''
                               ));


        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_SORT_ORDER',
                                    'configuration_value' => '30',
                                    'configuration_description' => 'Sort order of display. Lowest is displayed first.',
                                    'set_function' => ''
                               ));
    }


    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_STATUS', 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_PASSED_DAYS_SKIP', 'MAILBEEZ_DASHBOARD_BEEZ_O_GRAPH_SORT_ORDER');
    }

}

?>
