<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.0

  event log functions
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

mh_define('TABLE_MAILBEEZ_EVENT_LOG', DB_PREFIX . 'mailbeez_event_log');

$array_event_log_levels = explode(", ", MAILBEEZ_CONFIG_EVENT_LOG_LEVEL);
while (list(, $key) = each($array_event_log_levels)) {
  define('MAILBBEEZ_EVENTLOG_LEVEL_' . $key, 'True');
}

function mh_event_log($event_type, $log_entry='', $module = '', $class = '', $parameters = '') {

  // generate batch id
  if (!defined('MAILBBEEZ_EVENTLOG_BATCH_ID')) {
    $last_batch = mh_event_log_getLastBatchId();
    define('MAILBBEEZ_EVENTLOG_BATCH_ID', $last_batch + 1);
  }

  // log level
  if ($event_type == 'MAILBEEZ_MODULE_INIT'
          && !( defined('MAILBBEEZ_EVENTLOG_LEVEL_MODULE_INIT')
          && MAILBBEEZ_EVENTLOG_LEVEL_MODULE_INIT == 'True')) {
    return false;
  }
  if ($event_type == 'MODULE_QUERY'
          && !( defined('MAILBBEEZ_EVENTLOG_LEVEL_MODULE_SQL')
          && MAILBBEEZ_EVENTLOG_LEVEL_MODULE_SQL == 'True')) {
    return false;
  }

  $sql_data_array = array('event_type' => $event_type,
      'log_entry' => $log_entry,
      'batch_id' => MAILBBEEZ_EVENTLOG_BATCH_ID,
      'module' => $module,
      'class' => $class,
      'result' => '',
      'parameters' => '',
      'log_date' => 'now()',
      'query_string' => $_SERVER["QUERY_STRING"],
      'simulation' => MAILBEEZ_SIMULATION_ID
  );
  return mh_db_perform(TABLE_MAILBEEZ_EVENT_LOG, $sql_data_array);
}

function mh_event_log_getLastBatchId() {
  $last_id_query = mh_db_query("select max(batch_id) as batch_id_last from " . TABLE_MAILBEEZ_EVENT_LOG);
  $last_id = mh_db_fetch_array($last_id_query);
  return $last_id['batch_id_last'];
}

function mh_module_query() {
  $args = func_get_args();
  $module_obj = array_shift($args);

  //print_r($module_obj);
  //print_r($args);

  mh_event_log('MODULE_QUERY', str_replace(array("\r\n", "\n", "\r", "\t", "  "), ' ', $args[0]), $module_obj->module, $class = '', $parameters = '');
  return call_user_func_array('mh_db_query', $args);
}

?>