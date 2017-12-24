<?php 
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez
	
*/

	define('MAILBEEZ_CONFIG_THROTTLING_TEXT_TITLE', 'Email Throttling');
	define('MAILBEEZ_CONFIG_THROTTLING_TEXT_DESCRIPTION', 'Some hosters allow you to only send a certain number of emails per hours.<br>
	Configure email throttling e.g. 480 Emails per hour.' . ( ( defined('MAILBEEZ_THROTTLING_RATE') ) ? '' : '<div class="pro">To enable throttling, please install the <a href="http://www.mailbeez.com/documentation/filterbeez/filter_do_throttling_simple/' . MH_LINKID_1 . '" target="_blank">Simple Throttling Filter</a></div>') );



 ?>