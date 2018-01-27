<?php
/*
  $Id: admin_members.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/


define('HEADING_TITLE_GROUPS', 'Admin Groups');
define('HEADING_TITLE_DEFINE', 'Menu and File Access');
define('HEADING_TITLE_MEMBERS', 'Admin Members');

define('TEXT_COUNT_GROUPS', 'Groups: ');

define('TABLE_HEADING_NAME', 'Name');
define('TABLE_HEADING_EMAIL', 'Email Address');
define('TABLE_HEADING_PASSWORD', 'Password');
define('TABLE_HEADING_CONFIRM', 'Confirm Password');
define('TABLE_HEADING_GROUPS', 'Groups Level');
define('TABLE_HEADING_CREATED', 'Account Created');
define('TABLE_HEADING_MODIFIED', 'Account Modified');
define('TABLE_HEADING_LOGDATE', 'Last Access');
define('TABLE_HEADING_LOGNUM', 'LogNum');
define('TABLE_HEADING_LOG_NUM', 'Log Number');
define('TABLE_HEADING_ACTION', 'Action');

define('TABLE_HEADING_GROUPS_NAME', 'Groups Name');
define('TABLE_HEADING_GROUPS_DEFINE', 'Boxes and Files Selection');
define('TABLE_HEADING_GROUPS_GROUP', 'Level');
define('TABLE_HEADING_GROUPS_CATEGORIES', 'Categories Permission');


define('TEXT_INFO_HEADING_DEFAULT', 'Admin Member ');
define('TEXT_INFO_HEADING_DELETE', 'Delete Permission ');
define('TEXT_INFO_HEADING_EDIT', 'Edit Admin Member ');
define('TEXT_INFO_HEADING_NEW', 'New Admin Member ');

define('TEXT_INFO_DEFAULT_INTRO', 'Member Group');
define('TEXT_INFO_DELETE_INTRO', 'Remove <b>%s</b>?');
define('TEXT_INFO_DELETE_MAIN_ADMIN', 'You can not delete the Store Owner Admin!');
define('TEXT_INFO_DELETE_INTRO_NOT', 'You can not delete %s group!');
define('TEXT_INFO_EDIT_INTRO', 'Set permission level here: ');

define('TEXT_INFO_FULLNAME', 'Name: ');
define('TEXT_INFO_FIRSTNAME', 'First Name: ');
define('TEXT_INFO_LASTNAME', 'Last Name: ');
define('TEXT_INFO_EMAIL', 'Email Address: ');
define('TEXT_INFO_PASSWORD', 'Password: ');
define('TEXT_INFO_CONFIRM', 'Confirm Password: ');
define('TEXT_INFO_CREATED', 'Account Created: ');
define('TEXT_INFO_MODIFIED', 'Account Modified: ');
define('TEXT_INFO_LOGDATE', 'Last Access: ');
define('TEXT_INFO_LOGNUM', 'Log Number: ');
define('TEXT_INFO_GROUP', 'Group Level: ');
define('TEXT_INFO_EMAIL_USED', 'E-mail address already in use.');

define('JS_ALERT_INTRO', 'You missed something! \n');
define('JS_ALERT_FIRSTNAME', '- First Name cannot be blank. \n');
define('JS_ALERT_LASTNAME', '- Last Name cannot be blank. \n');
define('JS_ALERT_EMAIL', '- Email Address cannot be blank. \n');
define('JS_ALERT_EMAIL_FORMAT', '- Email Address format is invalid. \n');
define('JS_ALERT_EMAIL_USED', '- Email Address already exists. \n');
define('JS_ALERT_GROUP_LEVEL', '- Group Level not selected \n');

define('ADMIN_EMAIL_SUBJECT', 'New Admin Member');
define('ADMIN_EMAIL_TEXT', 'Hi %s,' . "\n\n" . 'You can access the admin panel with the following password. Once you access the admin, please change your password!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is a system automated response, please do not reply, as it would be unread!');
define('ADMIN_EMAIL_EDIT_SUBJECT', 'Admin Member Profile Edit');
define('ADMIN_EMAIL_EDIT_TEXT', 'Hi %s,' . "\n\n" . 'Your personal information has been updated by an administrator.' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is a system automated response, please do not reply, as it would be unread!');

define('TEXT_INFO_HEADING_DEFAULT_GROUPS', 'Admin Group ');
define('TEXT_INFO_HEADING_DELETE_GROUPS', 'Delete Group ');

define('TEXT_INFO_DEFAULT_GROUPS_INTRO', '');
define('TEXT_INFO_DELETE_GROUPS_INTRO', 'This will also delete <b>ALL</b> members of this group.  Are you sure want to delete <nobr><b>%s</b> group?</nobr>');
define('TEXT_INFO_DELETE_GROUPS_INTRO_NOT', 'You can not delete this group.');

define('TEXT_INFO_HEADING_GROUPS', 'New Group');
define('TEXT_INFO_GROUPS_NAME', 'Unique Group Name:');
define('TEXT_INFO_GROUPS_NAME_FALSE', 'Group Name must have at least 5 characters!');
define('TEXT_INFO_GROUPS_NAME_USED', 'Group Name has already been used!');
define('TEXT_INFO_GROUPS_LEVEL', 'Group Level: ');
define('TEXT_INFO_GROUPS_BOXES', '<b>Boxes Permission:</b><br>Give access to selected boxes.');
define('TEXT_INFO_GROUPS_BOXES_INCLUDE', 'Include files stored in: ');

define('TEXT_INFO_EDIT_GROUP_INTRO', 'Unique Group Name: ');

define('TEXT_INFO_HEADING_DEFINE', '%s Group');
define('TEXT_INFO_DEFINE_INTRO_1', 'You can not change permissions for <b>%s</b> group.');
define('TEXT_INFO_DEFINE_INTRO', '<div class="fw-400 mt-3 text-ltgray">Change permission for this group by selecting or unselecting boxes and files provided. Click <b>Update</b> to save the changes.</div>');
define('TEXT_INFO_DEFINE_TYPE', '<div class="mt-4"><div class="info-define-type-menu p-4 text-center f-w-600">Denotes Menu Access.</div><div class="info-define-type-file mt-2 mb-2 p-4 text-center">Denotes File Access.</div></div>');

define('TEXT_INFO_HEADING_EDIT_GROUP', 'Edit Group');
define('IMAGE_FILE_PERMISSIONS', 'Menu/File Access');
?>