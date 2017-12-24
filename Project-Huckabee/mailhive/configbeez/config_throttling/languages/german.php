<?php 
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

*/

	define('MAILBEEZ_CONFIG_THROTTLING_TEXT_TITLE', 'Emailversand Drosseln');
	define('MAILBEEZ_CONFIG_THROTTLING_TEXT_DESCRIPTION', 'Einige Hoster erlauben den Versand von Emails nur mit begrenzter Rate, z.B. 480 Emails pro Stunde.<br>
Mit dieser Konfiguration kann der Email Versand gedrosselt werden.
	
	' . ( ( defined('MAILBEEZ_THROTTLING_RATE') ) ? '' : '<div class="pro">Um die Drosselung des Emailversandes zu nutzen, bitte den <a href="http://www.mailbeez.com/documentation/filterbeez/filter_do_throttling_simple/' . MH_LINKID_1 . '" target="_blank">Simple Throttling Filter</a> installieren.</div>') );
 
		
	
 ?>