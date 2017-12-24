<?php
/*
  $Id: $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2003 osCommerce

  Released under the GNU General Public License
*/

	$back_url = mh_href_link(FILENAME_MAILBEEZ, 'module=config_template_engine&clear=ok');
	mh_smarty_clear_compile_dir();
	mh_redirect($back_url);
?>
