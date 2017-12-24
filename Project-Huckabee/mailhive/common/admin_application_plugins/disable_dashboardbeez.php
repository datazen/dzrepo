<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License

  v2.1
 */

///////////////////////////////////////////////////////////////////////////////
///																																					 //
///                 MailBeez Core file - do not edit                         //
///                                                                          //
///////////////////////////////////////////////////////////////////////////////

$back_url = mh_href_link(FILENAME_MAILBEEZ, 'tab=home');

mh_db_query("update " . TABLE_CONFIGURATION . " set configuration_value = 'False' where configuration_key = '" . $_GET['key'] . "'");
mh_redirect($back_url);
?>
