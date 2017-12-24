<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

// make path work from admin
require_once(DIR_FS_CATALOG . 'mailhive/common/classes/reportbeez.php');

class report_event_log extends reportbeez {

  // class constructor
  function report_event_log() {
    // call constructor
    reportbeez::reportbeez();
    $this->code = 'report_event_log';
    $this->module = 'report_event_log';
    $this->version = '1.0'; // float value
    $this->required_mb_version = 2.0;
    $this->title = MAILBEEZ_REPORT_EVENT_LOG_TEXT_TITLE;
    $this->description = MAILBEEZ_REPORT_EVENT_LOG_TEXT_DESCRIPTION;
    $this->sort_order = MAILBEEZ_REPORT_EVENT_LOG_SORT_ORDER;
    $this->enabled = ((MAILBEEZ_REPORT_EVENT_LOG_STATUS == 'True') ? true : false);
    $this->status_key = 'MAILBEEZ_REPORT_EVENT_LOG_STATUS';
    $this->documentation_key = $this->module; // leave empty if no documentation available						
    $this->admin_action_plugins = 'run_button.php'; //;clear_button.php';
  }

  function runReport() {
    return true;
  }

  function outputReport() {
    return 'report event log!';
  }

  function keys() {
    return array('MAILBEEZ_REPORT_EVENT_LOG_STATUS', 'MAILBEEZ_REPORT_EVENT_LOG_SORT_ORDER');
  }

  function install() {
    mh_insert_config_value(array('configuration_title' => 'MailBeez Event Log Viewer',
        'configuration_key' => 'MAILBEEZ_REPORT_EVENT_LOG_STATUS',
        'configuration_value' => 'True',
        'configuration_description' => 'Do you want to activate this viewer?',
        'set_function' => 'mh_cfg_select_option(array(\'True\', \'False\'), '
    ));

    mh_insert_config_value(array('configuration_title' => 'Sort order of display.',
        'configuration_key' => 'MAILBEEZ_REPORT_EVENT_LOG_SORT_ORDER',
        'configuration_value' => '100',
        'configuration_description' => 'Sort order of display. Lowest is displayed first.',
        'set_function' => ''
    ));
  }

}

?>
