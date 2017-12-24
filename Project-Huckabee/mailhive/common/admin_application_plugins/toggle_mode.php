<?php
/*
  MailBeez Automatic Trigger Email Campaigns
  http://www.mailbeez.com

  Copyright (c) 2010 MailBeez
	
	inspired and in parts based on
  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
	
	v1.0
*/


$back_url = mh_href_link(FILENAME_MAILBEEZ, 'tab=home');

$mh_toggle_mode = (MAILBEEZ_MAILHIVE_MODE == 'simulate') ? 'production' : 'simulate';

mh_insert_config_value(array('configuration_key' => 'MAILBEEZ_MAILHIVE_MODE',
                            'configuration_value' => $mh_toggle_mode
                       ), true);
mh_redirect($back_url);



?>