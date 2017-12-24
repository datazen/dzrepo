<?php

/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

  inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
 */

define('MH_INSTALL_INTRO', 'Bitte installiere MailHive mit Klick auf den Button');
define('MH_INSTALL_SUPPORT', 'Solltest du Schwierigkeiten mit der Installation haben, bitte die  <a href="http://www.mailbeez.com/documentation/installation/" target="_blank"><b><u>Installations Anleitung</u></b></a> lesen.<br>
									<br>
									Nachdem du dies mindestens 3x gemacht hast, darfst du gerne den <a href="http://www.mailbeez.com/support/" target="_blank"><b><u>MailBeez-Support</u></b></a> nutzen ;-)');

define('MH_RATE_TRUSTPILOT_LINK', 'bitte bewerte MailBeez auf Trustpilot');

define('MH_SECURE_URL', 'Sichere Cronjob-URL (f&uuml;hrt sofort alle aktiven MailBeez Module aus - im konfiguierten Mode)');

define('MH_BUTTON_VERSION_CHECK', 'Pr&uuml;fe auf Updates');
define('MH_BUTTON_BACK_CONFIGURATION', 'zur Konfiguration');
define('MH_BUTTON_BACK_DASHBOARD', 'zum Dashboard');
define('MH_BUTTON_BACK_REPORTS', 'zu Berichten');

define('MH_DASHBOARD_CONFIG', 'konfigurieren');
define('MH_DASHBOARD_REMOVE', 'x');

define('HEADING_TITLE', 'MailBeez - Einfaches Automatisches Emailmarketing');
define('TEXT_DOCUMENTATION', 'Dokumentation verf&uuml;gbar');
define('TEXT_VIEW_ONLINE', 'Online ansehen');
define('TEXT_UPGRADE_MAILBEEZ', 'Diese Modul erfordert MailBeez Version %s or h&ouml;her. Bitte MailBeez updaten.');
define('WARNING_SIMULATE', 'SIMULATION-MODE: es werden keine Emails verschickt');
define('WARNING_OFFLINE', 'DISABLED: MailBeez werden nicht ausgef&uuml;hrt');

define('MH_NO_MODULE', 'Keine Module vorhanden.');

define('MH_TAB_HOME', 'Dashboard');
define('MH_TAB_MAILBEEZ', 'MailBeez Module');
define('MH_TAB_CONFIGURATION', 'Konfiguration');
define('MH_TAB_FILTER', 'Filter &amp; Hilfsmodule');
define('MH_TAB_REPORT', 'Berichte');
define('MH_TAB_ABOUT', '&Uuml;ber MailBeez');
define('MH_HEADER_DASHBOARD_MODULES', 'Dashboard Module');
define('MH_MSG_EMPTY_DASHBOARD_AREA', 'Hier ist Platz f&uuml;r ein Dashboard-Modul');

define('MH_HOME_ACTIONS', 'Aktionen');
define('MH_HOME_RESOURCES', 'Mehr Informationen');

define('MH_DOWNLOAD_LINK_LIST', 'Finde weitere MailBeez Module...');
define('MH_DASHBOARD_CONFIG_BUTTON', 'Dashboard konfigurieren');

// config
define('MAILBEEZ_MAILHIVE_TEXT_TITLE', 'MailHive - Grundeinstellungen');
if (MAILBEEZ_CONFIG_INSTALLED == 'config.php' && MAILBEEZ_INSTALLED == '') {
  define('MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION', 'Grundeinstellungen von MailHive.');
} else {
  define('MAILBEEZ_MAILHIVE_TEXT_DESCRIPTION', 'Grundeinstellungen von MailHive. <br>
		<br>MailHive kann erst entfernt werden, wenn alle installierten Module entfernt wurden.');
}

define('MAILBEEZ_MAILHIVE_STATUS_TITLE', 'Modul aktivieren?');
define('MAILBEEZ_MAILHIVE_STATUS_DESC', 'MailHive und MailBeez aktivieren');

define('MAILBEEZ_MAILHIVE_COPY_TITLE', 'Kopie verschicken');
define('MAILBEEZ_MAILHIVE_COPY_DESC', 'Eine Kopie der generierten Emails verschicken?');

define('MAILBEEZ_MAILHIVE_EMAIL_COPY_TITLE', 'Kopie verschicken an');
define('MAILBEEZ_MAILHIVE_EMAIL_COPY_DESC', 'Email-Adresse, an die eine Kopie gehen soll');

define('MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT_TITLE', 'Max. Anzahl an Kopien');
define('MAILBEEZ_MAILHIVE_EMAIL_COPY_MAX_COUNT_DESC', 'Maximale Anzahl an Kopien per Modul');

define('MAILBEEZ_MAILHIVE_TOKEN_TITLE', 'Sicherheits-Schl&uuml;ssel');
define('MAILBEEZ_MAILHIVE_TOKEN_DESC', 'Teil der URL, um unberechtigen Aufruf zu vermeiden. Automatisch generiert oder eigenen Schl&uuml;ssel angeben.');

define('MAILBEEZ_MAILHIVE_POPUP_MODE_TITLE', 'Popup Fenster');
define('MAILBEEZ_MAILHIVE_POPUP_MODE_DESC', 'Bitte nur &auml;ndern, falls die AJAX (CeeBox) Popups nicht richtig &ouml;ffnen - z.B. bei der Vorschau der Vorlagen');

define('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_TITLE', 'Versions-Check Erinnerung');
define('MAILBEEZ_MAILHIVE_UPDATE_REMINDER_DESC', 'Automatisch erinnern, auf neue Version zu pr&uuml;fen?');

define('MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED_TITLE', '"Early Check" aktivieren');
define('MAILBEEZ_MAILHIVE_EARLY_CHECK_ENABLED_DESC', 'Falls vom Modul verwendet, kann der "Early Check" aktiviert oder deaktiviert werden. "Early Check" entfernt schon bei Generieren der Sende-Liste Empf&auml;nger, welche bereits eine Email erhalten haben.');


// config_dashboard
define('MAILBEEZ_CONFIG_DASHBOARD_TEXT_TITLE', 'Dashboard Konfiguration');
define('MAILBEEZ_CONFIG_DASHBOARD_TEXT_DESCRIPTION', 'W&auml;hle, wie dein MailBeez Dashboard aussehen soll');

define('MAILBEEZ_CONFIG_DASHBOARD_START_TITLE', 'Start-Tab');
define('MAILBEEZ_CONFIG_DASHBOARD_START_DESC', 'Mit welchem Tab starten? (empfohlen: home)');


// config_googleanalytics
define('MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_TITLE', 'Google Analytics Integration f&uuml;r Automatische Kampagnen');
define('MAILBEEZ_CONFIG_GOOGLEANALYTICS_TEXT_DESCRIPTION', 'Konfiguration f&uuml;r Google Analytics URL Rewrite.<br><br>
	<img src="' . MH_CATALOG_SERVER . DIR_WS_CATALOG . "/mailhive/common/images/analytics_logo.gif" . '" width="213" height="40" alt="" border="0" align="absmiddle" hspace="1">');

define('MAILBEEZ_MAILHIVE_GA_ENABLED_TITLE', 'Google Analytics Integration aktiv');
define('MAILBEEZ_MAILHIVE_GA_ENABLED_DESCRIPTION', 'Google Analytics Integration (automatisches Umschreiben von Links) aktivieren?');

define('MAILBEEZ_MAILHIVE_GA_REWRITE_MODE_TITLE', 'Google Analytics Umschreib-Modus');
define('MAILBEEZ_MAILHIVE_GA_REWRITE_MODE_DESC', 'Welche Links sollen umgebschrieben werden?');

define('MAILBEEZ_MAILHIVE_GA_MEDIUM_TITLE', 'Google Analytics Kampagne "Medium"');
define('MAILBEEZ_MAILHIVE_GA_MEDIUM_DESC', 'Welches "Medium" soll f&uuml;r die Google Analytics Kampagnen genutzt werden? (standard: email)');

define('MAILBEEZ_MAILHIVE_GA_SOURCE_TITLE', 'Google Analytics Kampagne "Source"');
define('MAILBEEZ_MAILHIVE_GA_SOURCE_DESC', 'Welche "Source" soll f&uuml;r die Google Analytics Kampagnen genutzt werden? (standard: MailBeez)');

// config_simulation
define('MAILBEEZ_CONFIG_SIMULATION_TEXT_TITLE', 'Simulation');
define('MAILBEEZ_CONFIG_SIMULATION_TEXT_DESCRIPTION', 'Einstellungen f&uuml;r MailBeez Advanced Simulations.<br>
	<br>Advanced Simulations erlaubt, vollst&auml;ndige und realistische Simulationen inkl. Versand-Tracking durchzuf&uuml;hren.
	Simulations Emails werden NICHT an Kunden geschickt (auch wenn es so aussieht), sondern nur an die konfigurierte Email Adresse');

define('MAILBEEZ_MAILHIVE_MODE_TITLE', 'Betriebsart');
define('MAILBEEZ_MAILHIVE_MODE_DESC', 'Zum Testen bitte "Simulation" w&auml;hlen, in "Produktion" werden Emails an Kunden verschickt!');

define('MAILBEEZ_CONFIG_SIMULATION_EMAIL_TITLE', 'Email-Adresse f&uuml;r Simulationen');
define('MAILBEEZ_CONFIG_SIMULATION_EMAIL_DESC', 'An welche Email-Adresse sollen die Simulations-Emails geschickt werden?');

define('MAILBEEZ_CONFIG_SIMULATION_COPY_TITLE', 'Kopie-Versand bei Simulation');
define('MAILBEEZ_CONFIG_SIMULATION_COPY_DESC', 'Kopien an die konfigurierte Kopie-Adresse (' . MAILBEEZ_MAILHIVE_EMAIL_COPY . ') auch im Simulations Modus verschicken?');

define('MAILBEEZ_CONFIG_SIMULATION_TRACKING_TITLE', 'Simulations-Protokollierung');
define('MAILBEEZ_CONFIG_SIMULATION_TRACKING_DESC', 'Im Simulations Modus den Versand protokollieren? Zum Neu-Start von Simulationen das Simulations-Protokoll l&ouml;schen');


// config_template_engine
define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_TITLE', 'Template System');
define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_TEXT_DESCRIPTION', 'Konfiguration der Smarty Template Engine.<br>
	<br>	<a href="http://www.smarty.net" target="_blank"><img src="' . MH_CATALOG_SERVER . DIR_WS_CATALOG . "/mailhive/common/images/smarty_icon.gif" . '" width="88" height="31" alt="" border="0" align="absmiddle" hspace="1"></a>');

define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE_TITLE', 'Kompatibilit&auml;t des Template-Systems');
define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_COMP_MODE_DESC', '"True" f&uuml;r Unterst&uuml;tzung von 1.x MailBeez Templates');

define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH_TITLE', 'Pfad zu Smarty');
define('MAILBEEZ_CONFIG_TEMPLATE_ENGINE_SMARTY_PATH_DESC', 'Pfad zum Smarty Template system /Smarty.class.php<br>im Ordner <br>mailhive/common/classes/');

// config_event_log
define('MAILBEEZ_CONFIG_EVENT_LOG_TEXT_TITLE', 'Ereignis Protokollierung');
define('MAILBEEZ_CONFIG_EVENT_LOG_TEXT_DESCRIPTION', 'Einstellungen f&uuml;r die Protokollierung von Ereignissen bei der Ausf&uuml;hrung von MailBeez.');


// about
define('MH_ABOUT', '<b style="font-size: 20px; font-weight: bold;">&Uuml;ber MailBeez ' . ((defined('MAILBEEZ_VERSION')) ? MAILBEEZ_VERSION : '' ) . '</b><br><br>
	MailBeez Version ' . ((defined('MAILBEEZ_VERSION')) ? MAILBEEZ_VERSION : '' ) . ',	erkannte Plattform: <b>' . MH_PLATFORM . '</b><br>
	
	Entwickelt von: Cord F. Rosted <a href="mailto:' . MAILBEEZ_CONTACT_EMAIL . '">' . MAILBEEZ_CONTACT_EMAIL .'</a> <br>
	(kontakt auf Deutsch, English, Dansk)');

define('MH_ABOUT_BUTTONS_FEATURE', 'Funktionen anfragen');
define('MH_ABOUT_BUTTONS_RATE_READ', 'Bewertungen lesen');
define('MH_ABOUT_BUTTONS_RATE_RATE', 'Eigene Bewertung abgeben');

$trustpilot_evaluate = 'http://www.trustpilot.de/evaluate/www.mailbeez.com';


define('MH_MAILBEEZ_LOVE', 'Dir gef&auml;llt MailBeez?');
define('MH_MAILBEEZ_LOVE_TEXT', 'Haben die fleissigen MailBeez geholfen, deine alten Kunden wiederzugewinnen? <br>
	Und zu mehr Umsatz durch die vielen Produktbewertungen?	
	<br><br>
	Du darst gerne den MailBeez mit einer Spende danken - und Dich auf die Weiterentwicklung freuen.');

define('MH_MAILBEEZ_LOVE_BTN', 'btn_donate_DE.gif');


// new with MailBeez V2.1

define('MH_VERSIONCHECK_INFO_DASHBOARD', 'Es gibt neue Dashboard Module und/oder neu Versionen. Bitte in der Dashboard Konfiguration pr&uuml;fen.');
define('MH_VERSIONCHECK_INFO_NEW', 'Diese %s Module sind noch nicht installiert');
define('MH_VERSIONCHECK_INFO_NEW_MORE', 'mehr Information');
define('MH_VERSIONCHECK_INFO_NEWVERSION', 'Neue Version');


// new with MailBeez V2.2
// config_process_control
define('MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_TITLE', 'MailHive Prozess Kontrolle');
define('MAILBEEZ_CONFIG_PROCESS_CONTROL_TEXT_DESCRIPTION', 'Einstellungen f&uuml; Prozess Kontrolle Control Settings - Nur f&uuml;r Nerds!');
define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_TITLE', 'MailHive Prozess Kontrolle aktive?');
define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_DESCRIPTION', 'MailHive Prozess Kontrolle ist aktiv bei True (empfohlen).');
define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD_TITLE', 'Lock Periode');
define('MAILBEEZ_MAILHIVE_PROCESS_CONTROL_LOCK_PERIOD_DESCRIPTION', 'Lock Periode in Sekunden.');

// action plugin view templates
define('MAILBEEZ_ACTION_VIEW_TEMPLATE_HEADLINE', 'Email Templates');
define('MAILBEEZ_ACTION_VIEW_TEMPLATE_TEXT', 'Vorschau der Email Templates:');
define('MAILBEEZ_BUTTON_VIEW_HTML', 'HTML');
define('MAILBEEZ_BUTTON_VIEW_TXT', 'TXT');


// action plugin list recipients
define('MAILBEEZ_ACTION_LIST_RECIPIENTS_HEADLINE', 'Empf&auml;nger');
define('MAILBEEZ_ACTION_LIST_RECIPIENTS_TEXT', 'Die aktuellen Empf&auml;nger auflisten:');
define('MAILBEEZ_BUTTON_LIST_RECIPIENTS', 'Zeigen');

// action plugin send testmail
define('MAILBEEZ_ACTION_SEND_TESTMAIL_HEADLINE', 'Sende Test Email');
define('MAILBEEZ_ACTION_SEND_TESTMAIL_TEXT', 'Sende eine Test Email mit Test Daten:');
define('MAILBEEZ_BUTTON_SEND_TESTMAIL', 'Senden...');

// action plugin run module
define('MAILBEEZ_ACTION_RUN_MODULE_HEADLINE', 'Modul Ausf&uuml;hren');
define('MAILBEEZ_ACTION_RUN_MODULE_TEXT', 'Dieses Module ausf&uuml;hren im Modus: ' . MAILBEEZ_MAILHIVE_MODE);
define('MAILBEEZ_BUTTON_RUN_MODULE', 'Ausf&uuml;hren...');

// action plugin edit dashboard
define('MAILBEEZ_ACTION_EDIT_DASHBOARD_HEADLINE', 'Dashboard Module');
define('MAILBEEZ_ACTION_EDIT_DASHBOARD_TEXT', 'Dashboard Module hinzuf&uuml;gen, entfernen und bearbeiten');
define('MAILBEEZ_BUTTON_EDIT_DASHBOARD', 'Bearbeiten...');


// action plugin control simulation
define('MAILBEEZ_ACTION_SIMULATION_RESTART_HEADLINE', 'Simulation');
define('MAILBEEZ_ACTION_SIMULATION_RESTART_TEXT', 'Simulation neu starten - alle Simulationsdaten werden gel&ouml;scht.');
define('MAILBEEZ_ACTION_SIMULATION_RESTART_OK', 'Simulation neu gestartet.');
define('MAILBEEZ_BUTTON_SIMULATION_RESTART', 'Neu-Start');

// action plugin template engine
define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_HEADLINE', 'Template System');
define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_TEXT', 'Alle kompilierten Template Dateien l&ouml;schen.');
define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_OK', 'Dateien gel&ouml;scht');
define('MAILBEEZ_ACTION_TEMPLATEENGINE_CLEAR_INFO', 'Anzahl kompilierter Template Dateien');
define('MAILBEEZ_BUTTON_TEMPLATEENGINE_CLEAR', 'Clear');


define('MAILBEEZ_VERSION_CHECK_MSG_INTRO', 'MailBeez sagt:');


define('MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL_TITLE', 'Emails beim Senden zeigen');
define('MAILBEEZ_MAILHIVE_RUN_SHOW_EMAIL_DESC', 'W&auml;hle True, um die generierten Emails beim Senden zu sehen.');

define('MAILBEEZ_MAILHIVE_MODE_SWITCH_TEXT', (MAILBEEZ_MAILHIVE_MODE == 'simulate') ? 'Schalte auf "production"' : 'Schalte auf "simulate"' );


// new in MailBeez V2.5 - kill process
// config_process_control
define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_HEADLINE', 'Prozess abbrechen');
define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_TEXT', 'Einmal gestartet, kann der Versandprozess einige Stunden ablaufen - z.B. bei gedrosseltem Versand.
<br>Mit klick auf "STOP" wird der Versandprozess baldm&ouml;glichst nach dem Versand der n&auml;chsten Email abgebrochen.');
define('MAILBEEZ_ACTION_PROCESS_CONTROL_KILL_OK', 'Prozess Abbruch eingeleitet');
define('MAILBEEZ_BUTTON_PROCESS_CONTROL_KILL', 'STOP');

// new in MailBeez V2.5 - configure email engine
// config_email_engine
define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_TITLE', 'Email System');
define('MAILBEEZ_CONFIG_EMAIL_ENGINE_TEXT_DESCRIPTION', 'Konfiguration des Email Systems - nur &auml;ndern, wenn es Probleme gibt');


define('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE_TITLE', 'Zencart Email Template System umgehen?');
define('MAILBEEZ_MAILHIVE_ZENCART_OVERRIDE_DESC', 'M&ouml;chtest du das Zencart Email Template System umgehen?<br>Bei "False" wird der von MailBeez generierte Inhalt in das Template "emails/email_template_default.html" oder  "emails/email_template_mailbeez.html" - falls vorhanden - eingef&uuml;gt. ');

define('MAILBEEZ_CONFIG_EMAIL_BUGFIX_1_TITLE', 'Double Dot Bugfix');
define('MAILBEEZ_CONFIG_EMAIL_BUGFIX_1_DESC', 'In seltenen F&auml;llen wird ein zweiter punkt eingef&uuml;gt, z.B.. file.php wird zu file..php, image.png becomes image..png. Diesen Bug versuchen zu fixen?');


define('MAILBEEZ_MODE_SET_SIMULATE_TEXT', 'Simulation');
define('MAILBEEZ_MODE_SET_PRODUCTION_TEXT', 'Produktion');

?>