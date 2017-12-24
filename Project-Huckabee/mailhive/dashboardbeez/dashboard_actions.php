<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

define('MAILBEEZ_DASHBOARD_ACTIONS_CONTENT', '
    <div id="WidgetTitle">' . MAILBEEZ_DASHBOARD_ACTIONS_TITLE . '</div>
    <div id="WidgetSubTitle">' . MAILBEEZ_DASHBOARD_ACTIONS_TEXT . '</div>
	<ul class="actions">
		<li class="actions_item item_mailbeez"><a href="' . mh_href_link(FILENAME_MAILBEEZ, 'tab=mailbeez') . '"><div class="actions_item_img"></div></a><div class="actions_item"><a href="' . mh_href_link(FILENAME_MAILBEEZ, 'tab=mailbeez') . '"><b>' . MH_TAB_MAILBEEZ . '</b></a><br>' . MAILBEEZ_DASHBOARD_ACTIONS_MAILBEEZ_TEXT . '</div></li>
		<li class="actions_item item_filterbeez"><a href="' . mh_href_link(FILENAME_MAILBEEZ, 'tab=filterbeez') . '"><div class="actions_item_img"></div></a><div class="actions_item"><a href="' . mh_href_link(FILENAME_MAILBEEZ, 'tab=filterbeez') . '"><b>' . MH_TAB_FILTER . '</b></a><br>' . MAILBEEZ_DASHBOARD_ACTIONS_FILTER_TEXT . '</div></li>
		<li class="actions_item item_reportbeez"><a href="' . mh_href_link(FILENAME_MAILBEEZ, 'tab=reportbeez') . '"><div class="actions_item_img"></div></a><div class="actions_item"><a href="' . mh_href_link(FILENAME_MAILBEEZ, 'tab=reportbeez') . '"><b>' . MH_TAB_REPORT . '</b></a><br>' . MAILBEEZ_DASHBOARD_ACTIONS_REPORT_TEXT . '</div></li>
		<li class="actions_item item_configbeez"><a href="' . mh_href_link(FILENAME_MAILBEEZ, 'tab=configbeez') . '"><div class="actions_item_img"></div></a><div class="actions_item"><a href="' . mh_href_link(FILENAME_MAILBEEZ, 'tab=configbeez') . '"><b>' . MH_TAB_CONFIGURATION . '</b></a><br>' . MAILBEEZ_DASHBOARD_ACTIONS_CONFIGURATION_TEXT . '</div></li>
	</ul>');

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/dashboardbeez.php');

class dashboard_actions extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_actions()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_actions';
        $this->module = 'dashboard_actions';
        $this->version = '1.2'; // float value
        $this->required_mb_version = 2.1;
        $this->title = MAILBEEZ_DASHBOARD_ACTIONS_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_ACTIONS_DESCRIPTION;
        $this->status_key = 'MAILBEEZ_DASHBOARD_ACTIONS_STATUS';

        if (defined('MAILBEEZ_DASHBOARD_ACTIONS_STATUS')) {
            $this->sort_order = MAILBEEZ_DASHBOARD_ACTIONS_SORT_ORDER;
            $this->enabled = (MAILBEEZ_DASHBOARD_ACTIONS_STATUS == 'True');
        }
    }

    function getOutput()
    {
        $output = '<link rel="stylesheet" type="text/css" media="print, projection, screen" href="' . MH_CATALOG_SERVER . DIR_WS_CATALOG . '/mailhive/dashboardbeez/dashboard_actions/dashboard_actions.css" >';
        $output .= MAILBEEZ_DASHBOARD_ACTIONS_CONTENT;

        return $output;
    }

    function check()
    {
        return defined('MAILBEEZ_DASHBOARD_ACTIONS_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Show MailBeez Actions',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_ACTIONS_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to show the MailBeez Actions on the dashboard? (recommended)',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_ACTIONS_SORT_ORDER',
                                    'configuration_value' => '5',
                                    'configuration_description' => 'Sort order of display. Lowest is displayed first.',
                                    'set_function' => ''
                               ));
    }

    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_ACTIONS_STATUS', 'MAILBEEZ_DASHBOARD_ACTIONS_SORT_ORDER');
    }

}

?>
