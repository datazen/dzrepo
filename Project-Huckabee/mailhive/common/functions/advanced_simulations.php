<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.1

  simulation functions
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

define('MAILBEEZ_SIMULATION', (MAILBEEZ_MAILHIVE_MODE == 'production') ? 'False' : 'True');
define('MAILBEEZ_SIMULATION_SQL', (MAILBEEZ_SIMULATION == 'True') ? ' and simulation > 0' : ' and simulation = 0');
define('MAILBEEZ_SIMULATION_ID', (MAILBEEZ_SIMULATION == 'True') ? 1 : 0);
define('MAILBEEZ_SIMULATION_TAG', '[SIM]');

function mh_simulation_info() {
  $count_query = mh_db_query("select count(*) as cnt from " . TABLE_MAILBEEZ_TRACKING . " where simulation > 0");
  $count = mh_db_fetch_array($count_query);
  $count_query = mh_db_query("select count(*) as cnt from " . TABLE_MAILBEEZ_BLOCK . " where simulation > 0");

  // todo:
  // collect simulation data of modules
  // modules with own simulation data need to provide this method
}

function mh_simulation_restart() {
  mh_db_query("delete from " . TABLE_MAILBEEZ_TRACKING . " where simulation > 0");
  mh_db_query("delete from " . TABLE_MAILBEEZ_BLOCK . " where simulation > 0");

  // todo: call simulation_restart method of all modules
  // modules with own simulation data need to provide this method
}

?>