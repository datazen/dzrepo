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

class dashboard_event_log extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_event_log()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_event_log';
        $this->module = 'dashboard_event_log';
        $this->version = '1.1'; // float value
        $this->required_mb_version = 2.2;
        $this->title = MAILBEEZ_DASHBOARD_EVENT_LOG_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_EVENT_LOG_DESCRIPTION;
        $this->status_key = 'MAILBEEZ_DASHBOARD_EVENT_LOG_STATUS';

        if (defined('MAILBEEZ_DASHBOARD_EVENT_LOG_STATUS')) {
            $this->sort_order = MAILBEEZ_DASHBOARD_EVENT_LOG_SORT_ORDER;
            $this->enabled = (MAILBEEZ_DASHBOARD_EVENT_LOG_STATUS == 'True');
        }
    }

    function getOutput()
    {
        $report_link = mh_href_link(FILENAME_MAILBEEZ, 'module=report_event_log&app=load_app&app_path=report_event_log/report_event_log.php');

        $output = '<div id="WidgetTitle">' . MAILBEEZ_DASHBOARD_EVENT_LOG_TITLE . '</div>
        <div id="WidgetSubTitle">' . MAILBEEZ_DASHBOARD_EVENT_LOG_TEXT . ', <a href="' . $report_link . '" >' . MAILBEEZ_DASHBOARD_EVENT_LOG_LINK_REPORT_TEXT . '</a> </div>';
        $output .= '<table style="margin-top: 5px;" border="0" width="100%" cellspacing="0" cellpadding="4">' .
                   '  <tr class="dataTableHeadingRow">' .
                   '    <td class="dataTableHeadingContent">' . MAILBEEZ_DASHBOARD_EVENT_LOG_EVENT . '</td>' .
                   '    <td class="dataTableHeadingContent" align="right">' . MAILBEEZ_DASHBOARD_EVENT_LOG_DATE . '</td>' .
                   '  </tr>';

        $report_query_raw = "select * from " . TABLE_MAILBEEZ_EVENT_LOG . " where event_type like 'MAILHIVE_%' order by batch_id DESC limit 6";

        $report_query = mh_db_query($report_query_raw);

        while ($report = mh_db_fetch_array($report_query)) {
            $output .= '  <tr class="dataTableRow" onmouseover="rowOverEffect(this);" onmouseout="rowOutEffect(this);">' .
                       '    <td class="dataTableContent"><a href="' . $report_link . '" >' . $report['event_type'] . '</a></td>' .
                       '    <td class="dataTableContent" align="right" style="white-space: nowrap;">' . $report['log_date'] . '</td>' .
                       '  </tr>';
        }

        $output .= '  </tr>' .
                   '</table>';

        $output .= '<a href="' . $report_link . '" >' . MAILBEEZ_DASHBOARD_EVENT_LOG_LINK_REPORT . '</a>';

        return $output;
    }

    function check()
    {
        return defined('MAILBEEZ_DASHBOARD_EVENT_LOG_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Enable Latest MailBeez Event Log Overview Module',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_EVENT_LOG_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to show the latest MailBeez Events on the dashboard?',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_EVENT_LOG_SORT_ORDER',
                                    'configuration_value' => '35',
                                    'configuration_description' => 'Sort order of display. Lowest is displayed first.',
                                    'set_function' => ''
                               ));
    }

    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_EVENT_LOG_STATUS', 'MAILBEEZ_DASHBOARD_EVENT_LOG_SORT_ORDER');
    }

}

?>
