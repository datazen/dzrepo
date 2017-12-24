<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

define('MAILBEEZ_DASHBOARD_INTRO_CONTENT', '<div id="WidgetTitle">' . MAILBEEZ_DASHBOARD_INTRO_TITLE . '</div>
    <div id="WidgetSubTitle">' . MAILBEEZ_DASHBOARD_INTRO_TEXT . '</div>
	<ul class="intros">
		<li class="intros_item item_tutorials"><a href="http://www.mailbeez.com/documentation/tutorials/' . MH_LINKID_1 . '" target="_blank"><div class="intros_item_img"></div></a><div class="intros_item"><a href="http://www.mailbeez.com/documentation/tutorials/' . MH_LINKID_1 . '" target="_blank"><b>' . MAILBEEZ_DASHBOARD_INTRO_TITLE_TUTORIAL . '</b></a><br>' . MAILBEEZ_DASHBOARD_INTRO_TEXT_TUTORIAL . '</div></li>
		<li class="intros_item item_support"><a href="http://www.mailbeez.com/support/' . MH_LINKID_1 . '" target="_blank"><div class="intros_item_img"></div></a><div class="intros_item"><a href="http://www.mailbeez.com/support/' . MH_LINKID_1 . '" target="_blank"><b>' . MAILBEEZ_DASHBOARD_INTRO_TITLE_SUPPORT . '</b></a><br>' . MAILBEEZ_DASHBOARD_INTRO_TEXT_SUPPORT . '</div></li>
		<li class="intros_item item_facebook"><a href="http://www.facebook.com/pages/MailBeez/124275137595496" target="_blank"><div class="intros_item_img"></div></a><div class="intros_item"><a href="http://www.facebook.com/pages/MailBeez/124275137595496" target="_blank"><b>' . MAILBEEZ_DASHBOARD_INTRO_TITLE_FACEBOOK . '</b></a><br>' . MAILBEEZ_DASHBOARD_INTRO_TEXT_FACEBOOK . '</div></li>
		<li class="intros_item item_twitter"><a href="http://twitter.com/mailbeez" target="_blank"><div class="intros_item_img"></div></a><div class="intros_item"><a href="http://twitter.com/mailbeez" target="_blank"><b>' . MAILBEEZ_DASHBOARD_INTRO_TITLE_TWITTER . '</b></a><br>' . MAILBEEZ_DASHBOARD_INTRO_TEXT_TWITTER . '</div></li>
	</ul>');

require_once(DIR_FS_CATALOG . 'mailhive/common/classes/dashboardbeez.php');

class dashboard_intro extends dashboardbeez
{

    var $code;
    var $module;
    var $title;
    var $description;
    var $sort_order;
    var $enabled = false;

    function dashboard_intro()
    {
        dashboardbeez::dashboardbeez();
        $this->code = 'dashboard_intro';
        $this->module = 'dashboard_intro';
        $this->version = '1.1'; // float value
        $this->required_mb_version = 2.2;
        $this->title = MAILBEEZ_DASHBOARD_INTRO_TITLE;
        $this->description = MAILBEEZ_DASHBOARD_INTRO_DESCRIPTION;
        $this->status_key = 'MAILBEEZ_DASHBOARD_INTRO_STATUS';

        if (defined('MAILBEEZ_DASHBOARD_INTRO_STATUS')) {
            $this->sort_order = MAILBEEZ_DASHBOARD_INTRO_SORT_ORDER;
            $this->enabled = (MAILBEEZ_DASHBOARD_INTRO_STATUS == 'True');
        }
    }

    function getOutput()
    {
        $output = '<link rel="stylesheet" type="text/css" media="print, projection, screen" href="' . MH_CATALOG_SERVER . DIR_WS_CATALOG . '/mailhive/dashboardbeez/dashboard_intro/dashboard_intro.css" >';
        $output .= MAILBEEZ_DASHBOARD_INTRO_CONTENT;

        return $output;
    }

    function check()
    {
        return defined('MAILBEEZ_DASHBOARD_INTRO_STATUS');
    }

    function install()
    {
        mh_insert_config_value(array('configuration_title' => 'Show MailBeez Intro',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_INTRO_STATUS',
                                    'configuration_value' => 'True',
                                    'configuration_description' => 'Do you want to show the MailBeez Intro on the dashboard? (recommended)',
                                    'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
                               ));

        mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
                                    'configuration_key' => 'MAILBEEZ_DASHBOARD_INTRO_SORT_ORDER',
                                    'configuration_value' => '40',
                                    'configuration_description' => 'Sort order of display. Lowest is displayed first.',
                                    'set_function' => ''
                               ));
    }

    function keys()
    {
        return array('MAILBEEZ_DASHBOARD_INTRO_STATUS', 'MAILBEEZ_DASHBOARD_INTRO_SORT_ORDER');
    }

}

?>
