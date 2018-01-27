<?php
/*
  $Id: admin_files.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Admin "Boxes" Menu');

define('TABLE_HEADING_ACTION', 'Action');
define('TABLE_HEADING_BOXES', 'Boxes');
define('TABLE_HEADING_FILENAME', 'Filenames');
define('TABLE_HEADING_GROUPS', 'Groups');
define('TABLE_HEADING_STATUS', 'Status');

define('TEXT_COUNT_BOXES', 'Boxes: ');
define('TEXT_COUNT_FILES', 'File(s): ');
define('TEXT_BOX', 'Box');

//categories access
define('TEXT_INFO_HEADING_DEFAULT_BOXES', 'Boxes: ');

define('TEXT_INFO_DEFAULT_BOXES_INTRO', 'Click the <i class="fa fa-check-circle fa-lg text-secondary"></i> button to install or the <i class="fa fa-times-circle fa-lg text-secondary"></i> button to uninstall.');
define('WARNING_UNINSTALL_TEXT', 'If you uninstall the menu, all menu entries will also be removed.');
define('TEXT_INFO_DEFAULT_BOXES_INSTALLED', ' installed');
define('TEXT_INFO_DEFAULT_BOXES_NOT_INSTALLED', ' not installed');

define('STATUS_BOX_INSTALLED', 'Installed');
define('STATUS_BOX_NOT_INSTALLED', 'Not Installed');
define('STATUS_BOX_REMOVE', 'Remove');
define('STATUS_BOX_INSTALL', 'Install');

//files access
define('TEXT_INFO_HEADING_DEFAULT_FILE', 'File: ');
define('TEXT_INFO_HEADING_DELETE_FILE', 'Remove Confirmation');
define('TEXT_INFO_HEADING_NEW_FILE', 'Store Files');

define('TEXT_INFO_DEFAULT_FILE_INTRO', 'Click the <b>Insert File</b> button to create a new menu entry in the current box: ');
define('TEXT_INFO_DELETE_FILE_INTRO', 'Remove <font color="#ff5b57"><b>%s</b></font> from <b>%s</b> box? ');
define('TEXT_INFO_NEW_FILE_INTRO', 'Check the <font color="#ff5b57"><b>left menu</b></font> to make sure you store the right files.');

define('TEXT_INFO_NEW_FILE_BOX', 'Current Box: ');

?>
