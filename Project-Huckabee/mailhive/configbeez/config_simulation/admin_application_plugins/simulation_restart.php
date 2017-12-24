<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	$back_url = mh_href_link(FILENAME_MAILBEEZ, 'module=config_simulation&restart=ok');
	
  // clear simulation data
	// redirect to back_url
	mh_simulation_restart();
	mh_redirect($back_url);

?>	

		