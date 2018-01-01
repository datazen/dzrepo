<?php
/*
  $Id: admin_account.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Admin Account');

define('TABLE_HEADING_ACCOUNT', 'My Account');

define('TEXT_INFO_FULLNAME', '<b>Name: </b>');
define('TEXT_INFO_FIRSTNAME', '<b>First Name: </b>');
define('TEXT_INFO_LASTNAME', '<b>Last Name: </b>');
define('TEXT_INFO_EMAIL', '<b>Email Address: </b>');
define('TEXT_INFO_PASSWORD', '<b>Password: </b>');
define('TEXT_INFO_PASSWORD_HIDDEN', '-Hidden-');
define('TEXT_INFO_PASSWORD_CONFIRM', '<b>Confirm Password: </b>');
define('TEXT_INFO_CREATED', '<b>Account Created: </b>');
define('TEXT_INFO_LOGDATE', '<b>Last Access: </b>');
define('TEXT_INFO_LOGNUM', '<b>Log Number: </b>');
define('TEXT_INFO_GROUP', '<b>Group Level: </b>');
define('TEXT_EMAIL_ERROR', '<font color="red">Email address has already been used! Please try again.</font>');
define('TEXT_PASSWORD_ERROR', 'Password must be a minimum of ' . ((ENTRY_PASSWORD_MIN_LENGTH > 7) ? ENTRY_PASSWORD_MIN_LENGTH : 8) . ' characters, contain upper and lowercase characters and at least one number.');  
define('TEXT_INFO_MODIFIED', 'Last Modified: ');

define('TEXT_INFO_HEADING_DEFAULT', 'Edit Account ');
define('TEXT_INFO_HEADING_CONFIRM_PASSWORD', 'Password Confirmation ');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD', 'Current Password:');
define('TEXT_INFO_INTRO_CONFIRM_PASSWORD_ERROR', 'Incorrect password!</font>');
define('TEXT_INFO_INTRO_DEFAULT', 'Click <b>Edit button</b> to change your account.');
define('TEXT_INFO_INTRO_DEFAULT_FIRST_TIME', 'Welcome <b>%s</b>, please update your password.');
define('TEXT_INFO_INTRO_DEFAULT_FIRST', 'Welcome <b>%s</b>, we recommend you change your email (<font color="red">admin@localhost</font>) and password!');
define('TEXT_INFO_INTRO_EDIT_PROCESS', 'All fields are required. Click Update to save.');

define('JS_ALERT_FIRSTNAME',        '- Required: Firstname \n');
define('JS_ALERT_LASTNAME',         '- Required: Lastname \n');
define('JS_ALERT_EMAIL',            '- Required: Email address \n');
define('JS_ALERT_PASSWORD',         '- Required: Password \n');
define('JS_ALERT_FIRSTNAME_LENGTH', '- Firstname length must be over ');
define('JS_ALERT_LASTNAME_LENGTH',  '- Lastname length must be over ');
define('JS_ALERT_PASSWORD_LENGTH',  '- Password length must be over ');
define('JS_ALERT_EMAIL_FORMAT',     '- Email address format is invalid! \n');
define('JS_ALERT_EMAIL_USED',       '- Email address has already been used! \n');
define('JS_ALERT_PASSWORD_CONFIRM', '- Miss typing in Password Confirmation field! \n');
define('JS_ALERT_PASSWORD_NOT_HARDENED', '- The password must contain upper and lowercase characters and at least 1 number! \n'); 

define('ADMIN_EMAIL_SUBJECT', 'Personal Information Change');
define('ADMIN_EMAIL_TEXT', 'Hello %s,' . "\n\n" . 'Your personal information, perhaps including your password, has been changed.  If this was done without your knowledge or consent please contact the administrator immediatly!' . "\n\n" . 'Website : %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is a system automated response, please do not reply, as it would be unread!');

define('JS_ALERT_FIRSTNAME_1','- Firstname length must over ');
define('JS_ALERT_LASTNAME_1','- Firstname length must over ');
define('JS_ALERT_ERROR','The following error(s) occurred:');


?>
