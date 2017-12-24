<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010, 2011 MailBeez

 */

if (defined('MAILBEEZ_CRON_SIMPLE_STATUS') && MAILBEEZ_CRON_SIMPLE_STATUS == 'True') {
    require_once(DIR_FS_CATALOG . 'mailhive/configbeez/config_cron_simple/includes/cron_simple_inc.php');
}

if (defined('MAILBEEZ_CRON_ADVANCED_STATUS') && MAILBEEZ_CRON_ADVANCED_STATUS == 'True') {
    require_once(DIR_FS_CATALOG . 'mailhive/configbeez/config_cron_advanced/includes/cron_advanced_inc.php');
}

?>
