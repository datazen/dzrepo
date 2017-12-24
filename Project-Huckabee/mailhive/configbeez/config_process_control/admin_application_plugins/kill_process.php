<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

$back_url = mh_href_link(FILENAME_MAILBEEZ, 'module=config_process_control&clear=ok');

require_once(DIR_FS_CATALOG . 'mailhive/configbeez/config_process_control.php');

config_process_control::set_kill();

mh_redirect($back_url);
?>
