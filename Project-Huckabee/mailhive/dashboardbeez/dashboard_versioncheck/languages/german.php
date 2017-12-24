<?php 
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez
	
	inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_STATUS_TITLE', 'Modul aktivieren?');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_STATUS_DESC', 'Dieses Modul aktivieren');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_SORT_ORDER_TITLE', 'Anzeigereihenfolge');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_SORT_ORDER_DESC', 'Reihenfolge der Anzeige. Kleinste Ziffer wird zuerst angezeigt.');

define('MH_BUTTON_VERSION_CHECK_CLEAR', 'Zur&uuml;cksetzen');

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_TITLE', 'Versions Pr&uuml;fung');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_DESCRIPTION', 'Voll-Integrierte Versionspr&uuml;fung');

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_TEXT', 'Letzte Pr&uuml;fung: %s');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT', 'Ergebnisse<br>');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_UPD_CNT', 'Updates verf&uuml;gbar');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_NEW_CNT', 'weitere Module verf&uuml;gbar');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_UPD_OK', 'Alle Module ok');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_RESULT_NEW_OK', 'Alle Module installiert');

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_TEXT_NCURL', 'Die vollintegrierte Versionspr&uuml;fung erfordert das PHP modul "CURL", welches auf diesem Server nicht vorhanden ist. Daher steht nur die einfache Versionspr&uuml;fung im Popup zur Verf&uuml;gung');


define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_TITLE', 'MailBeez Status');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_TEXT', 'Der MailBeez Status auf einen Blick');

define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_TITLE', 'Status');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_PRODUCTION_TEXT', MAILBEEZ_MODE_SET_PRODUCTION_TEXT);
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_SIMULATE_TEXT', MAILBEEZ_MODE_SET_SIMULATE_TEXT);
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_PRODUCTION_DESC', 'Emails werden an Kunden verschickt');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_SIMULATE_DESC', 'Alle Emails werden an <i>' . MAILBEEZ_CONFIG_SIMULATION_EMAIL . '</i> geschickt');
define('MAILBEEZ_DASHBOARD_VERSIONCHECK_MODE_SET_SIMULATE_CONFIG', 'Simulations Einstellungen');


?>