<?php
/*
  $Id: login.php,v 1.2 2004/03/05 00:36:41 ccwjr Exp $

  osCommerce, Open Source E-Commerce Solutions
  http://www.oscommerce.com

  Copyright (c) 2002 osCommerce

  Released under the GNU General Public License
*/

define('HEADING_TITLE', 'Administrator Login');

define('HEADING_RETURNING_ADMIN', 'Loaded Commerce Admin Login');
define('HEADING_PASSWORD_FORGOTTEN', 'Loaded Commerce Admin Login');
define('TEXT_RETURNING_ADMIN', 'Staff only!');
define('ENTRY_FIRSTNAME', 'First Name:');
define('IMAGE_BUTTON_LOGIN', 'Submit');

define('HEADING_TITLE_FORGOTTEN', 'Forgot Passsword?');

define('TEXT_LOGIN_ERROR', 'Validation failed!<span class="ml-2">Please try again.</span>');
define('TEXT_SEND_PASSWORD', 'Send Password');
define('TEXT_BACK_TO_LOGIN', 'Back to Login');

define('TEXT_FORGOTTEN_ERROR', 'Recover password attempt (%s) failed!<span class="ml-2">Please try again.</span>');
define('TEXT_FORGOTTEN_FAIL', 'You have tried more than 3 times. For security reasons, please contact the Webmaster to get a new password.');
define('TEXT_FORGOTTEN_SUCCESS', '<b>The new password has been sent to your Email address. Please check your Email and click Back to login again.');
define('TEXT_FORGOTTEN_SUPPORT_MESSAGE','If you need assistance resetting your email address or retrieving your password please contact your webhost support or Loaded Commerce support at <a href="http://www.loadedcommerce.com/" target="_blank">www.loadedcommerce.com</a>');
define('TEXT_FORGOTTEN_USER_MESSAGE','Enter your <span class="text-white">Admin</span> login email address and a new password will be emailed to you.');
define('TEXT_PASSWORD_FORGOTTEN_TITLE','Forgotten Password');

define('ADMIN_EMAIL_SUBJECT', 'New Password');
define('ADMIN_EMAIL_TEXT', 'Hi %s,' . "\n\n" . 'You can access the admin panel with the following password. Once you accessed the admin, please change your password immediately!' . "\n\n" . 'Website: %s' . "\n" . 'Username: %s' . "\n" . 'Password: %s' . "\n\n" . 'Thanks!' . "\n" . '%s' . "\n\n" . 'This is a system automated response, please do not reply, as your answer would be unread!');
?>